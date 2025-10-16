<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%' . $request->string('search')->trim()->toString() . '%';
                $query->where(function ($builder) use ($term): void {
                    $builder->where('name', 'like', $term)
                        ->orWhere('tagline', 'like', $term)
                        ->orWhere('summary', 'like', $term);
                });
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $status = $request->string('status')->toString();

                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    $query->where('is_published', false);
                }
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.brands.index', [
            'brands' => $brands,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.brands.create', [
            'brand' => new Brand(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBrand($request);
        $data['hero_image'] = $this->storeHeroImage($request);

        $brand = Brand::create($data);

        return redirect()
            ->route('admin.brands.edit', $brand)
            ->with('status', 'Brand created successfully.');
    }

    public function show(Brand $brand): View
    {
        $productStats = $brand->products()
            ->select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $products = $brand->products()
            ->with('brand')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.brands.show', [
            'brand' => $brand,
            'products' => $products,
            'stats' => [
                'total' => $productStats->sum(),
                'published' => $productStats->get('published', 0),
                'draft' => $productStats->get('draft', 0),
            ],
        ]);
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $this->validateBrand($request, $brand);
        $data['hero_image'] = $this->storeHeroImage($request, $brand->hero_image);

        $brand->update($data);

        return redirect()
            ->route('admin.brands.edit', $brand)
            ->with('status', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return back()->withErrors([
                'brand' => 'This brand still has products assigned. Please reassign or delete them first.',
            ]);
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('status', 'Brand deleted successfully.');
    }

    public function toggleStatus(Request $request, Brand $brand): RedirectResponse
    {
        $brand->update([
            'is_published' => ! $brand->is_published,
        ]);

        return back()->with('status', 'Brand visibility updated.');
    }

    protected function validateBrand(Request $request, ?Brand $brand = null): array
    {
        $id = $brand?->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,' . $id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug,' . $id],
            'tagline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'hero_image_file' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }

    protected function storeHeroImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('hero_image_file')) {
            return 'storage/' . $request->file('hero_image_file')->store('brands', 'public');
        }

        return $current ?? $request->input('hero_image');
    }
}
