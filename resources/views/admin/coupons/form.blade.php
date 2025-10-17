<div class="row g-4">
    <div class="col-md-6">
        <label for="coupon-title" class="form-label fw-semibold">Display title<span class="text-danger">*</span></label>
        <input
            type="text"
            id="coupon-title"
            name="title"
            class="form-control"
            placeholder="Radiant Insider Boost"
            value="{{ old('title', $coupon->title) }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="coupon-code" class="form-label fw-semibold">Coupon code<span class="text-danger">*</span></label>
        <input
            type="text"
            id="coupon-code"
            name="code"
            class="form-control text-uppercase"
            placeholder="RADIANT25"
            value="{{ old('code', $coupon->code) }}"
            required
        >
        <div class="form-text">Letters, numbers, dashes and underscores only.</div>
    </div>

    <div class="col-12">
        <label for="coupon-description" class="form-label fw-semibold">Description</label>
        <textarea
            id="coupon-description"
            name="description"
            class="form-control"
            rows="3"
            placeholder="Limited run reward for the Glamer Collective."
        >{{ old('description', $coupon->description) }}</textarea>
    </div>

    <div class="col-md-4">
        <label for="coupon-type" class="form-label fw-semibold">Discount type<span class="text-danger">*</span></label>
        <select id="coupon-type" name="type" class="form-select" required>
            @foreach(['fixed' => 'Fixed amount', 'percent' => 'Percent off'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $coupon->type ?? 'fixed') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="coupon-value" class="form-label fw-semibold">
            Discount value<span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text" data-type-prefix>{{ old('type', $coupon->type ?? 'fixed') === 'percent' ? '%' : '$' }}</span>
            <input
                type="number"
                step="0.01"
                min="0.01"
                id="coupon-value"
                name="value"
                class="form-control"
                value="{{ old('value', $coupon->value) }}"
                required
            >
        </div>
        <div class="form-text">For percent, enter the percentage (e.g. 10 for 10%).</div>
    </div>

    <div class="col-md-4">
        <label for="coupon-max-discount" class="form-label fw-semibold">
            Max discount (optional)
        </label>
        <input
            type="number"
            step="0.01"
            min="0"
            id="coupon-max-discount"
            name="max_discount"
            class="form-control"
            value="{{ old('max_discount', $coupon->max_discount) }}"
        >
        <div class="form-text">Caps percent coupons. Leave blank for no cap.</div>
    </div>

    <div class="col-md-4">
        <label for="coupon-min-spend" class="form-label fw-semibold">
            Minimum spend
        </label>
        <input
            type="number"
            step="0.01"
            min="0"
            id="coupon-min-spend"
            name="min_spend"
            class="form-control"
            value="{{ old('min_spend', $coupon->min_spend) }}"
        >
        <div class="form-text">Set to zero to allow any order value.</div>
    </div>

    <div class="col-md-4">
        <label for="coupon-starts-at" class="form-label fw-semibold">Starts at</label>
        <input
            type="datetime-local"
            id="coupon-starts-at"
            name="starts_at"
            class="form-control"
            value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}"
        >
    </div>

    <div class="col-md-4">
        <label for="coupon-expires-at" class="form-label fw-semibold">Expires at</label>
        <input
            type="datetime-local"
            id="coupon-expires-at"
            name="expires_at"
            class="form-control"
            value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}"
        >
    </div>

    <div class="col-12">
        <div class="form-check form-switch pt-3">
            <input class="form-check-input" type="checkbox" role="switch" id="coupon-active" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))>
            <label class="form-check-label fw-semibold" for="coupon-active">Coupon is active</label>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const typeSelect = document.getElementById('coupon-type');
            const prefix = document.querySelector('[data-type-prefix]');

            if (!typeSelect || !prefix) {
                return;
            }

            typeSelect.addEventListener('change', () => {
                prefix.textContent = typeSelect.value === 'percent' ? '%' : '$';
            });
        })();
    </script>
@endpush
