@extends('layouts.app')

@section('title', 'Reçu de paiement #' . $payment->payment_number)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Reçu de paiement</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Mes Inscriptions</a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.show', $payment->tontineSubscription) }}">Détails</a></li>
                <li class="breadcrumb-item active">Reçu</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body" id="printable-receipt">
                    <div class="text-center mb-4">
                        <h2 class="text-primary mb-1">Tontine Parfums</h2>
                        <p class="text-muted mb-0">Système de Tontine pour Parfums</p>
                        <hr>
                        <h4 class="mb-3">
                            @if($payment->status === 'paid')
                                <span class="badge badge-success p-2">
                                    <i data-feather="check-circle"></i> PAIEMENT CONFIRMÉ
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="badge badge-warning p-2">
                                    <i data-feather="clock"></i> EN ATTENTE
                                </span>
                            @elseif($payment->status === 'late')
                                <span class="badge badge-danger p-2">
                                    <i data-feather="alert-triangle"></i> EN RETARD
                                </span>
                            @else
                                <span class="badge badge-secondary p-2">ANNULÉ</span>
                            @endif
                        </h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Client</h6>
                            <p class="mb-1"><strong>{{ $payment->tontineSubscription->user->name }}</strong></p>
                            <p class="mb-1">{{ $payment->tontineSubscription->user->email }}</p>
                            @if($payment->tontineSubscription->user->phone)
                                <p class="mb-0">{{ $payment->tontineSubscription->user->phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Informations du reçu</h6>
                            <p class="mb-1"><strong>Reçu N°:</strong> PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1"><strong>Date d'émission:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                            @if($payment->payment_date)
                                <p class="mb-0"><strong>Date de paiement:</strong> {{ $payment->payment_date->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Détails de l'inscription</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Tontine</label>
                                    <p class="mb-0"><strong>{{ $payment->tontineSubscription->tontine->name }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Date d'inscription</label>
                                    <p class="mb-0">{{ $payment->tontineSubscription->subscription_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Parfums commandés</label>
                                    @foreach($payment->tontineSubscription->items as $item)
                                        <p class="mb-0"><strong>{{ $item->perfume->name }}</strong> ({{ $item->quantity }}x)</p>
                                    @endforeach
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Montant total de la commande</label>
                                    <p class="mb-0">{{ number_format($payment->tontineSubscription->totalAmount(), 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-center">N° Paiement</th>
                                    <th class="text-center">Date d'échéance</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Versement mensuel</strong><br>
                                        <small class="text-muted">{{ $payment->tontineSubscription->tontine->name }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary">#{{ $payment->payment_number }}</span>
                                    </td>
                                    <td class="text-center">{{ $payment->due_date->format('d/m/Y') }}</td>
                                    <td class="text-end"><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>MONTANT TOTAL</strong></td>
                                    <td class="text-end"><h5 class="mb-0 text-primary">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($payment->status === 'paid')
                    <div class="card border-success mb-4">
                        <div class="card-body">
                            <h6 class="text-success mb-3"><i data-feather="credit-card"></i> Informations de paiement</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-muted small">Mode de paiement</label>
                                    <p class="mb-0">
                                        <strong>
                                            @if($payment->payment_method === 'mobile_money')
                                                Mobile Money
                                            @elseif($payment->payment_method === 'bank_transfer')
                                                Virement Bancaire
                                            @elseif($payment->payment_method === 'cash')
                                                Espèces
                                            @elseif($payment->payment_method === 'check')
                                                Chèque
                                            @else
                                                {{ $payment->payment_method }}
                                            @endif
                                        </strong>
                                    </p>
                                </div>
                                @if($payment->reference)
                                <div class="col-md-4">
                                    <label class="text-muted small">Référence</label>
                                    <p class="mb-0"><strong>{{ $payment->reference }}</strong></p>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <label class="text-muted small">Date de paiement</label>
                                    <p class="mb-0">{{ $payment->payment_date->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i data-feather="info"></i> Progression</h6>
                                @php
                                    $paidCount = $payment->tontineSubscription->payments->where('status', 'paid')->count();
                                    $totalCount = $payment->tontineSubscription->payments->count();
                                    $percentage = $totalCount > 0 ? ($paidCount / $totalCount) * 100 : 0;
                                @endphp
                                <p class="mb-2">{{ $paidCount }} / {{ $totalCount }} paiements effectués</p>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                                        {{ round($percentage) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Montants</h6>
                                <p class="mb-1">
                                    <strong>Payé:</strong> {{ number_format($payment->tontineSubscription->totalPaid(), 0, ',', ' ') }} FCFA
                                </p>
                                <p class="mb-0">
                                    <strong>Reste:</strong> {{ number_format($payment->tontineSubscription->totalAmount() - $payment->tontineSubscription->totalPaid(), 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($payment->tontineSubscription->isFullyPaid())
                    <div class="alert alert-success">
                        <h6><i data-feather="award"></i> Félicitations !</h6>
                        <p class="mb-0">Vous avez complété tous vos paiements. Votre parfum vous sera remis prochainement.</p>
                    </div>
                    @endif

                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="text-muted small mb-2">
                            Ce reçu a été généré automatiquement par le système Tontine Parfums.
                        </p>
                        <p class="text-muted small mb-0">
                            Pour toute question, veuillez contacter l'administration.
                        </p>
                    </div>
                </div>

                <div class="card-footer bg-white" id="action-buttons">
                    <div class="text-center">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i data-feather="printer"></i> Imprimer le reçu
                        </button>
                        <a href="{{ route('subscriptions.show', $payment->tontineSubscription) }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Retour à l'inscription
                        </a>
                        @if(auth()->user()->isAdmin())
                            @if($payment->status === 'paid')
                                <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i data-feather="x-circle"></i> Annuler le paiement
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .page-title,
    .sidebar,
    .page-header,
    #action-buttons,
    .btn {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    body {
        background: white !important;
    }
}
</style>
@endpush
