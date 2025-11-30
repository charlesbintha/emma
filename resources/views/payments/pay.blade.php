@extends('layouts.app')

@section('title', 'Effectuer un paiement')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Effectuer un paiement</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Mes Inscriptions</a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.show', $payment->tontineSubscription) }}">Détails</a></li>
                <li class="breadcrumb-item active">Paiement</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white">Détails du paiement</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Tontine</label>
                        <h6>{{ $payment->tontineSubscription->tontine->name }}</h6>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Parfums commandés</label>
                        @foreach($payment->tontineSubscription->items as $item)
                            <div class="mb-1">
                                <strong>{{ $item->perfume->name }}</strong> ({{ $item->quantity }}x)
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Numéro de paiement</label>
                        <h5><span class="badge badge-primary">#{{ $payment->payment_number }}</span></h5>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Date d'échéance</label>
                        <p>{{ $payment->due_date->format('d F Y') }}</p>
                        @if($payment->isLate())
                            <span class="badge badge-danger">
                                <i data-feather="alert-triangle"></i> Paiement en retard
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Montant à payer</label>
                        <h3 class="text-primary">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</h3>
                    </div>

                    <div class="alert alert-info">
                        <i data-feather="info"></i>
                        <small>Après validation, votre paiement sera enregistré et vous recevrez un reçu.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informations de paiement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.process', $payment) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-method-card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_method" id="mobile_money"
                                                               value="mobile_money" required>
                                                        <label class="form-check-label w-100" for="mobile_money">
                                                            <div class="d-flex align-items-center">
                                                                <i data-feather="smartphone" class="me-3 text-primary" style="width: 32px; height: 32px;"></i>
                                                                <div>
                                                                    <h6 class="mb-0">Mobile Money</h6>
                                                                    <small class="text-muted">Orange Money, MTN, Moov</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-method-card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_method" id="bank_transfer"
                                                               value="bank_transfer" required>
                                                        <label class="form-check-label w-100" for="bank_transfer">
                                                            <div class="d-flex align-items-center">
                                                                <i data-feather="credit-card" class="me-3 text-success" style="width: 32px; height: 32px;"></i>
                                                                <div>
                                                                    <h6 class="mb-0">Virement Bancaire</h6>
                                                                    <small class="text-muted">Transfert bancaire</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-method-card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_method" id="cash"
                                                               value="cash" required>
                                                        <label class="form-check-label w-100" for="cash">
                                                            <div class="d-flex align-items-center">
                                                                <i data-feather="dollar-sign" class="me-3 text-warning" style="width: 32px; height: 32px;"></i>
                                                                <div>
                                                                    <h6 class="mb-0">Espèces</h6>
                                                                    <small class="text-muted">Paiement en liquide</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="card payment-method-card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_method" id="check"
                                                               value="check" required>
                                                        <label class="form-check-label w-100" for="check">
                                                            <div class="d-flex align-items-center">
                                                                <i data-feather="file-text" class="me-3 text-info" style="width: 32px; height: 32px;"></i>
                                                                <div>
                                                                    <h6 class="mb-0">Chèque</h6>
                                                                    <small class="text-muted">Paiement par chèque</small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="reference">Référence de transaction</label>
                                    <input type="text" class="form-control @error('reference') is-invalid @enderror"
                                           id="reference" name="reference"
                                           placeholder="Ex: OM123456789 ou numéro de chèque"
                                           value="{{ old('reference') }}">
                                    <small class="form-text text-muted">
                                        Entrez le numéro de transaction, référence de virement, ou numéro de chèque
                                    </small>
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="notes">Notes (optionnel)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes" name="notes" rows="3"
                                              placeholder="Informations supplémentaires sur ce paiement">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i data-feather="alert-circle"></i> Important</h6>
                                    <ul class="mb-0">
                                        <li>Assurez-vous d'avoir effectué le paiement avant de valider</li>
                                        <li>Conservez votre preuve de paiement jusqu'à confirmation</li>
                                        <li>En cas de problème, contactez l'administration avec votre référence</li>
                                    </ul>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="confirmPayment" required>
                                    <label class="form-check-label" for="confirmPayment">
                                        Je confirme avoir effectué ce paiement de <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong>
                                    </label>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i data-feather="check-circle"></i> Confirmer le paiement
                                    </button>
                                    <a href="{{ route('subscriptions.show', $payment->tontineSubscription) }}"
                                       class="btn btn-secondary btn-lg">
                                        <i data-feather="x"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-method-card {
    transition: all 0.3s;
    cursor: pointer;
    border: 2px solid #e0e0e0;
}
.payment-method-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.form-check-input:checked ~ .form-check-label .payment-method-card {
    border-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}
</style>
@endpush
