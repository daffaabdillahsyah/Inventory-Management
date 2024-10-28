<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * OrderController
 * 
 * Controller ini bertanggung jawab untuk mengelola semua operasi terkait pesanan (order)
 * dalam sistem manajemen inventaris.
 */
class OrderController extends Controller
{
    /**
     * Constructor
     * 
     * Menerapkan middleware 'auth' untuk memastikan bahwa semua metode dalam controller ini
     * hanya dapat diakses oleh pengguna yang sudah terautentikasi.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua pesanan
     * 
     * Mengambil semua pesanan dari database beserta data pengguna terkait,
     * kemudian menampilkannya dalam view 'orders.index'.
     */
    public function index()
    {
        $orders = Order::with('user')->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan tertentu
     * 
     * Memuat data pesanan tertentu beserta item pesanan, produk, dan data pengguna terkait,
     * kemudian menampilkannya dalam view 'orders.show'.
     */
    public function show(Order $order)
    {
        $order->load('orderItems.product', 'user');
        return view('orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan
     * 
     * Memvalidasi input, memperbarui status pesanan, dan mengarahkan kembali ke halaman daftar pesanan
     * dengan pesan sukses.
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validatedData);

        return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
    }

    /**
     * Menampilkan formulir pembuatan pesanan baru
     * 
     * Mengambil semua produk yang memiliki stok lebih dari 0 dan menampilkannya
     * dalam view 'orders.create'.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Menyimpan pesanan baru
     * 
     * Memproses pembuatan pesanan baru, termasuk validasi input, pembuatan record pesanan,
     * pembaruan stok produk, dan penanganan transaksi database.
     */
    public function store(Request $request)
    {
        // Mencatat data pesanan yang diterima untuk keperluan debugging
        Log::info('Order data received:', $request->all());

        try {
            // Validasi input
            $validatedData = $request->validate([
                'product_id' => 'required|array',
                'product_id.*' => 'required|exists:products,id',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mencatat error validasi dan mengembalikan respons error
            Log::error('Validation failed:', $e->errors());
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        }

        $user = Auth::user();
        $totalAmount = 0;

        // Menggunakan transaksi database untuk memastikan integritas data
        DB::transaction(function () use ($request, $user, &$totalAmount, &$order) {
            // Membuat record pesanan baru
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            // Memproses setiap item dalam pesanan
            foreach ($request->product_id as $index => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = $request->quantity[$index];

                // Memeriksa ketersediaan stok
                if ($product->stock < $quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                // Membuat record item pesanan
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                // Mengurangi stok produk
                $product->decrement('stock', $quantity);
                $totalAmount += $product->price * $quantity;
            }

            // Memperbarui total amount pesanan
            $order->update(['total_amount' => $totalAmount]);
        });

        // Memicu event OrderCreated
        event(new OrderCreated($order));

        // Mengembalikan respons sukses
        return response()->json([
            'success' => true, 
            'message' => 'Order placed successfully.',
            'redirect' => route('orders.user')
        ]);
    }

    /**
     * Menampilkan pesanan pengguna
     * 
     * Mengambil semua pesanan milik pengguna yang sedang login,
     * termasuk item pesanan dan data produk terkait, kemudian menampilkannya
     * dalam view 'orders.user_orders'.
     */
    public function userOrders()
    {
        $user = Auth::user(); // Dapatkan user yang sedang login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your orders.');
        }

        $orders = Order::where('user_id', $user->id)
                       ->with(['orderItems.product'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('orders.user', compact('orders'));
    }

    /**
     * Menampilkan dashboard admin
     * 
     * Mengambil 5 pesanan terbaru beserta data pengguna terkait
     * dan menampilkannya dalam view 'admin.dashboard'.
     */
    public function adminDashboard()
    {
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('recentOrders'));
    }

    /**
     * Download PDF of selected orders
     * 
     * Generates a PDF containing details of the selected orders
     * and returns it as a downloadable file.
     */
    public function downloadPdf(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        
        if (empty($orderIds)) {
            return redirect()->back()->with('error', 'Please select at least one order to download.');
        }

        $orders = Order::whereIn('id', $orderIds)
                       ->where('user_id', Auth::id())
                       ->with('orderItems.product')
                       ->get();

        $pdf = Pdf::loadView('orders.pdf', compact('orders'));
        
        return $pdf->download('orders_report.pdf');
    }
}
