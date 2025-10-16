@php
    $brand = $brand ?? new \App\Models\Brand();
@endphp

<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}" placeholder="auto-generated if empty">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $brand->tagline) }}" placeholder="Optional brand promise">
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Summary</label>
                <textarea name="summary" rows="3" class="form-control" placeholder="Short intro that appears in listings">{{ old('summary', $brand->summary) }}</textarea>
            </div>

            <div class="mt-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="6" class="form-control" placeholder="Share the brand story, materials, ethos">{{ old('description', $brand->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="admin-card">
            <h2 class="admin-section-title">Visibility</h2>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_published" id="is_published" @checked(old('is_published', $brand->is_published ?? true))>
                <label class="form-check-label" for="is_published">Published on storefront</label>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Hero imagery</h2>
            <div class="mb-3">
                <label class="form-label">Hero image URL</label>
                <input type="text" name="hero_image" class="form-control" value="{{ old('hero_image', $brand->hero_image) }}" placeholder="Optional static asset path">
            </div>
            <div class="mb-0">
                <label class="form-label">Upload hero image</label>
                <input type="file" name="hero_image_file" class="form-control" accept="image/*" onchange="previewBrandHero(event)">
                <div class="mt-3">
                    <img id="brand-hero-preview" src="{{ $brand->hero_image ? asset($brand->hero_image) : '' }}" class="img-fluid rounded {{ $brand->hero_image ? '' : 'd-none' }}" style="max-height: 220px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewBrandHero(event) {
        const target = document.getElementById('brand-hero-preview');
        if (!target) return;

        if (event.target.files && event.target.files[0]) {
            target.src = URL.createObjectURL(event.target.files[0]);
            target.classList.remove('d-none');
        }
    }
</script>
@endpush
