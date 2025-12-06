<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        $products = Product::with('productCategory')
            ->where('store_id', $store->id)
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();

        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $store = auth()->user()->store;

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'description'         => ['nullable', 'string'],
            'condition'           => ['required', 'in:new,second'],
            'price'               => ['required', 'numeric', 'min:0'],
            'weight'              => ['required', 'integer', 'min:1'],
            'stock'               => ['required', 'integer', 'min:0'],
            'images.*'            => ['nullable', 'image', 'max:2048'],
        ]);

        $data['store_id'] = $store->id;
        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        // Simpan gambar (kalau ada)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('product_images', 'public');

                ProductImage::create([
                    'product_id'   => $product->id,
                    'image'        => $path,
                    'is_thumbnail' => $index === 0, // gambar pertama dijadikan thumbnail
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $categories = ProductCategory::all();
        $product->load('productImages');

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'description'         => ['nullable', 'string'],
            'condition'           => ['required', 'in:new,second'],
            'price'               => ['required', 'numeric', 'min:0'],
            'weight'              => ['required', 'integer', 'min:1'],
            'stock'               => ['required', 'integer', 'min:0'],
            'images.*'            => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $product->update($data);

        // kalau upload gambar baru, tambahkan (biar simple, nggak hapus yang lama)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('product_images', 'public');

                ProductImage::create([
                    'product_id'   => $product->id,
                    'image'        => $path,
                    'is_thumbnail' => false, // nanti kamu bisa bikin UI set thumbnail
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        // hapus gambar juga
        foreach ($product->productImages as $img) {
            // optional: Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * Pastikan produk ini milik store dari user yang login
     */
    protected function authorizeProduct(Product $product)
    {
        $store = auth()->user()->store;

        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak berhak mengelola produk ini.');
        }
    }
}
