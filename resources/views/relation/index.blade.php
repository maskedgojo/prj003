@extends('layout')

@section('title')
    All Relations
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-nowrap align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="50"><i class="bi bi-arrows-move"></i> Drag</th>
                    <th>title</th>
                    <th>page_url</th>
                    <th>description</th>
                    <th>is_file_uploaded</th>
                    <th>fileupload_count</th>
                    <th>is_disabled</th>
                    <th class="text-center" width="70">View</th>
                    <th class="text-center" width="70">Edit</th>
                    <th class="text-center" width="80">Delete</th>
                </tr>
            </thead>
            <tbody id="sortable">
                @forelse ($relations as $index => $relation)
                    <tr class="sortable-row" data-id="{{ $relation->relations_dtl_id }}" data-index="{{ $index }}">
                        <td class="drag-handle text-center" style="background-color: #f0f0f0; cursor: grab;">
                            <i class="bi bi-grip-vertical text-primary" style="font-size: 18px;"></i>
                            <br><small>{{ $index + 1 }}</small>
                        </td>
                        <td>{{ $relation->title }}</td>
                        <td class="py-0" style="vertical-align: middle;">
                            @if($relation->page_url)
                                <a href="{{ $relation->page_url }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-1"
                                    style="font-size: 0.7rem;">
                                    <i class="bi bi-link-45deg"></i> Link
                                </a>
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">No URL</span>
                            @endif
                        </td>
                        <td>{{ $relation->description }}</td>
                        <td>{{ $relation->is_file_uploaded === 'Y' ? 'Yes' : 'No' }}</td>
                        <td>{{ $relation->documents->count() }}</td>
                        <td class="py-0" style="vertical-align: middle;">
                        <span class="badge {{ $relation->is_disabled  ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.65rem;">
                            {{ $relation->is_disabled ? 'Disabled' : 'Active' }}
                        </span>
                    </td>
                        <td class="text-center py-0" style="vertical-align: middle;">
                            <a href="{{ route('relation.show', $relation->relations_dtl_id) }}"
                                class="btn btn-sm btn-outline-primary py-1 px-2" title="View" style="font-size: 0.75rem;">
                                <i class="bi bi-eye me-1"></i>SHOW
                            </a>
                        </td>
                        <td class="text-center py-0" style="vertical-align: middle;">
                            <a href="{{ route('relation.edit', $relation->relations_dtl_id) }}"
                                class="btn btn-sm btn-outline-warning py-1 px-2" title="Edit" style="font-size: 0.75rem;">
                                <i class="bi bi-pencil me-1"></i>EDIT
                            </a>
                        </td>
                        <td class="text-center py-0" style="vertical-align: middle;">
                            <form action="{{ route('relation.destroy', $relation->relations_dtl_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete"
                                    style="font-size: 0.75rem;"
                                    onclick="return confirm('Are you sure you want to delete this document? This will also update the precedence of other documents.')">
                                    <i class="bi bi-trash me-1"></i>DELETE
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <br>
                            No relations found. <a href="{{ route('relation.create') }}">Create one first</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Status Messages -->
    <div id="status-messages" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

<style>
    .table th,
    .table td {
        padding-top: 0.10rem !important;
        padding-bottom: 0.10rem !important;
        font-size: 0.78rem;
        line-height: 1.05;
        vertical-align: middle;
        padding-left: 0.4rem !important;
        padding-right: 0.4rem !important;
    }

    .table .btn {
        font-size: 0.66rem;
        padding: 0.08rem 0.22rem;
        line-height: 1.1;
    }

    .sortable-row {
        transition: all 0.3s ease;
    }

    .sortable-row:hover {
        background-color: #f8f9fa !important;
    }

    .sortable-row.sortable-chosen {
        background-color: #e3f2fd !important;
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border: 2px solid #007bff;
    }

    .sortable-row.sortable-ghost {
        opacity: 0.5;
        background-color: #fff3cd !important;
    }

    .drag-handle {
        cursor: grab !important;
        user-select: none;
        width: 50px;
        min-width: 50px;
    }

    .drag-handle:active {
        cursor: grabbing !important;
    }

    .drag-handle i {
        font-size: 18px;
        pointer-events: none;
    }

    .sortable-fallback {
        display: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const sortableElement = document.getElementById('sortable');
    if (!sortableElement || sortableElement.children.length === 0) {
        return;
    }
    if (typeof Sortable === 'undefined') {
        return;
    }
    try {
        const sortable = new Sortable(sortableElement, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            forceFallback: false,
            onStart: function (evt) {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function (evt) {
                document.body.style.cursor = '';
                if (evt.oldIndex === evt.newIndex) {
                    return;
                }
                
                // Get the new order
                const order = [];
                sortableElement.querySelectorAll('.sortable-row').forEach((row, index) => {
                    const id = parseInt(row.getAttribute('data-id'));
                    order.push({
                        id: id,
                        position: index + 1
                    });
                });
                
                // Send AJAX request
                fetch("{{ route('relation.sort') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update both drag column numbers AND relations_master_id
                        sortableElement.querySelectorAll('.sortable-row').forEach((row, index) => {
                            // Update drag column number (the small number in drag handle)
                            const dragNumber = row.querySelector('.drag-handle small');
                            if (dragNumber) {
                                dragNumber.textContent = index + 1;
                            }
                            
                            // Update relations_master_id column
                            const masterIdCell = row.querySelector('.relations-master-id');
                            if (masterIdCell) {
                                masterIdCell.textContent = index + 1;
                            }
                            
                            // Also update the data-index attribute
                            row.setAttribute('data-index', index);
                        });
                        
                        // Show success message
                        showStatusMessage('Order updated successfully!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating sort order:', error);
                    showStatusMessage('Error updating order. Please refresh the page.', 'error');
                });
            }
        });
    } catch (error) {
        console.error('Error initializing sortable:', error);
    }
});

// Optional: Function to show status messages
function showStatusMessage(message, type) {
    const statusContainer = document.getElementById('status-messages');
    if (!statusContainer) return;
    
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    statusContainer.appendChild(messageDiv);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 3000);
}
</script>