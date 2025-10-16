@props([
    'categories' => [],
    'activeCategory' => null,
    'filters' => [],
    'activeFilters' => null,
    'sortOptions' => [],
    'formId' => 'shopFiltersForm',
    'showSearch' => false,
])

@php
    $activeFilters = collect($activeFilters);
    $queryBase = collect(request()->query())->except('page');
    if (empty($filters['price_requested'])) {
        $queryBase = $queryBase->except('price');
    }
    $currentSort = $filters['sort'] ?? 'newest';
    $searchValue = $filters['search'] ?? '';
@endphp

<div class="shop-filters-bar d-flex flex-column gap-3 mb-4">
    <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">
        <div class="d-flex flex-wrap gap-2">
            <a
                href="{{ route('shop', $queryBase->except('category')->toArray()) }}"
                class="btn btn-sm {{ $activeCategory ? 'btn-outline-dark' : 'btn-dark' }}"
            >All</a>
            @foreach($categories as $key => $label)
                @php
                    $params = $queryBase->toArray();
                    unset($params['category']);
                    $params['category'] = $key;
                @endphp
                <a
                    href="{{ route('shop.category', array_merge(['category' => $key], $queryBase->toArray())) }}"
                    class="btn btn-sm {{ $activeCategory === $key ? 'btn-dark' : 'btn-outline-dark' }}"
                >{{ $label }}</a>
            @endforeach
        </div>
        <div class="d-flex align-items-center gap-2 ms-lg-auto filters-actions">
            @if($showSearch)
                <div class="position-relative">
                    <input
                        type="search"
                        name="q"
                        value="{{ $searchValue }}"
                        form="{{ $formId }}"
                        class="form-control form-control-sm ps-4"
                        placeholder="Search products"
                    >
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-2 text-muted">
                        <i class="flaticon-search-interface-symbol"></i>
                    </span>
                </div>
            @endif
            @if(!empty($sortOptions))
                <label class="d-flex align-items-center gap-2 mb-0">
                    <span class="text-muted small">Sort by</span>
                    <select
                        name="sort"
                        class="form-select form-select-sm"
                        form="{{ $formId }}"
                        data-filter-change-submit
                    >
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" @selected($currentSort === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
        </div>
    </div>

    @if($activeFilters->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small">Active filters:</span>
            @foreach($activeFilters as $chip)
                @php
                    $chipKey = $chip['key'] ?? null;
                    $label = $chip['label'] ?? '';
                    $removals = collect(['page']);
                    $overrides = collect();

                    if ($chipKey === 'price') {
                        $overrides->put('price', null);
                    } elseif ($chipKey && $chipKey !== 'category') {
                        $overrides->put($chipKey, null);
                    }

                    if ($chipKey === 'category') {
                        $chipUrl = route('shop', $queryBase->except('category')->toArray());
                    } elseif ($chipKey === 'brand') {
                        $chipUrl = route('shop', $queryBase->except(['brand', 'brand_slug', 'brand_id'])->toArray());
                    } else {
                        $query = $queryBase->except($removals->all());

                        if ($overrides->has('price')) {
                            $query = $query->except('price');
                        }

                        foreach ($overrides as $key => $value) {
                            if ($value === null) {
                                $query = $query->except($key);
                                if ($key === 'brand') {
                                    $query = $query->except(['brand_slug', 'brand_id']);
                                }
                            } else {
                                $query = $query->put($key, $value);
                            }
                        }

                        $chipUrl = request()->url();
                        if ($query->filter(fn ($v) => !is_null($v) && $v !== '')->isNotEmpty()) {
                            $chipUrl .= '?' . http_build_query($query->toArray());
                        }
                    }
                @endphp
                <a href="{{ $chipUrl }}" class="badge text-bg-dark d-flex align-items-center gap-2 px-3 py-2">
                    <span>{{ $label }}</span>
                    <span aria-hidden="true">&times;</span>
                </a>
            @endforeach
            <a href="{{ request()->routeIs('shop.brand') ? route('shop') : request()->url() }}" class="badge bg-transparent text-decoration-underline text-muted">Clear all</a>
        </div>
    @endif
</div>
