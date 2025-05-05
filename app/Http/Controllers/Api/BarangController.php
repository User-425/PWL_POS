<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barang = BarangModel::with('kategori')->get();
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return BarangModel::with('kategori')->find($barang->barang_id);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return BarangModel::with('kategori')->find($barang->barang_id);
    }

    public function destroy(BarangModel $barang)
    {
        // Delete image if exists
        if ($barang->image) {
            Storage::delete('public/barang/' . $barang->image);
        }

        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }

    public function upload(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('public/barang', $imageName);

        // Create barang
        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'image' => $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang created successfully with image',
            'data' => $barang
        ], 201);
    }

    public function getProductsWithImages()
    {
        $products = BarangModel::with('kategori')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
