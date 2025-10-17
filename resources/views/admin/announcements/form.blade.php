@csrf

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $announcement->subtitle) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $announcement->category ?? 'general') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Body</label>
                    <textarea name="body" class="form-control" rows="4">{{ old('body', $announcement->body) }}</textarea>
                    <small class="text-secondary">Keep it concise — the notification panel shows 3–4 lines.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">CTA label</label>
                    <input type="text" name="cta_label" class="form-control" value="{{ old('cta_label', $announcement->cta_label) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">CTA URL</label>
                    <input type="text" name="cta_url" class="form-control" value="{{ old('cta_url', $announcement->cta_url) }}">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Starts at</label>
                        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', optional($announcement->starts_at)->format('Y-m-d\TH:i')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ends at</label>
                        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', optional($announcement->ends_at)->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedSwitch" {{ old('is_featured', $announcement->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isFeaturedSwitch">Featured highlight</label>
                </div>

                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublishedSwitch" {{ old('is_published', $announcement->is_published ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPublishedSwitch">Published</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-link">Cancel</a>
            <button class="btn btn-primary" type="submit">Save alert</button>
        </div>
    </div>
</div>
