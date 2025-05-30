<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Http\Requests\StoreRelationRequest;
use App\Http\Requests\UpdateRelationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RelationController extends Controller
{

    public function sort(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $item) {
            $id = $item['id'];
            Relation::where('relations_dtl_id', $id)
                ->update(['relations_master_id' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Order by relations_master_id instead of order_column
        $relations = Relation::orderBy('relations_master_id')->get();
        return view('relation.index', ['relations' => $relations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('relation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRelationRequest $request)
    {
        $data = $request->validated();
        
        // Set the relations_master_id to the next available position
        $maxMasterId = Relation::max('relations_master_id') ?? 0;
        $data['relations_master_id'] = $maxMasterId + 1;
        
        Relation::create($data);
        
        return redirect()->route('relation.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Relation $relation)
    {
        $relation->load('documents');
        $relation->fileupload_count = $relation->documents()->count();

        return view('relation.show', compact('relation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Relation $relation)
    {
        return view('relation.edit',compact('relation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        $data = $request->validated();
        $relation->update($data);
        return redirect()->route('relation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Relation $relation)
    {
        $deletedMasterId = $relation->relations_master_id;
        $relation->delete();
        
        // Update the relations_master_id of remaining records
        Relation::where('relations_master_id', '>', $deletedMasterId)
            ->decrement('relations_master_id');
        
        return redirect()->route('relation.index');
    }
}