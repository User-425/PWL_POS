<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\StokModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)['title' => 'Daftar Transaksi Penjualan', 'list' => ['Home', 'Transaksi', 'Penjualan']];

        $page = (object)['title' => 'Daftar Transaksi Penjualan yang terdaftar di sistem'];

        $activeMenu = 'penjualan';

        $transactions = TransactionModel::with(['details', 'user'])->orderBy('penjualan_tanggal', 'desc')
            ->get();
        return view('transaksi.penjualan.index', compact('breadcrumb', 'page', 'activeMenu', 'transactions'));
    }

    public function list(Request $request)
    {

        $startDate = $request->input('start_date') ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
            ->startOfDay() : Carbon::now()
            ->subDays(30)
            ->startOfDay();

        $endDate = $request->input('end_date') ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
            ->endOfDay() : Carbon::now()
            ->endOfDay();

        $query = TransactionModel::with(['user', 'details'])->whereBetween('penjualan_tanggal', [$startDate, $endDate]);

        return DataTables::of($query)->addColumn('date', function ($transaction) {

            if ($transaction->penjualan_tanggal instanceof \Carbon\Carbon) {
                return $transaction
                    ->penjualan_tanggal
                    ->format('d M Y H:i');
            }

            return Carbon::parse($transaction->penjualan_tanggal)
                ->format('d M Y H:i');
        })->addColumn('cashier', function ($transaction) {
            return $transaction
                ->user->nama ?? 'Unknown';
        })->addColumn('total', function ($transaction) {
            $total = 0;
            foreach ($transaction->details as $detail) {
                $total += ($detail->harga * $detail->jumlah);
            }
            return 'Rp' . number_format($total, 0, ',', '.');
        })->addColumn('items', function ($transaction) {
            return $transaction->details()
                ->sum('jumlah') ?? 0;
        })->addColumn('pembeli', function ($transaction) {
            return $transaction->pembeli ?? 'Umum';
        })->addColumn('action', function ($transaction) {
            return '';
        })->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)['title' => 'Tambah Transaksi Penjualan', 'list' => ['Home', 'Transaksi', 'Penjualan', 'Tambah']];

        $page = (object)['title' => 'Buat Transaksi Penjualan Baru'];

        $activeMenu = 'penjualan';

        $products = BarangModel::with('kategori')->get();
        $stockData = [];

        foreach ($products as $product)
        {
            $stock = StokModel::where('barang_id', $product->barang_id)
                ->sum('stok_jumlah');
            $stockData[$product->barang_id] = $stock;
        }

        $availableProducts = $products->filter(function ($product) use ($stockData)
        {
            return isset($stockData[$product->barang_id]) && $stockData[$product->barang_id] > 0;
        });

        return view('transaksi.penjualan.create', compact('breadcrumb', 'page', 'activeMenu', 'availableProducts', 'stockData'));
    }

    public function store(Request $request)
    {
        $request->validate(['pembeli' => 'required|string|max:50', 'products' => 'required|array|min:1', 'products.*.barang_id' => 'required|exists:m_barang,barang_id', 'products.*.quantity' => 'required|integer|min:1', 'products.*.price' => 'required|numeric|min:1', ]);

        DB::beginTransaction();
        try
        {

            $transaction = TransactionModel::create(['user_id' => Auth::id() , 'pembeli' => $request->pembeli, 'penjualan_kode' => 'TRX-' . date('Ymd') . '-' . rand(1000, 9999) , 'penjualan_tanggal' => now() , ]);

            foreach ($request->products as $product)
            {
                TransactionDetailModel::create(['penjualan_id' => $transaction->penjualan_id, 'barang_id' => $product['barang_id'], 'harga' => $product['price'], 'jumlah' => $product['quantity'], ]);
                $barang_id = $product['barang_id'];
                $stok = StokModel::where('barang_id', $barang_id)->first();
                if ($stok) {
                    $stok->stok_jumlah -= $product['quantity'];
                    $stok->save();
                } else {
                    return response()->json(['success' => false, 'message' => 'Stock not found for product ID: ' . $barang_id], 404);
                }
                // StokModel::create(['barang_id' => $product['barang_id'], 'supplier_id' => 1, 'user_id' => Auth::id() , 'stok_tanggal' => now() , 'stok_jumlah' => - $product['quantity'], ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaction completed successfully!', 'transaction_id' => $transaction->penjualan_id]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create transaction: ' . $e->getMessage() ], 500);
        }
    }
    public function show($id)
    {
        $breadcrumb = (object)['title' => 'Detail Transaksi Penjualan', 'list' => ['Home', 'Transaksi', 'Penjualan', 'Detail']];

        $page = (object)['title' => 'Detail Transaksi Penjualan'];

        $activeMenu = 'penjualan';
        $transaction = TransactionModel::with(['details.barang', 'user'])->findOrFail($id);

        return view('transaksi.penjualan.show', compact('breadcrumb', 'page', 'activeMenu', 'transaction'));
    }
    public function receipt($id)
    {
        $transaction = TransactionModel::with(['details.barang', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.penjualan.pdf.receipt', compact('transaction'));
        return $pdf->stream('receipt-' . $transaction->penjualan_kode . '.pdf');
    }

    public function stats(Request $request)
    {
        $startDate = $request->input('start_date', now()
            ->subDays(30)
            ->format('Y-m-d'));
        $endDate = $request->input('end_date', now()
            ->format('Y-m-d'));
        $endDateForQuery = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $transactions = TransactionModel::whereBetween('penjualan_tanggal', [$startDate, $endDateForQuery])->with('details')
            ->get();
        $totalTransactions = $transactions->count();
        $todayTransactions = TransactionModel::whereDate('penjualan_tanggal', now()->format('Y-m-d'))
            ->count();
        $totalRevenue = 0;
        $totalItems = 0;

        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $totalRevenue += ($detail->harga * $detail->jumlah);
                $totalItems += $detail->jumlah;
            }
        }

        return response()
            ->json(['total_transactions' => $totalTransactions, 'total_revenue' => number_format($totalRevenue, 0, ',', '.'), 'today_transactions' => $todayTransactions, 'total_items' => $totalItems]);
    }

    public function detail($id)
    {
        try {
            $transaction = TransactionModel::with(['details.barang', 'user'])->findOrFail($id);

            $transaction->formatted_date = Carbon::parse($transaction->penjualan_tanggal)
                ->format('d/m/Y H:i');

            $total = 0;
            foreach ($transaction->details as $detail) {
                $total += ($detail->harga * $detail->jumlah);
            }
            $transaction->formatted_total = number_format($total, 0, ',', '.');

            return response()->json(['success' => true, 'transaction' => $transaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load transaction: ' . $e->getMessage()], 500);
        }
    }
}
