<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
class BukuController extends Controller
{
    public function index() {
        return Buku::all();
    }
public function store(Request $request)
{
    $url = null;

    if ($request->hasFile('cover')) {
        $upload = Cloudinary::upload($request->file('cover')->getRealPath());
        $url = $upload->getSecurePath(); // URL gambar
    }

    $buku = Buku::create([
        'judul' => $request->judul,
        'penulis' => $request->penulis,
        'cover' => $url,
    ]);

    return response()->json(['message' => 'Buku ditambahkan', 'data' => $buku]);
}



    public function show($id) {
        return Buku::findOrFail($id);
    }


public function update(Request $request, $id)
{
    $buku = Buku::findOrFail($id);
    $data = $request->only('judul', 'penulis');

    if ($request->hasFile('cover')) {
        $upload = Cloudinary::upload($request->file('cover')->getRealPath());
        $data['cover'] = $upload->getSecurePath(); // ganti cover
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
