<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(session('success'));
        return view('Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomer_hp' => 'required|string|max:20',
            'keperluan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'signature' => 'required|string',
        ]);

        $signatureData = $request->signature;
        $signatureFileName = 'ttd/' . Str::uuid() . '.png';

        $signatureData = explode(',', $signatureData)[1];
        Storage::disk('public')->put($signatureFileName, base64_decode($signatureData));

        Menu::create([
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_hp' => $request->country_code . $request->nomer_hp,
            'keperluan' => $request->keperluan,
            'instansi' => $request->instansi,
            'ip_address' => $request->ip(),
            'ttd' => $signatureFileName,
        ]);

        return redirect()->route('menu.index')->with('success', 'Data berhasil disimpan!');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
