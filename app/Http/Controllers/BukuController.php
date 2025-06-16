<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class BukuController extends Controller
{
    public function index() {
        return Buku::all();
    }

public function store(Request $request)
{
    $data = $request->all();

    if ($request->hasFile('cover')) {
        $data['cover'] = $request->file('cover')->store('covers', 'public');
    }

    Buku::create($data);
    return response()->json(['message' => 'Buku ditambahkan']);
}

    public function show($id) {
        return Buku::findOrFail($id);
    }


public function update(Request $request, $id)
{
    $buku = Buku::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('cover')) {
        // Hapus gambar lama dulu
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        // Simpan gambar baru
        $data['cover'] = $request->file('cover')->store('covers', 'public');
    }

    $buku->update($data);

    return response()->json(['message' => 'Buku diupdate']);
    
}


public function destroy($id)
{
    $buku = Buku::findOrFail($id);

    // Hapus gambar dari storage
    if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
        Storage::disk('public')->delete($buku->cover);
    }

    // Hapus data dari database
    $buku->delete();

    return response()->json(['message' => 'Buku dihapus']);
}
}
