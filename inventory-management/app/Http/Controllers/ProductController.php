<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Log;

// Controller ini bertanggung jawab untuk mengelola operasi terkait produk
class ProductController extends Controller
{
    // Metode ini menampilkan daftar semua produk
    public function index()
    {
        // Mengambil semua data produk dari database
        $products = Product::all();
        // Mengembalikan view 'products.index' dengan data produk yang telah diambil
        return view('products.index', compact('products'));
    }

    // Metode ini menampilkan formulir untuk membuat produk baru
    public function create()
    {
        // Mengembalikan view 'products.create' untuk menampilkan formulir pembuatan produk
        return view('products.create');
    }

    // Metode ini menyimpan produk baru ke database
    public function store(Request $request)
    {
        // Melakukan validasi terhadap data yang diinput oleh pengguna
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Membuat produk baru dengan data yang telah divalidasi
        $product = Product::create($validatedData);

        // Jika ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            // Menyimpan gambar ke storage dengan nama folder 'product_images'
            $path = $request->file('image')->store('product_images', 'public');
            // Membuat entri baru di tabel media untuk menyimpan informasi gambar
            Media::create([
                'product_id' => $product->id,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_type' => 'image',
                'file_path' => $path
            ]);
        }

        // Redirect ke halaman index produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Metode ini menampilkan detail dari sebuah produk
    public function show(Product $product)
    {
        // Mengembalikan view 'products.show' dengan data produk yang diminta
        return view('products.show', compact('product'));
    }

    // Metode ini menampilkan formulir untuk mengedit produk
    public function edit(Product $product)
    {
        // Mengembalikan view 'products.edit' dengan data produk yang akan diedit
        return view('products.edit', compact('product'));
    }

    // Metode ini memperbarui data produk di database
    public function update(Request $request, Product $product)
    {
        // Melakukan validasi terhadap data yang diinput oleh pengguna
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Memperbarui data produk dengan data yang telah divalidasi
        $product->update($validatedData);

        // Jika ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            // Menghapus gambar lama jika ada
            if ($product->media->isNotEmpty()) {
                Storage::disk('public')->delete($product->media->first()->file_path);
                $product->media->first()->delete();
            }

            // Menyimpan gambar baru ke storage
            $path = $request->file('image')->store('product_images', 'public');
            // Membuat entri baru di tabel media untuk menyimpan informasi gambar baru
            Media::create([
                'product_id' => $product->id,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_type' => 'image',
                'file_path' => $path
            ]);
        }

        // Redirect ke halaman index produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Metode ini menghapus produk dari database
    public function destroy(Product $product)
    {
        // Jika produk memiliki media (gambar)
        if ($product->media->isNotEmpty()) {
            // Menghapus file gambar dari storage
            Storage::disk('public')->delete($product->media->first()->file_path);
            // Menghapus entri media dari database
            $product->media->first()->delete();
        }
        // Menghapus produk dari database
        $product->delete();
        // Redirect ke halaman index produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Metode ini menampilkan daftar produk untuk pengguna (hanya produk dengan stok > 0)
    public function userIndex()
    {
        // Mengambil semua produk dengan stok > 0 beserta data media-nya
        $products = Product::with('media')->where('stock', '>', 0)->get();
        // Mengembalikan view 'products.user_index' dengan data produk
        return view('products.user_index', compact('products'));
    }

    // Metode ini menampilkan tampilan produk untuk pengguna
    public function userView()
    {
        // Mencatat log bahwa method userView dipanggil
        Log::info('userView method called');
        // Mengambil semua produk beserta data media-nya
        $products = Product::with('media')->get();
        // Mengembalikan view 'products.user_view' dengan data produk
        return view('products.user_view', compact('products'));
    }

    // Metode ini mengekspor data produk ke file Excel
    public function export() 
    {
        $products = Product::all();
        return (new FastExcel($products))->download('products.xlsx');
    }

    // Metode ini menampilkan formulir untuk mengimpor data produk
    public function importForm()
    {
        return view('products.import');
    }

    // Metode ini mengimpor data produk dari file Excel
    public function import(Request $request) 
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');

        try {
            // Mengimpor data dari file Excel dan membuat produk baru untuk setiap baris
            $products = (new FastExcel)->import($file, function ($line) {
                Log::info('Importing line:', $line);  // Mencatat setiap baris yang diimpor
                return Product::create([
                    'name' => $line['Name'] ?? $line['name'] ?? '',
                    'description' => $line['Description'] ?? $line['description'] ?? '',
                    'price' => $line['Price'] ?? $line['price'] ?? 0,
                    'stock' => $line['Stock'] ?? $line['stock'] ?? 0,
                ]);
            });
            return redirect()->route('products.index')->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());  // Mencatat error jika terjadi
            return redirect()->back()->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }
}
