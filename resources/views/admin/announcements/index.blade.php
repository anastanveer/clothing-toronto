@extends('admin.layouts.app')

@section('title', 'Alerts | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Notification alerts</h1>
            <p class="text-secondary mb-0">Curate upcoming drops, articles, and perks for the Glamer alerts panel.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="flaticon-plus me-1"></i> New alert
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Window</th>
                        <th scope="col">Featured</th>
                        <th scope="col">Published</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $announcement->title }}</div>
                                @if($announcement->subtitle)
                                    <small class="text-secondary">{{ $announcement->subtitle }}</small>
                                @endif
                            </td>
                            <td class="text-uppercase small">{{ $announcement->category }}</td>
                            <td>
                                <small class="text-secondary d-block">
                                    {{ $announcement->starts_at ? 'Starts ' . $announcement->starts_at->format('M j, Y g:i A') : 'Live now' }}
                                </small>
                                <small class="text-secondary">
                                    {{ $announcement->ends_at ? 'Ends ' . $announcement->ends_at->format('M j, Y g:i A') : 'Open' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge {{ $announcement->is_featured ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $announcement->is_featured ? 'Featured' : 'Standard' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $announcement->is_published ? 'bg-success-subtle text-success' : 'bg-secondary' }}">
                                    {{ $announcement->is_published ? 'Published' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="ms-1" onsubmit="return confirm('Remove this alert?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-secondary">
                                No alerts yet. Add one to populate the notification center.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
            <div class="card-footer">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
@endsection
