<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocumentController extends Controller
{


    



        public function search(Request $request)
    {
        try {
            $filters = $request->input('filters', []);
            
            // Start with base query
            $query = Document::with('relation');
            
            // Apply filters
            foreach ($filters as $filter) {
                $column = $filter['column'] ?? '';
                $relation = $filter['relation'] ?? '';
                $value = $filter['value'] ?? '';
                
                if (empty($column) || empty($relation) || empty($value)) {
                    continue;
                }
                
                // Apply filter based on relation type
                switch ($relation) {
                    case 'equals':
                        if ($column === 'is_disabled') {
                            // Handle boolean conversion for is_disabled
                            $boolValue = in_array(strtolower($value), ['true', '1', 'yes', 'active']) ? false : true;
                            if (strtolower($value) === 'disabled') {
                                $boolValue = true;
                            } elseif (strtolower($value) === 'active') {
                                $boolValue = false;
                            }
                            $query->where($column, $boolValue);
                        } else {
                            $query->where($column, '=', $value);
                        }
                        break;
                        
                    case 'not_equal':
                        if ($column === 'is_disabled') {
                            $boolValue = in_array(strtolower($value), ['true', '1', 'yes', 'active']) ? false : true;
                            if (strtolower($value) === 'disabled') {
                                $boolValue = true;
                            } elseif (strtolower($value) === 'active') {
                                $boolValue = false;
                            }
                            $query->where($column, '!=', $boolValue);
                        } else {
                            $query->where($column, '!=', $value);
                        }
                        break;
                        
                    case 'contains':
                        $query->where($column, 'LIKE', '%' . $value . '%');
                        break;
                        
                    case 'starts_with':
                        $query->where($column, 'LIKE', $value . '%');
                        break;
                        
                    case 'ends_with':
                        $query->where($column, 'LIKE', '%' . $value);
                        break;
                }
            }
            
            // Get results ordered by precedence
            $documents = $query->orderBy('precedence', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'documents' => $documents,
                'count' => $documents->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
                'documents' => []
            ], 500);
        }
    }
    public function sort(Request $request)
    {
        $order = $request->input('order');

        DB::transaction(function () use ($order) {
            foreach ($order as $item) {
                Document::where('doc_id', $item['id'])
                    ->update(['precedence' => $item['position']]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $relations = Relation::all();
    
    // Start with the base query
    $query = Document::orderBy('precedence', 'asc');
    
    // Search by Reference ID
    if ($request->filled('ref_id_search') && $request->ref_id_search != '') {
        $query->where('ref_id', $request->ref_id_search);
    }
    
    // Search by Description
    if ($request->filled('description_search') && strlen(trim($request->description_search)) >= 2) {
        $searchTerm = trim($request->description_search);
        $query->where('uploaded_file_desc', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('publication','LIKE','%' . $searchTerm . '%');
    }
    
    $documents = $query->get();
    
    return view('index', compact('documents', 'relations'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $relations = Relation::all();
        return view('create', [
            'relations' => $relations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
{
    $data = $request->validated();
if ($request->hasFile('uploaded_file')) {
    $file = $request->file('uploaded_file'); // Get the uploaded file from the request
    
    $originalName = $file->getClientOriginalName(); // Original filename from user's system
    $extension = $file->getClientOriginalExtension(); // File extension like jpg, pdf, etc.
    
    
    // Create a unique filename by appending the timestamp to the original name (to avoid overwriting files)
    $uniqueFileName = pathinfo($originalName, PATHINFO_FILENAME) .  '.' . $extension;

    $publicPath = public_path('documents'); // Define the folder to save uploaded files

    // Check if folder exists, if not, create it with permissions 0755
    if (!File::exists($publicPath)) {
        File::makeDirectory($publicPath, 0755, true);
    }

    // Move the uploaded file to the 'documents' folder with the unique filename
    $file->move($publicPath, $uniqueFileName);

    // Prepare the data array with file-related info to save into the database
    $data['file_type'] = $extension;
    $data['user_file_name'] = $originalName;
    $data['random_file_name'] = $uniqueFileName;
} else {
    // If no file was uploaded, redirect back with an error message
    return redirect()->back()->withErrors(['uploaded_file' => 'Please upload a file.']);
}

// Set additional fields for the document record before saving
$data['precedence'] = (Document::max('precedence') ?? 0) + 1; 

    // Now you can use create safely
    Document::create($data);

    return redirect()->route('document.index')->with('success', 'Document uploaded successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return view('show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $relations = Relation::all();
        return view('edit', compact('document', 'relations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $data = $request->validated();

        // Handle file upload if a new file is provided
        if ($request->hasFile('uploaded_file')) {
            $file = $request->file('uploaded_file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Create unique filename
    
            $uniqueFileName = pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
            
            // Create documents directory in public folder if it doesn't exist
            $publicPath = public_path('documents');
            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }

            // Delete old file if it exists
            if ($document->random_file_name) {
                $oldFilePath = public_path('documents/' . $document->random_file_name);
                if (File::exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
            }
            
            // Move the new file to public/documents directory
            $file->move($publicPath, $uniqueFileName);

            // Update file-related data
            $data['random_file_name'] = $uniqueFileName;
            $data['user_file_name'] = $originalName;
            $data['file_type'] = $extension;
        }

        // Don't update precedence during regular edit - only through drag/drop
        unset($data['precedence']);
        
        $document->update($data);

        return redirect()->route('document.index')->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Delete the physical file if it exists
        if ($document->random_file_name) {
            $filePath = public_path('documents/' . $document->random_file_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $document->delete();
        
        return redirect()->route('document.index')->with('success', 'Document deleted successfully!');
    }
}