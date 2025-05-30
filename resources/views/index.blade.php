@extends('layout')

@section('title')
    All Document Data
@endsection

@section('content')



<!-- Advanced Search Container -->
<div class="search-container">
    <div class="search-header">
        <h3 class="search-title">
            <i class="bi bi-search me-2"></i>
            Advanced Document Search
        </h3>
        <div class="filter-count">
            <span id="filterCount">0</span> active filter(s)
        </div>
    </div>
    
    <!-- Filters Container -->
    <div id="filtersContainer">
        <div class="no-filters" id="noFiltersMessage">
            <i class="bi bi-filter-circle me-2" style="font-size: 2rem;"></i>
            <br>
            Click "Add Filter" to start searching
        </div>
    </div>
    
    <!-- Add Filter Button -->
    <div class="text-center mb-3">
        <button type="button" class="btn btn-add-filter" id="addFilterBtn">
            <i class="bi bi-plus-circle me-2"></i>Add Filter
        </button>
    </div>
    <!-- Search Actions -->
    <div class="search-actions">
        <button type="button" class="btn btn-search" id="searchBtn">
            <i class="bi bi-search me-2"></i>Search Documents
        </button>
        <button type="button" class="btn btn-clear" id="clearBtn">
            <i class="bi bi-arrow-clockwise me-2"></i>Clear All
        </button>
    </div>
</div>


