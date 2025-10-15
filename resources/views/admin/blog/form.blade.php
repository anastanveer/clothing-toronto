@php
    $post = $post ?? null;
@endphp

<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $post?->title) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $post?->slug) }}" placeholder="auto-generated if empty">
            </div>
            <div class="mb-3">
                <label class="form-label">Excerpt</label>
                <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt', $post?->excerpt) }}</textarea>
            </div>
            <div class="mb-0">
                <label class="form-label">Body</label>
                <textarea name="content" rows="10" class="form-control" placeholder="Compose your story..." required>{{ old('content', $post?->content) }}</textarea>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Meta & discoverability</h2>
            <div class="mb-3">
                <label class="form-label">Meta title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post?->meta_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Meta description</label>
                <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description', $post?->meta_description) }}</textarea>
            </div>
            <div class="mb-0">
                <label class="form-label">Tags (comma separated)</label>
                <input type="text" name="tags" class="form-control" value="{{ old('tags', isset($post?->tags) ? implode(', ', $post->tags) : '') }}">
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="admin-card">
            <h2 class="admin-section-title">Publishing</h2>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $post?->status ?? 'draft') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-0">
                <label class="form-label">Publish at</label>
                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($post?->published_at)->format('Y-m-d\TH:i')) }}">
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Hero imagery</h2>
            <div class="mb-0">
                <label class="form-label">Featured image path</label>
                <input type="text" name="featured_image" class="form-control" value="{{ old('featured_image', $post?->featured_image) }}" placeholder="assets/img/blog-1.jpg">
            </div>
        </div>
    </div>
</div>
