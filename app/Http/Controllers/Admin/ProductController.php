<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('brand')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . $request->string('search')->trim()->toString() . '%';
                $query->where('name', 'like', $term)
                    ->orWhere('sku', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $request->query('search'),
        ]);
    }

    public function create(Request $request): View
    {
        $brands = Brand::orderBy('name')->get();
        $selectedBrandId = $request->integer('brand_id');

        if (! $selectedBrandId && $request->filled('brand')) {
            $selectedBrandId = Brand::where('slug', $request->string('brand')->toString())->value('id');
        }

        return view('admin.products.create', [
            'brands' => $brands,
            'selectedBrandId' => $selectedBrandId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $data['featured_image'] = $this->storeFeaturedImage($request, $data['featured_image'] ?? null);
        $data['gallery_images'] = $this->resolveGalleryImages($request, $data['gallery_images'] ?? []);

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load('brand');

        return view('admin.products.edit', [
            'product' => $product,
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);

        $data['featured_image'] = $this->storeFeaturedImage($request, $data['featured_image'] ?? $product->featured_image);
        $data['gallery_images'] = $this->resolveGalleryImages(
            $request,
            $data['gallery_images'] ?? $product->gallery_images ?? []
        );

        $product->update($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product moved to trash.');
    }

    protected function validatedData(Request $request, ?Product $product = null): array
    {
        $id = $product?->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $id],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,' . $id],
            'category' => ['required', 'in:' . implode(',', Product::CATEGORIES)],
            'brand_id' => ['required', 'exists:brands,id'],
            'summary' => ['nullable', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:draft,published,archived'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'max:4096'],
            'gallery_images' => ['nullable', 'string'],
            'gallery_images_files' => ['nullable', 'array'],
            'gallery_images_files.*' => ['image', 'max:4096'],
            'options' => ['nullable', 'array'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');

        $data['featured_image'] = $data['featured_image'] ?? ($product->featured_image ?? null);

        $data['gallery_images'] = $request->filled('gallery_images')
            ? collect(preg_split('/\r?\n/', $request->input('gallery_images')))
                ->map(fn ($path) => trim($path))
                ->filter()
                ->values()
                ->all()
            : ($product?->gallery_images ?? []);

        return $data;
    }

    protected function storeFeaturedImage(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('featured_image_file')) {
            return 'storage/' . $request->file('featured_image_file')->store('products', 'public');
        }

        return $current;
    }

    protected function resolveGalleryImages(Request $request, array $existing): array
    {
        $gallery = $existing;

        if ($request->hasFile('gallery_images_files')) {
            $uploaded = collect($request->file('gallery_images_files'))
                ->map(fn ($file) => 'storage/' . $file->store('products', 'public'))
                ->all();

            $gallery = array_values(array_filter(array_merge($gallery, $uploaded)));
        }

        return $gallery;
    }
}
