<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KategoriPanduan;
use App\Models\ItemPanduan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanduanManagementController extends Controller
{
    // ===================== KATEGORI PANDUAN =====================

    public function indexKategori()
    {
        $kategori = KategoriPanduan::orderBy('urutan', 'asc')->get();
        return view('panduan.kategori.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        KategoriPanduan::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.panduan.kategori.index')
            ->with('success', 'Kategori panduan berhasil ditambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        $kategori = KategoriPanduan::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan,
        ]);

        return redirect()->route('superadmin.panduan.kategori.index')
            ->with('success', 'Kategori panduan berhasil diperbarui');
    }

    public function destroyKategori($id)
    {
        $kategori = KategoriPanduan::findOrFail($id);
        $kategori->delete();

        return redirect()->route('superadmin.panduan.kategori.index')
            ->with('success', 'Kategori panduan berhasil dihapus');
    }

    public function toggleStatusKategori($id)
    {
        $kategori = KategoriPanduan::findOrFail($id);
        $kategori->update(['is_active' => !$kategori->is_active]);

        return redirect()->route('superadmin.panduan.kategori.index')
            ->with('success', 'Status kategori berhasil diubah');
    }

    // ===================== ITEM PANDUAN =====================

    public function indexItem()
    {
        $items = ItemPanduan::with('kategoriPanduan')
            ->orderBy('kategori_panduan_id', 'asc')
            ->orderBy('urutan', 'asc')
            ->get();
        $kategori = KategoriPanduan::active()->ordered()->get();

        return view('panduan.item.index', compact('items', 'kategori'));
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'kategori_panduan_id' => 'required|exists:kategori_panduan,id',
            'judul' => 'required|string|max:200',
            'konten' => 'required|string',
            'urutan' => 'required|integer|min:0',
        ]);

        ItemPanduan::create($request->all());

        return redirect()->route('superadmin.panduan.item.index')
            ->with('success', 'Item panduan berhasil ditambahkan');
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'kategori_panduan_id' => 'required|exists:kategori_panduan,id',
            'judul' => 'required|string|max:200',
            'konten' => 'required|string',
            'urutan' => 'required|integer|min:0',
        ]);

        $item = ItemPanduan::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('superadmin.panduan.item.index')
            ->with('success', 'Item panduan berhasil diperbarui');
    }

    public function destroyItem($id)
    {
        $item = ItemPanduan::findOrFail($id);
        $item->delete();

        return redirect()->route('superadmin.panduan.item.index')
            ->with('success', 'Item panduan berhasil dihapus');
    }

    public function toggleStatusItem($id)
    {
        $item = ItemPanduan::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return redirect()->route('superadmin.panduan.item.index')
            ->with('success', 'Status item berhasil diubah');
    }
}
