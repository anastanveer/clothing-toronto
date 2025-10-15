@extends('admin.layouts.app')

@section('title', 'System Logs | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">System logs</h1>
            <p class="text-secondary mb-0">Latest entries from {{ basename($logPath) }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>

    <div class="admin-card" style="max-height: 70vh; overflow-y: auto;">
        @if($entries->isEmpty())
            <p class="mb-0 text-secondary">No log entries found.</p>
        @else
            <pre class="mb-0" style="white-space: pre-wrap; font-family: 'Fira Code', monospace; font-size: 0.85rem;">{{ $entries->implode("\n") }}</pre>
        @endif
    </div>
@endsection
