<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search', '');
        $perusahaan = $request->get('perusahaan', '');
        $sort       = $request->get('sort', 'terbaru');

        // Produk Grosir = produk dengan is_grosir = true
        $query = Medicine::where('is_grosir', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($perusahaan) {
            $query->where('kategori', $perusahaan);
        }

        match ($sort) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'nama'       => $query->orderBy('nama_obat', 'asc'),
            default      => $query->latest(),
        };

        $medicines   = $query->paginate(12)->withQueryString();
        $perusahaans = Companies::LIST;
        $total       = Medicine::where('is_grosir', true)->count();

        return view('prescriptions', compact('medicines', 'search', 'perusahaan', 'sort', 'perusahaans', 'total'));
    }
}
