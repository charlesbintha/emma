<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfumeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Perfume::with('supplier');

        if ($request->has('available') && $request->available == '1') {
            $query->available();
        }

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $perfumes = $query->latest()->paginate(12);
        $suppliers = Supplier::all();

        return view('perfumes.index', compact('perfumes', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('perfumes.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|image|mimes:webp,jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('perfumes', 'public');
        }

        $perfume = Perfume::create($validated);

        return redirect()->route('perfumes.show', $perfume)
            ->with('success', 'Parfum créé avec succès.');
    }

    public function show(Perfume $perfume)
    {
        $perfume->load('supplier', 'tontineSubscriptions');
        return view('perfumes.show', compact('perfume'));
    }

    public function edit(Perfume $perfume)
    {
        $suppliers = Supplier::all();
        return view('perfumes.edit', compact('perfume', 'suppliers'));
    }

    public function update(Request $request, Perfume $perfume)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|image|mimes:webp,jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image_url')) {
            // Supprimer l'ancienne image si elle existe
            if ($perfume->image_url) {
                Storage::disk('public')->delete($perfume->image_url);
            }
            $validated['image_url'] = $request->file('image_url')->store('perfumes', 'public');
        }

        $perfume->update($validated);

        return redirect()->route('perfumes.show', $perfume)
            ->with('success', 'Parfum mis à jour avec succès.');
    }

    public function destroy(Perfume $perfume)
    {
        // Supprimer l'image si elle existe
        if ($perfume->image_url) {
            Storage::disk('public')->delete($perfume->image_url);
        }

        $perfume->delete();

        return redirect()->route('perfumes.index')
            ->with('success', 'Parfum supprimé avec succès.');
    }
}