<!-- Professional Inline Search Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="search-container">
            <form action="{{ route('document.index') }}" method="GET" class="search-form">
                
                <!-- Reference ID Dropdown -->
                <div class="search-field">
                    <label class="field-label">Reference ID</label>
                    <select name="ref_id_search" id="ref_id_search" class="form-control">
                        <option value="">All</option>
                        @if(isset($relations))
                            @foreach($relations as $relation)
                                <option value="{{ $relation->relations_dtl_id }}" 
                                    {{ request('ref_id_search') == $relation->relations_dtl_id ? 'selected' : '' }}>
                                    {{ $relation->relations_dtl_id }} - {{ $relation->title ?? 'No Title' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Description Search -->
                <div class="search-field">
                    <label class="field-label">Description</label>
                    <input type="text" 
                           name="description_search" 
                           id="description_search" 
                           class="form-control" 
                           placeholder="Enter keywords..."
                           value="{{ request('description_search') }}"
                           minlength="2">
                </div>

                <!-- Search Button -->
                <div class="search-button">
                    <button type="submit" class="btn btn-search">
                        Search
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Search Results Summary -->
@if(request('ref_id_search') || request('description_search'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="results-summary">
                <span class="results-text">
                    Active Filters:
                    @if(request('ref_id_search'))
                        <span class="filter-tag">Reference ID: {{ request('ref_id_search') }}</span>
                    @endif
                    @if(request('description_search'))
                        <span class="filter-tag">Description: "{{ request('description_search') }}"</span>
                    @endif
                    • Found <strong>{{ $documents->count() }}</strong> result(s)
                </span>
                <a href="{{ route('document.index') }}" class="clear-btn">
                    Clear All
                </a>
            </div>
        </div>
    </div>
@endif





{{-- <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('document.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add New Document
    </a>
</div> --}}

<div class="table-responsive" id="documentsTable">
    <table class="table table-striped table-bordered table-hover text-nowrap align-middle table-sm">
        <thead class="table-dark">
            <tr>
                <th width="60"><i class="bi bi-arrows-move"></i> Drag</th>
                <th>file_type</th>
                <th>table_name</th>
                <th>ref_id</th>
                <th>uploaded_file_desc</th>
                <th>File</th>
                <th>url</th>
                <th>publication</th>
                <th>is_disabled</th>
                <th class="text-center" width="70">View</th>
                <th class="text-center" width="70">Edit</th>
                <th class="text-center" width="80">Delete</th>
            </tr>
        </thead>
        <tbody id="sortable">
            @forelse ($documents as $index => $document)
                <tr class="sortable-row" data-id="{{ $document->doc_id }}" data-precedence="{{ $document->precedence }}" style="height: 32px;">
                    <td class="drag-handle text-center py-0" style="background-color: #f0f0f0; cursor: grab; vertical-align: middle;">
                        <i class="bi bi-grip-vertical text-primary" style="font-size: 14px;"></i>
                        <br><small class="position-number" style="line-height: 1; font-size: 0.7rem;">{{ $index + 1 }}</small>
                    </td>
                    <td class="py-0" style="vertical-align: middle; font-size: 0.85rem;">{{ $document->file_type }}</td>
                    <td class="py-0" style="vertical-align: middle; font-size: 0.85rem;">{{ $document->table_name }}</td>
                    <td class="py-0" style="vertical-align: middle; font-size: 0.85rem;">{{ $document->relation ? $document->relation->title : 'N/A' }}</td>
                    <td class="py-0" style="vertical-align: middle; font-size: 0.85rem;">{{ $document->uploaded_file_desc }}</td>
                    <td class="py-0" style="vertical-align: middle;">
                        @if($document->random_file_name || $document->user_file_name)
                            @php
                                $fileName = $document->random_file_name ?: $document->user_file_name;
                                $displayName = $document->user_file_name ?: $fileName;
                                $fileUrl = asset('documents/' . $fileName);
                            @endphp
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-xs btn-outline-success py-0 px-1" title="Open {{ $displayName }}" style="font-size: 0.7rem;">
                                <i class="bi bi-file-earmark-text"></i> {{ Str::limit($displayName, 12) }}
                            </a>
                        @else
                            <span class="text-muted" style="font-size: 0.75rem;">No File</span>
                        @endif
                    </td>
                    <td class="py-0" style="vertical-align: middle;">
                        @if($document->url)
                            <a href="{{ $document->url }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 0.7rem;">
                                <i class="bi bi-link-45deg"></i> Link
                            </a>
                        @else
                            <span class="text-muted" style="font-size: 0.75rem;">No URL</span>
                        @endif
                    </td>
                    <td class="py-0" style="vertical-align: middle; font-size: 0.85rem;">{{ $document->publication }}</td>
                    <td class="py-0" style="vertical-align: middle;">
                        <span class="badge {{ $document->is_disabled ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.65rem;">
                            {{ $document->is_disabled ? 'Disabled' : 'Active' }}
                        </span>
                    </td>
                    <td class="text-center py-0" style="vertical-align: middle;">
                        <a href="{{ route('document.show', $document->doc_id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="View" style="font-size: 0.75rem;">
                            <i class="bi bi-eye me-1"></i>SHOW
                        </a>
                    </td>
                    <td class="text-center py-0" style="vertical-align: middle;">
                        <a href="{{ route('document.edit', $document->doc_id) }}" class="btn btn-sm btn-outline-warning py-1 px-2" title="Edit" style="font-size: 0.75rem;">
                            <i class="bi bi-pencil me-1"></i>EDIT
                        </a>
                    </td>
                    <td class="text-center py-0" style="vertical-align: middle;">
                        <form action="{{ route('document.destroy', $document->doc_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete" style="font-size: 0.75rem;" onclick="return confirm('Are you sure you want to delete this document? This will also update the precedence of other documents.')">
                                <i class="bi bi-trash3"></i>DELETE
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <br>No documents found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('relation.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-list-ul"></i> Relations details
    </a>
</div> --}}
@endsection


<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Advanced Search Class
class AdvancedDocumentSearch {

    constructor() {
        this.filterCounter = 0;
        this.columnOptions = [
            { value: 'file_type', label: 'File Type' },
            { value: 'table_name', label: 'Table Name' },
            { value: 'ref_id', label: 'Reference ID' },
            { value: 'uploaded_file_desc', label: 'Description' },
            { value: 'user_file_name', label: 'File Name' },
            { value: 'publication', label: 'Publication' },
            { value: 'is_disabled', label: 'Status' }
        ];
        
        this.relationOptions = [
            { value: 'equals', label: 'Equals (=)' },
            { value: 'not_equal', label: 'Not Equal (≠)' },
            { value: 'contains', label: 'Contains' },
            { value: 'starts_with', label: 'Starts With' },
            { value: 'ends_with', label: 'Ends With' }
        ];
        
        this.originalTableContent = '';
        this.init();
    }
    
    init() {
        const filterId = `filter-${this.filterCounter}`;
        // Store original table content
        this.originalTableContent = document.getElementById('documentsTable').innerHTML;
        
        // Bind events
        document.getElementById('addFilterBtn').addEventListener('click', () => this.addFilter());
        document.getElementById('searchBtn').addEventListener('click', () => this.performSearch());
        document.getElementById('clearBtn').addEventListener('click', () => this.clearAllFilters());
    }
    
    addFilter() {
        this.filterCounter++;
        const filterId = `filter-${this.filterCounter}`;
        
        const filterHtml = `
            <div class="filter-row" id="${filterId}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Column</label>
                        <select class="form-select" name="column">
                            <option value="">Select Column</option>
                            ${this.columnOptions.map(opt => 
                                `<option value="${opt.value}">${opt.label}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Condition</label>
                        <select class="form-select" name="relation">
                            <option value="">Select Condition</option>
                            ${this.relationOptions.map(opt => 
                                `<option value="${opt.value}">${opt.label}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Value</label>
                        <input type="text" class="form-control" name="value" placeholder="Enter search value">
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-danger remove-filter-btn" data-filter-id="${filterId}">
                                <i class="bi bi-x-circle"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('filtersContainer').insertAdjacentHTML('beforeend', filterHtml);
        this.updateFilterCount();
        this.toggleNoFiltersMessage();
    }
    
    removeFilter(filterId) {
        const filterElement = document.getElementById(filterId);
                    document.getElementById('filtersContainer').innerHTML = `
            <div class="no-filters" id="noFiltersMessage">
                <i class="bi bi-filter-circle me-2" style="font-size: 2rem;"></i>
                <br>
                Click "Add Filter" to start searching
            </div>
        `;
        if (filterElement) {
            filterElement.remove();

            this.updateFilterCount();
             document.getElementById('documentsTable').innerHTML = this.originalTableContent;
            this.toggleNoFiltersMessage();
        }
    }
    
    clearAllFilters() {
        document.getElementById('filtersContainer').innerHTML = `
            <div class="no-filters" id="noFiltersMessage">
                <i class="bi bi-filter-circle me-2" style="font-size: 2rem;"></i>
                <br>
                Click "Add Filter" to start searching
            </div>
        `;
        this.updateFilterCount();
        
        // Restore original table content
        document.getElementById('documentsTable').innerHTML = this.originalTableContent;
        
        // Reinitialize sortable
        this.initializeSortable();
    }
    
    updateFilterCount() {
        const count = document.querySelectorAll('.filter-row').length;
        document.getElementById('filterCount').textContent = count;
    }
    
    toggleNoFiltersMessage() {
        const hasFilters = document.querySelectorAll('.filter-row').length > 0;
        const noFiltersMsg = document.getElementById('noFiltersMessage');
        if (noFiltersMsg) {
            noFiltersMsg.style.display = hasFilters ? 'none' : 'block';
        }
    }
    
    getFilters() {
        const filters = [];
        document.querySelectorAll('.filter-row').forEach(row => {
            const column = row.querySelector('select[name="column"]').value;
            const relation = row.querySelector('select[name="relation"]').value;
            const value = row.querySelector('input[name="value"]').value;
            
            if (column && relation && value) {
                filters.push({ column, relation, value });
            }
        });
        return filters;
    }
    
    async performSearch() {
        const filters = this.getFilters();
        if (filters.length === 0) {
            alert('Please add at least one complete filter to search.');
            return;
        }
        
        const searchBtn = document.getElementById('searchBtn');
        const originalText = searchBtn.innerHTML;
        searchBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Searching...';
        searchBtn.disabled = true;
        
        try {
            const response = await fetch('{{ route("document.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ filters })
            });
            
            if (!response.ok) {
                throw new Error('Search request failed');
            }
            
            const data = await response.json();
            this.renderSearchResults(data.documents || []);
            
        } catch (error) {
            console.error('Search error:', error);
            alert('An error occurred while searching. Please try again.');
        } finally {
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        }
    }
    
    renderSearchResults(documents) {
        let tableHtml = `
            <table class="table table-striped table-bordered table-hover text-nowrap align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>file_type</th>
                        <th>table_name</th>
                        <th>ref_id</th>
                        <th>uploaded_file_desc</th>
                        <th>File</th>
                        <th>url</th>
                        <th>publication</th>
                        <th>is_disabled</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        if (documents.length > 0) {
            documents.forEach(document => {
                const fileName = document.random_file_name || document.user_file_name;
                const displayName = document.user_file_name || fileName;
                const fileUrl = fileName ? `/documents/${fileName}` : '';
                
                tableHtml += `
                    <tr>
                        <td>${document.file_type || ''}</td>
                        <td>${document.table_name || ''}</td>
                        <td>${document.ref_id || 'N/A'}</td>
                        <td>${document.uploaded_file_desc || ''}</td>
                        <td>
                            ${fileName ? `
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-success" title="Open ${displayName}">
                                    <i class="bi bi-file-earmark-text"></i> ${displayName.length > 20 ? displayName.substring(0, 20) + '...' : displayName}
                                </a>
                            ` : '<span class="text-muted">No File</span>'}
                        </td>
                        <td>
                            ${document.url ? `
                                <a href="${document.url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-link-45deg"></i> Link
                                </a>
                            ` : '<span class="text-muted">No URL</span>'}
                        </td>
                        <td>${document.publication || ''}</td>
                        <td>
                            <span class="badge ${document.is_disabled ? 'bg-danger' : 'bg-success'}">
                                ${document.is_disabled ? 'Disabled' : 'Active'}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group-vertical" role="group">
                                <a href="/document/${document.doc_id}" class="btn btn-sm btn-info mb-1">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="/document/${document.doc_id}/edit" class="btn btn-sm btn-warning mb-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="/document/${document.doc_id}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this document?')">
                                        <i class="bi bi-trash3"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tableHtml += `
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size: 2rem;"></i>
                        <br>No documents found matching your search criteria.
                    </td>
                </tr>
            `;
        }
        
        tableHtml += `
                </tbody>
            </table>
        `;
        
        document.getElementById('documentsTable').innerHTML = tableHtml;
    }
    
    initializeSortable() {
        // Re-initialize sortable functionality after table update
        const sortableElement = document.getElementById('sortable');
        if (sortableElement && sortableElement.children.length > 0 && typeof Sortable !== 'undefined') {
            new Sortable(sortableElement, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                forceFallback: false,
                onStart: function(evt) {
                    document.body.style.cursor = 'grabbing';
                },
                onEnd: function(evt) {
                    document.body.style.cursor = '';
                    if (evt.oldIndex === evt.newIndex) {
                        return;
                    }
                    
                    const order = [];
                    sortableElement.querySelectorAll('.sortable-row').forEach((row, index) => {
                        const id = parseInt(row.getAttribute('data-id'));
                        const newPrecedence = index + 1;
                        order.push({
                            id: id,
                            position: newPrecedence
                        });
                        row.querySelector('.position-number').textContent = newPrecedence;
                    });
                    
                    fetch("{{ route('document.sort') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update order on the server.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize advanced search
    window.advancedSearch = new AdvancedDocumentSearch();
    
    // Initialize original sortable functionality
    advancedSearch.initializeSortable();
    document.getElementById('filtersContainer').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-filter-btn');
        if (btn) {
            const filterId = btn.dataset.filterId;
            advancedSearch.removeFilter(filterId);
        }
    });
});

</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const refIdSelect = document.getElementById('ref_id_search');
    const descriptionInput = document.getElementById('description_search');
    
    // Enhanced interaction - visual feedback when fields are used together
    refIdSelect.addEventListener('change', function() {
        // Remove the clearing behavior - allow both fields to be used
        if (this.value && this.value !== '' && descriptionInput.value.length >= 2) {
            // Both fields are active - show visual feedback
            this.style.borderLeft = '3px solid #28a745';
            descriptionInput.style.borderLeft = '3px solid #28a745';
        } else {
            this.style.borderLeft = '';
            descriptionInput.style.borderLeft = '';
        }
    });
    
    descriptionInput.addEventListener('input', function() {
        // Remove the clearing behavior - allow both fields to be used
        if (this.value.length >= 2 && refIdSelect.value && refIdSelect.value !== '') {
            // Both fields are active - show visual feedback
            this.style.borderLeft = '3px solid #28a745';
            refIdSelect.style.borderLeft = '3px solid #28a745';
        } else {
            this.style.borderLeft = '';
            refIdSelect.style.borderLeft = '';
        }
    });

    // Auto-submit on select change (optional)
    // refIdSelect.addEventListener('change', function() {
    //     if (this.value) {
    //         this.form.submit();
    //     }
    // });
});
</script>
