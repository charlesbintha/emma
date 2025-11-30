@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Gestion des Paiements</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Paiements</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card o-hidden">
                <div class="bg-success b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0 text-white">Total Payé</span>
                            <h4 class="mb-0 counter text-white">
                                {{ number_format(\App\Models\Payment::where('status', 'paid')->sum('amount'), 0, ',', ' ') }}
                            </h4>
                            <small class="text-white">FCFA</small>
                            <i data-feather="dollar-sign" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card o-hidden">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0 text-white">Paiements Confirmés</span>
                            <h4 class="mb-0 counter text-white">
                                {{ \App\Models\Payment::where('status', 'paid')->count() }}
                            </h4>
                            <i data-feather="check-circle" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card o-hidden">
                <div class="bg-warning b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0 text-white">En Attente</span>
                            <h4 class="mb-0 counter text-white">
                                {{ \App\Models\Payment::where('status', 'pending')->count() }}
                            </h4>
                            <i data-feather="clock" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card o-hidden">
                <div class="bg-danger b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0 text-white">En Retard</span>
                            <h4 class="mb-0 counter text-white">
                                {{ \App\Models\Payment::where('status', 'late')->count() }}
                            </h4>
                            <i data-feather="alert-triangle" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Liste des paiements</h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('payments.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>En retard</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tontine</label>
                                <select name="tontine_id" class="form-select">
                                    <option value="">Toutes les tontines</option>
                                    @foreach(\App\Models\Tontine::all() as $tontine)
                                        <option value="{{ $tontine->id }}" {{ request('tontine_id') == $tontine->id ? 'selected' : '' }}>
                                            {{ $tontine->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mode de paiement</label>
                                <select name="payment_method" class="form-select">
                                    <option value="">Tous les modes</option>
                                    <option value="mobile_money" {{ request('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Espèces</option>
                                    <option value="check" {{ request('payment_method') === 'check' ? 'selected' : '' }}>Chèque</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Client, référence..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="filter"></i> Filtrer
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i data-feather="x"></i> Réinitialiser
                            </a>
                        </div>
                    </form>

                    <!-- Payments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Client</th>
                                    <th>Tontine</th>
                                    <th>Parfum</th>
                                    <th>N° Paiement</th>
                                    <th>Montant</th>
                                    <th>Échéance</th>
                                    <th>Statut</th>
                                    <th>Mode</th>
                                    <th>Date paiement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr class="{{ $payment->isLate() && $payment->status !== 'paid' ? 'table-danger' : '' }}">
                                    <td><strong>{{ $payment->id }}</strong></td>
                                    <td>
                                        {{ $payment->tontineSubscription->user->name }}<br>
                                        <small class="text-muted">{{ $payment->tontineSubscription->user->email }}</small>
                                    </td>
                                    <td>{{ $payment->tontineSubscription->tontine->name }}</td>
                                    <td>
                                        @foreach($payment->tontineSubscription->items->take(2) as $item)
                                            {{ $item->perfume->name }} ({{ $item->quantity }}x)@if(!$loop->last), @endif
                                        @endforeach
                                        @if($payment->tontineSubscription->items->count() > 2)
                                            <br><small class="text-muted">+{{ $payment->tontineSubscription->items->count() - 2 }} autre(s)</small>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-light-primary">#{{ $payment->payment_number }}</span></td>
                                    <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($payment->status === 'paid')
                                            <span class="badge badge-success">
                                                <i data-feather="check"></i> Payé
                                            </span>
                                        @elseif($payment->status === 'late')
                                            <span class="badge badge-danger">
                                                <i data-feather="alert-circle"></i> En retard
                                            </span>
                                        @elseif($payment->status === 'cancelled')
                                            <span class="badge badge-secondary">Annulé</span>
                                        @else
                                            @if($payment->isLate())
                                                <span class="badge badge-danger">
                                                    <i data-feather="alert-triangle"></i> En retard
                                                </span>
                                            @else
                                                <span class="badge badge-warning">En attente</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_method)
                                            @if($payment->payment_method === 'mobile_money')
                                                <span class="badge badge-light-primary">Mobile Money</span>
                                            @elseif($payment->payment_method === 'bank_transfer')
                                                <span class="badge badge-light-success">Virement</span>
                                            @elseif($payment->payment_method === 'cash')
                                                <span class="badge badge-light-warning">Espèces</span>
                                            @elseif($payment->payment_method === 'check')
                                                <span class="badge badge-light-info">Chèque</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_date)
                                            {{ $payment->payment_date->format('d/m/Y') }}<br>
                                            @if($payment->reference)
                                                <small class="text-muted">Réf: {{ $payment->reference }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-action" role="group">
                                            <a href="{{ route('payments.show', $payment) }}"
                                               class="btn btn-sm btn-outline-info btn-action"
                                               title="Voir détails">
                                                <i data-feather="eye"></i>
                                            </a>
                                            @if($payment->status !== 'paid' && $payment->status !== 'cancelled')
                                                <a href="{{ route('payments.pay', $payment) }}"
                                                   class="btn btn-sm btn-outline-primary btn-action"
                                                   title="Marquer comme payé">
                                                    <i data-feather="credit-card"></i>
                                                </a>
                                            @endif
                                            @if($payment->status === 'paid')
                                                <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Annuler">
                                                        <i data-feather="x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                                        <p class="text-muted">Aucun paiement trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($payments->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>TOTAL (page actuelle)</strong></td>
                                    <td colspan="6">
                                        <strong>{{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
