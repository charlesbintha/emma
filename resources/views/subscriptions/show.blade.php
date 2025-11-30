@extends('layouts.app')

@section('title', 'Détails de mon inscription')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Détails de l'inscription</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Mes Inscriptions</a></li>
                <li class="breadcrumb-item active">Détails</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white">Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Tontine</label>
                        <h5>{{ $subscription->tontine->name }}</h5>
                        <a href="{{ route('tontines.show', $subscription->tontine) }}" class="btn btn-sm btn-outline-primary">
                            Voir la tontine
                        </a>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Parfums commandés</label>
                        @foreach($subscription->items as $item)
                            <div class="mb-3 p-2 bg-light rounded">
                                @if($item->perfume->image_url)
                                    <img src="{{ Storage::url($item->perfume->image_url) }}"
                                         alt="{{ $item->perfume->name }}"
                                         class="img-fluid rounded mb-2"
                                         style="max-height: 100px;">
                                @endif
                                <h6 class="mb-1">{{ $item->perfume->name }}</h6>
                                <p class="text-muted small mb-1">{{ $item->perfume->brand }}</p>
                                <p class="mb-0">
                                    <strong>Quantité :</strong> {{ $item->quantity }} ×
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA =
                                    <span class="text-primary"><strong>{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</strong></span>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Date d'inscription</label>
                        <p>{{ $subscription->subscription_date->format('d F Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Statut</label>
                        <div>
                            @if($subscription->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($subscription->status === 'completed')
                                <span class="badge badge-secondary">Complétée</span>
                            @else
                                <span class="badge badge-danger">Annulée</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Montant total</label>
                        <h4 class="text-primary">{{ number_format($subscription->totalAmount(), 0, ',', ' ') }} FCFA</h4>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Montant payé</label>
                        <h5 class="text-success">{{ number_format($subscription->totalPaid(), 0, ',', ' ') }} FCFA</h5>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Reste à payer</label>
                        <h5 class="text-warning">{{ number_format($subscription->totalAmount() - $subscription->totalPaid(), 0, ',', ' ') }} FCFA</h5>
                    </div>

                    @php
                        $percentage = $subscription->totalAmount() > 0 ? ($subscription->totalPaid() / $subscription->totalAmount()) * 100 : 0;
                    @endphp
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                            {{ round($percentage) }}%
                        </div>
                    </div>

                    @if($subscription->status === 'active' && !$subscription->isFullyPaid())
                        <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="mt-3"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette inscription ? Cette action est irréversible.')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i data-feather="x-circle"></i> Annuler mon inscription
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Calendrier des Paiements</h5>
                    @php
                        $hasPendingPayments = $subscription->payments()->whereIn('status', ['pending', 'late'])->exists();
                    @endphp
                    @if($hasPendingPayments && $subscription->status === 'active')
                        <a href="{{ route('payments.pay-multiple', $subscription) }}" class="btn btn-primary">
                            <i data-feather="credit-card"></i> Payer plusieurs tranches
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Date d'échéance</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date de paiement</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscription->payments as $payment)
                                <tr class="{{ $payment->isLate() ? 'table-danger' : '' }}">
                                    <td><strong>#{{ $payment->payment_number }}</strong></td>
                                    <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                                    <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
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
                                        @if($payment->payment_date)
                                            {{ $payment->payment_date->format('d/m/Y H:i') }}
                                            @if($payment->payment_method)
                                                <br><small class="text-muted">{{ $payment->payment_method }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending' || $payment->status === 'late')
                                            <a href="{{ route('payments.pay', $payment) }}" class="btn btn-sm btn-primary">
                                                <i data-feather="credit-card"></i> Payer
                                            </a>
                                        @elseif($payment->status === 'paid')
                                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                                <i data-feather="eye"></i> Voir
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ number_format($subscription->payments->sum('amount'), 0, ',', ' ') }} FCFA</strong></td>
                                    <td colspan="3">
                                        @php
                                            $paidCount = $subscription->payments->where('status', 'paid')->count();
                                            $totalCount = $subscription->payments->count();
                                        @endphp
                                        <strong>{{ $paidCount }} / {{ $totalCount }} paiements effectués</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($subscription->isFullyPaid())
                        <div class="alert alert-success mt-3">
                            <h5><i data-feather="check-circle"></i> Félicitations !</h5>
                            <p class="mb-0">Vous avez complété tous vos paiements. Votre parfum vous sera remis prochainement.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
