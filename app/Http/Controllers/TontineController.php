<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use Illuminate\Http\Request;

class TontineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Tontine::withCount('subscriptions');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tontines = $query->latest()->paginate(15);

        return view('tontines.index', compact('tontines'));
    }

    public function create()
    {
        return view('tontines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'nullable|in:pending,active',
        ]);

        // Si end_date n'est pas fournie, la calculer automatiquement (45 jours après start_date)
        if (empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDays(Tontine::DURATION_DAYS);
        }

        $validated['status'] = $validated['status'] ?? 'pending';

        $tontine = Tontine::create($validated);

        return redirect()->route('tontines.show', $tontine)
            ->with('success', 'Tontine créée avec succès.');
    }

    public function show(Tontine $tontine)
    {
        $tontine->load(['subscriptions.user', 'subscriptions.items.perfume', 'subscriptions.payments']);

        return view('tontines.show', compact('tontine'));
    }

    public function edit(Tontine $tontine)
    {
        // Ne peut pas modifier une tontine active ou terminée
        if (in_array($tontine->status, ['completed', 'cancelled'])) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Impossible de modifier une tontine terminée ou annulée.');
        }

        return view('tontines.edit', compact('tontine'));
    }

    public function update(Request $request, Tontine $tontine)
    {
        // Ne peut pas modifier une tontine active ou terminée
        if (in_array($tontine->status, ['completed', 'cancelled'])) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Impossible de modifier une tontine terminée ou annulée.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        // Si end_date n'est pas fournie, la calculer automatiquement
        if (empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDays(Tontine::DURATION_DAYS);
        }

        $tontine->update($validated);

        return redirect()->route('tontines.show', $tontine)
            ->with('success', 'Tontine mise à jour avec succès.');
    }

    public function destroy(Tontine $tontine)
    {
        // Ne peut supprimer que les tontines en attente sans participants
        if ($tontine->status !== 'pending' || $tontine->subscriptions()->count() > 0) {
            return redirect()->route('tontines.index')
                ->with('error', 'Impossible de supprimer cette tontine.');
        }

        $tontine->delete();

        return redirect()->route('tontines.index')
            ->with('success', 'Tontine supprimée avec succès.');
    }

    public function activate(Tontine $tontine)
    {
        if ($tontine->status !== 'pending') {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Seules les tontines en attente peuvent être activées.');
        }

        $tontine->update(['status' => 'active']);

        return redirect()->route('tontines.show', $tontine)
            ->with('success', 'Tontine activée avec succès.');
    }

    public function cancel(Tontine $tontine)
    {
        if (in_array($tontine->status, ['completed', 'cancelled'])) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Cette tontine ne peut pas être annulée.');
        }

        $tontine->update(['status' => 'cancelled']);

        return redirect()->route('tontines.show', $tontine)
            ->with('success', 'Tontine annulée avec succès.');
    }

    public function complete(Tontine $tontine)
    {
        if ($tontine->status !== 'active') {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Seules les tontines actives peuvent être complétées.');
        }

        $tontine->update(['status' => 'completed']);

        return redirect()->route('tontines.show', $tontine)
            ->with('success', 'Tontine complétée avec succès.');
    }
}
