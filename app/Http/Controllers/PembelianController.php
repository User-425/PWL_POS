<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PembelianModel;
use App\Models\PembelianDetailModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PembelianController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)['title' => 'Daftar Transaksi Pembelian', 'list' => ['Home', 'Transaksi', 'Pembelian']];
        $page = (object)['title' => 'Daftar Transaksi Pembelian yang terdaftar di sistem'];
        $activeMenu = 'pembelian';

        $pembelians = PembelianModel::with(['details', 'user', 'supplier'])->orderBy('pembelian_tanggal', 'desc')->get();
        return view('transaksi.pembelian.index', compact('breadcrumb', 'page', 'activeMenu', 'pembelians'));
    }

    public function list(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
            ->startOfDay() : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $request->input('end_date') ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
            ->endOfDay() : Carbon::now()->endOfDay();

        $query = PembelianModel::with(['user', 'supplier', 'details'])
            ->whereBetween('pembelian_tanggal', [$startDate, $endDate]);

        return DataTables::of($query)
            ->addColumn('date', function ($pembelian) {
                if ($pembelian->pembelian_tanggal instanceof \Carbon\Carbon) {
                    return $pembelian->pembelian_tanggal->format('d M Y H:i');
                }
                return Carbon::parse($pembelian->pembelian_tanggal)->format('d M Y H:i');
            })
            ->addColumn('petugas', function ($pembelian) {
                return $pembelian->user->nama ?? 'Unknown';
            })
            ->addColumn('supplier_name', function ($pembelian) {
                return $pembelian->supplier->nama ?? 'Unknown';
            })
            ->addColumn('total', function ($pembelian) {
                $total = 0;
                foreach ($pembelian->details as $detail) {
                    $total += ($detail->harga * $detail->jumlah);
                }
                return 'Rp' . number_format($total, 0, ',', '.');
            })
            ->addColumn('items', function ($pembelian) {
                return $pembelian->details()->sum('jumlah') ?? 0;
            })
            ->addColumn('action', function ($pembelian) {
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)['title' => 'Tambah Transaksi Pembelian', 'list' => ['Home', 'Transaksi', 'Pembelian', 'Tambah']];
        $page = (object)['title' => 'Buat Transaksi Pembelian Baru (Restocking)'];
        $activeMenu = 'pembelian';

        $products = BarangModel::with('kategori')->get();
        $suppliers = SupplierModel::all();
        $stockData = [];

        foreach ($products as $product) {
            $stock = StokModel::where('barang_id', $product->barang_id)->sum('stok_jumlah');
            $stockData[$product->barang_id] = $stock;
        }

        return view('transaksi.pembelian.create', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'products',
            'suppliers',
            'stockData'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:m_supplier,supplier_id',
            'products' => 'required|array|min:1',
            'products.*.barang_id' => 'required|exists:m_barang,barang_id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $pembelian = PembelianModel::create([
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'pembelian_kode' => 'PBL-' . date('Ymd') . '-' . rand(1000, 9999),
                'pembelian_tanggal' => now(),
            ]);

            foreach ($request->products as $product) {
                PembelianDetailModel::create([
                    'pembelian_id' => $pembelian->pembelian_id,
                    'barang_id' => $product['barang_id'],
                    'harga' => $product['price'],
                    'jumlah' => $product['quantity'],
                ]);

                // Tambahkan stok
                StokModel::create([
                    'barang_id' => $product['barang_id'],
                    'supplier_id' => $request->supplier_id,
                    'user_id' => Auth::id(),
                    'stok_tanggal' => now(),
                    'stok_jumlah' => $product['quantity'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi pembelian berhasil disimpan!',
                'pembelian_id' => $pembelian->pembelian_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi pembelian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $breadcrumb = (object)['title' => 'Detail Transaksi Pembelian', 'list' => ['Home', 'Transaksi', 'Pembelian', 'Detail']];
        $page = (object)['title' => 'Detail Transaksi Pembelian'];
        $activeMenu = 'pembelian';
        
        $pembelian = PembelianModel::with(['details.barang', 'user', 'supplier'])->findOrFail($id);

        return view('transaksi.pembelian.show', compact('breadcrumb', 'page', 'activeMenu', 'pembelian'));
    }

    public function receipt($id)
    {
        $pembelian = PembelianModel::with(['details.barang', 'user', 'supplier'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.pembelian.pdf.receipt', compact('pembelian'));
        return $pdf->stream('faktur-' . $pembelian->pembelian_kode . '.pdf');
    }

    public function detail($id)
    {
        try {
            $pembelian = PembelianModel::with(['details.barang', 'user', 'supplier'])->findOrFail($id);
            $pembelian->formatted_date = Carbon::parse($pembelian->pembelian_tanggal)->format('d/m/Y H:i');

            $total = 0;
            foreach ($pembelian->details as $detail) {
                $total += ($detail->harga * $detail->jumlah);
            }
            $pembelian->formatted_total = number_format($total, 0, ',', '.');

            return response()->json(['success' => true, 'pembelian' => $pembelian]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat data pembelian: ' . $e->getMessage()], 500);
        }
    }
}