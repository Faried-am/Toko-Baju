<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'sizes'])->latest()->get();

        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create', ['categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array|max:5',
            'gallery.*.file' => 'nullable|image|max:2048',
            'gallery.*.color' => 'nullable|string|max:50',
            'sizes' => 'required|array|min:1',
            'sizes.*.color' => 'nullable|string|max:50',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => collect($request->sizes)->sum('stock'),
            'image' => $imagePath,
        ]);

        foreach ($request->sizes as $sizeData) {
            $product->sizes()->create([
                'color' => $sizeData['color'] ?? null,
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'],
            ]);
        }

        $this->saveGallery($request, $product);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $product->load(['sizes', 'images']);

        return view('products.edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array|max:5',
            'gallery.*.file' => 'nullable|image|max:2048',
            'gallery.*.color' => 'nullable|string|max:50',
            'existing_images' => 'nullable|array',
            'existing_images.*.color' => 'nullable|string|max:50',
            'sizes' => 'required|array|min:1',
            'sizes.*.id' => 'nullable|exists:product_sizes,id',
            'sizes.*.color' => 'nullable|string|max:50',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => collect($request->sizes)->sum('stock'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Sinkronkan varian warna/size
        $keptIds = [];

        foreach ($request->sizes as $sizeData) {
            $values = [
                'color' => $sizeData['color'] ?? null,
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'],
            ];

            if (!empty($sizeData['id'])) {
                $productSize = $product->sizes()->find($sizeData['id']);
                if ($productSize) {
                    $productSize->update($values);
                    $keptIds[] = $productSize->id;
                }
            } else {
                $keptIds[] = $product->sizes()->create($values)->id;
            }
        }

        $product->sizes()->whereNotIn('id', $keptIds)->delete();

        // Update warna gambar galeri yang sudah ada
        foreach ($request->input('existing_images', []) as $id => $row) {
            $product->images()->where('id', $id)->update(['color' => $row['color'] ?? null]);
        }

        $this->saveGallery($request, $product);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    private function saveGallery(Request $request, Product $product): void
    {
        $nextOrder = ($product->images()->max('order') ?? -1) + 1;

        foreach ($request->input('gallery', []) as $i => $row) {
            $file = $request->file("gallery.$i.file");

            if (!$file) {
                continue;
            }

            $product->images()->create([
                'image' => $file->store('products', 'public'),
                'color' => $row['color'] ?? null,
                'order' => $nextOrder++,
            ]);
        }
    }
}