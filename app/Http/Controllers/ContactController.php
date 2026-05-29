<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesan; // Import model Pesan jika pakai DB

class ContactController extends Controller
{
    public function index()
    {
        return view('kontak');
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required',
    ]);

    \App\Models\Pesan::create($request->all());

    return redirect()->back()->with('success', 'Pesan kamu sudah terkirim, Sahabat Yummy! 🍤');
}
}