@php
    $product = $product ?? null;
    $brandCollection = collect($brands ?? \App\Models\Brand::orderBy('name')->get());
    $selectedBrandId = old('brand_id', $product?->brand_id ?? ($selectedBrandId ?? null));
@endphp

<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product?->name) }}" required>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product?->slug) }}" placeholder="auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product?->sku) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        @foreach(\App\Models\Product::CATEGORIES as $category)
                            <option value="{{ $category }}" @selected(old('category', $product?->category ?? 'men') === $category)>{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select" required>
                        <option value="">Select brand</option>
                        @foreach($brandCollection as $brand)
                            <option value="{{ $brand->id }}" @selected($selectedBrandId == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Summary</label>
                <textarea name="summary" rows="2" class="form-control">{{ old('summary', $product?->summary) }}</textarea>
            </div>

            <div class="mt-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="6" class="form-control" placeholder="Tell the story behind this piece">{{ old('description', $product?->description) }}</textarea>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Meta & SEO</h2>
            <div class="mb-3">
                <label class="form-label">Meta title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product?->meta_title) }}">
            </div>
            <div class="mb-0">
                <label class="form-label">Meta description</label>
                <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description', $product?->meta_description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="admin-card">
            <h2 class="admin-section-title">Visibility</h2>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $product?->status ?? 'draft') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" name="is_featured" id="is_featured" @checked(old('is_featured', $product?->is_featured))>
                <label class="form-check-label" for="is_featured">Feature on storefront</label>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Pricing & Stock</h2>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product?->price) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Sale price</label>
                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product?->sale_price) }}" placeholder="Optional">
            </div>
            <div class="mb-0">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product?->stock ?? 0) }}" required>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="admin-section-title">Imagery</h2>
            <div class="mb-3">
                <label class="form-label">Cover image</label>
                <input type="text" name="featured_image" class="form-control mb-2" value="{{ old('featured_image', $product?->featured_image) }}" placeholder="assets/img/product-img-1.jpg">
                <input type="file" name="featured_image_file" class="form-control" accept="image/*" onchange="previewFeaturedImage(event)">
                <div class="mt-3">
                    <img id="featured-image-preview" src="{{ $product?->featured_image ? asset($product->featured_image) : '' }}" class="img-fluid rounded {{ $product?->featured_image ? '' : 'd-none' }}" style="max-height: 220px; object-fit: cover;">
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label">Gallery images (one per line)</label>
                <textarea name="gallery_images" rows="3" class="form-control mb-2" placeholder="assets/img/product-img-2.jpg
assets/img/product-img-3.jpg">{{ old('gallery_images', isset($product?->gallery_images) ? implode("\n", $product->gallery_images) : '') }}</textarea>
                <input type="file" name="gallery_images_files[]" class="form-control" accept="image/*" multiple onchange="previewGalleryImages(event)">
                <div id="gallery-images-preview" class="d-flex flex-wrap gap-2 mt-3">
                    @if(!empty($product?->gallery_images))
                        @foreach($product->gallery_images as $image)
                            <img src="{{ asset($image) }}" alt="Gallery Image" class="rounded" style="width: 96px; height: 96px; object-fit: cover;">
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewFeaturedImage(event) {
        const target = document.getElementById('featured-image-preview');
        if (!target) return;

        if (event.target.files && event.target.files[0]) {
            target.src = URL.createObjectURL(event.target.files[0]);
            target.classList.remove('d-none');
        }
    }

    function previewGalleryImages(event) {
        const container = document.getElementById('gallery-images-preview');
        if (!container) return;

        container.innerHTML = '';

        if (event.target.files) {
            Array.from(event.target.files).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'rounded';
                img.style.width = '96px';
                img.style.height = '96px';
                img.style.objectFit = 'cover';
                container.appendChild(img);
            });
        }
    }
</script>
@endpush
