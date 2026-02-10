@extends('layouts.app')

@section('content')
  <main class="kb-admin">
    <div class="container">
      <div class="kb-admin-header">
        <div>
          <div class="kb-page-title">Add Product</div>
          <div class="kb-page-sub">Create a new product and push it into the catalog.</div>
        </div>
        <div class="kb-admin-actions">
          <a class="kb-btn-outline" href="{{ route('admin.products.index') }}">Back to Products</a>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="kb-btn-outline" type="submit">Logout</button>
          </form>
        </div>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="kb-admin-form" method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="kb-form-grid">
          <div>
            <label>Brand</label>
            <select name="brand_key" data-brand-select required>
              @foreach ($brands as $brand)
                <option value="{{ $brand['key'] }}" {{ ($selectedBrand ?? '') === $brand['key'] ? 'selected' : '' }}>
                  {{ $brand['label'] }}
                </option>
              @endforeach
            </select>
            <div class="kb-admin-note">Products, collections, and URLs will be tied to this brand.</div>
          </div>
          <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
          </div>
          <div>
            <label>Handle (Slug)</label>
            <input type="text" name="handle" value="{{ old('handle') }}" placeholder="auto-generated if empty">
          </div>
          <div>
            <label>SKU</label>
            <input type="text" name="sku" value="{{ old('sku') }}">
          </div>
          <div>
            <label>Price ({{ \App\Support\CurrencyFormatter::currency() }})</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}">
          </div>
          <div>
            <label>Compare at Price ({{ \App\Support\CurrencyFormatter::currency() }})</label>
            <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price') }}">
          </div>
          <div>
            <label>Inventory Quantity</label>
            <input type="number" name="inventory_quantity" value="{{ old('inventory_quantity') }}">
          </div>
          <div>
            <label>Discount Type</label>
            <select name="discount_type">
              <option value="">None</option>
              <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Percent (%)</option>
              <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
            </select>
          </div>
          <div>
            <label>Discount Value</label>
            <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}">
          </div>
          <div>
            <label>Discount Start</label>
            <input type="datetime-local" name="discount_starts_at" value="{{ old('discount_starts_at') }}">
          </div>
          <div>
            <label>Discount End</label>
            <input type="datetime-local" name="discount_ends_at" value="{{ old('discount_ends_at') }}">
          </div>
          <div>
            <label>Tags</label>
            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Men, Winter, New">
          </div>
          @php
            $accessoryCategories = $catalogCategories['accessories'] ?? [];
            $selectedAccessoryTags = collect(old('accessory_tags', []));
            $tagList = collect(explode(',', (string) old('tags', '')))
              ->map(fn ($tag) => strtolower(trim($tag)))
              ->filter();
          @endphp
          @if (!empty($accessoryCategories))
            <div class="kb-form-span">
              <label>Accessory Categories</label>
              <div class="kb-chip-grid">
                @foreach ($accessoryCategories as $category)
                  @php
                    $label = $category['label'];
                    $isChecked = $selectedAccessoryTags->contains($label)
                      || $tagList->contains(strtolower($label));
                  @endphp
                  <label class="kb-chip">
                    <input type="checkbox" name="accessory_tags[]" value="{{ $label }}" {{ $isChecked ? 'checked' : '' }}>
                    <span>{{ $label }}</span>
                  </label>
                @endforeach
              </div>
              <div class="kb-admin-note">Select accessories to auto-tag this product under Accessories + category.</div>
            </div>
          @endif
          <div>
            <label>Product Type</label>
            <input type="text" name="product_type" value="{{ old('product_type') }}">
          </div>
          <div>
            <label>Vendor</label>
            <input type="text" name="vendor" value="{{ old('vendor') }}">
          </div>
          <div>
            <label>Primary Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url') }}">
          </div>
          <div>
            <label>Collections</label>
            <select name="collections[]" multiple data-collection-select>
              @foreach ($collections as $collection)
                <option value="{{ $collection->id }}" data-brand="{{ $collection->brand_key }}" {{ collect(old('collections', []))->contains($collection->id) ? 'selected' : '' }}>
                  {{ $collection->title }}
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label>Available</label>
            <select name="available">
              <option value="1" {{ old('available') === '1' ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('available') === '0' ? 'selected' : '' }}>Hidden</option>
            </select>
          </div>
        </div>

        <div class="mt-3">
          <label>Description</label>
          <textarea name="body_html" rows="5">{{ old('body_html') }}</textarea>
        </div>

        <div class="d-flex gap-2 mt-3 flex-wrap">
          <button class="kb-btn-primary" type="submit">Create Product</button>
          <a class="kb-btn-outline" href="{{ route('admin.products.index') }}">Cancel</a>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    (function () {
      var brandSelect = document.querySelector('[data-brand-select]');
      var collectionSelect = document.querySelector('[data-collection-select]');
      if (!brandSelect || !collectionSelect) {
        return;
      }

      var updateCollections = function () {
        var brand = brandSelect.value;
        var options = Array.from(collectionSelect.options);
        var visibleCount = 0;

        options.forEach(function (option) {
          var optionBrand = option.getAttribute('data-brand') || '';
          var isMatch = optionBrand === '' || optionBrand === brand;
          option.hidden = !isMatch;
          if (!isMatch) {
            option.selected = false;
          }
          if (isMatch) {
            visibleCount += 1;
          }
        });

        if (visibleCount === 0) {
          options.forEach(function (option) {
            option.hidden = false;
          });
        }
      };

      brandSelect.addEventListener('change', updateCollections);
      updateCollections();
    })();
  </script>
@endpush
