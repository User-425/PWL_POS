<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PembelianModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class StockController extends Controller
{
    /**
     * Display a listing of the stocks.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Stok',
            'list' => ['Home', 'Barang', 'Stok']
        ];

        $page = (object) [
            'title' => 'Manajemen Stok Barang di Sistem'
        ];

        $activeMenu = 'stok';

        $products = BarangModel::with(['kategori'])->get();
        $suppliers = SupplierModel::all();

        // Get current stock for each product
        $stockData = [];
        foreach ($products as $product) {
            $totalStock = DB::table('t_stok')
                ->where('barang_id', $product->barang_id)
                ->sum('stok_jumlah');

            $stockData[$product->barang_id] = $totalStock;
        }

        return view('stok.index', compact('breadcrumb', 'page', 'activeMenu', 'products', 'suppliers', 'stockData'));
    }

    /**
     * Check stock availability for a product
     */
    public function checkStock(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
        ]);

        $barang = BarangModel::findOrFail($request->barang_id);
        $currentStock = StokModel::where('barang_id', $request->barang_id)
            ->sum('stok_jumlah');

        return response()->json([
            'barang' => $barang,
            'stock' => $currentStock,
        ]);
    }

    /**
     * Add new stock entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'supplier_id' => 'required|exists:m_supplier,supplier_id',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        $stock = new StokModel();
        $stock->barang_id = $request->barang_id;
        $stock->supplier_id = $request->supplier_id;
        $stock->user_id = Auth::id();
        $stock->stok_tanggal = now();
        $stock->stok_jumlah = $request->stok_jumlah;
        $stock->penjualan_id = null;
        $stock->pembelian_id = null;
        $stock->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
        ]);
    }

    public function history($id)
    {
        $barang = BarangModel::findOrFail($id);
        
        $pembelianDetails = DB::table('t_pembelian_detail')
            ->join('t_pembelian', 't_pembelian.pembelian_id', '=', 't_pembelian_detail.pembelian_id')
            ->join('m_supplier', 'm_supplier.supplier_id', '=', 't_pembelian.supplier_id')
            ->join('m_user', 'm_user.user_id', '=', 't_pembelian.user_id')
            ->select(
                't_pembelian_detail.*',
                't_pembelian.pembelian_tanggal',
                't_pembelian.pembelian_kode',
                'm_supplier.supplier_nama as supplier_nama',
                'm_user.nama as user_nama'
            )
            ->where('t_pembelian_detail.barang_id', $id)
            ->get();
    
        $penjualanDetails = DB::table('t_penjualan_detail')
            ->join('t_penjualan', 't_penjualan.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->join('m_user', 'm_user.user_id', '=', 't_penjualan.user_id')
            ->select(
                't_penjualan_detail.*',
                't_penjualan.penjualan_tanggal',
                't_penjualan.penjualan_kode',
                'm_user.nama as user_nama'
            )
            ->where('t_penjualan_detail.barang_id', $id)
            ->get();
    
        $history = collect();
        
        foreach ($pembelianDetails as $item) {
            $history->push([
                'tanggal' => Carbon::parse($item->pembelian_tanggal),
                'kode' => $item->pembelian_kode,
                'tipe' => 'Pembelian',
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'pihak' => $item->supplier_nama,
                'petugas' => $item->user_nama,
                'is_incoming' => true,
            ]);
        }
    
        foreach ($penjualanDetails as $item) {
            $history->push([
                'tanggal' => Carbon::parse($item->penjualan_tanggal),
                'kode' => $item->penjualan_kode,
                'tipe' => 'Penjualan',
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'pihak' => $item->customer_nama ?? 'Umum',
                'petugas' => $item->user_nama,
                'is_incoming' => false,
            ]);
        }
    
        $history = $history->sortByDesc('tanggal')->values();
        
        $formattedHistory = $history->map(function ($item) {
            $item['tanggal_formatted'] = $item['tanggal']->format('d M Y H:i');
            return $item;
        });
    
        $currentStock = StokModel::where('barang_id', $id)->sum('stok_jumlah');
        
        if (request()->ajax() || request()->wantsJson()) {
            // Return the rendered view for the modal
            $breadcrumb = (object) [
                'title' => 'Riwayat Stok',
                'list' => ['Home', 'Barang', 'Stok', 'Riwayat']
            ];
    
            $page = (object) [
                'title' => 'Riwayat Stok Barang: ' . $barang->barang_nama
            ];
    
            $view = view('stok.history', compact('breadcrumb', 'page', 'barang', 'history', 'currentStock'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'barang' => $barang,
                'currentStock' => $currentStock
            ]);
        }
    
        $breadcrumb = (object) [
            'title' => 'Riwayat Stok',
            'list' => ['Home', 'Barang', 'Stok', 'Riwayat']
        ];
    
        $page = (object) [
            'title' => 'Riwayat Stok Barang: ' . $barang->barang_nama
        ];
    
        $activeMenu = 'stok';
    
        return view('stok.history', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'history', 'currentStock'));
    }
}