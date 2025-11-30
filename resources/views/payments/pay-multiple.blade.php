@extends('layouts.app')

@section('title', 'Payer plusieurs tranches')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Payer plusieurs tranches</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Mes Inscriptions</a></li>
                <li class="breadcrumb-item"><a href="{{ route('subscriptions.show', $subscription) }}">Détails</a></li>
                <li class="breadcrumb-item active">Paiement Multiple</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white">Récapitulatif</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Tontine</label>
                        <h6>{{ $subscription->tontine->name }}</h6>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Parfums commandés</label>
                        @foreach($subscription->items as $item)
                            <div class="mb-1">
                                <strong>{{ $item->perfume->name }}</strong> ({{ $item->quantity }}x)
                                <br><small class="text-muted">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</small>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Montant total de la commande</label>
                        <h5>{{ number_format($subscription->totalAmount(), 0, ',', ' ') }} FCFA</h5>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Déjà payé</label>
                        <p class="text-success mb-0">{{ number_format($subscription->totalPaid(), 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Reste à payer</label>
                        <p class="text-warning mb-0">{{ number_format($subscription->totalAmount() - $subscription->totalPaid(), 0, ',', ' ') }} FCFA</p>
                    </div>

                    <hr>

                    <div id="selected-summary" class="alert alert-info">
                        <h6><i data-feather="info"></i> Tranches sélectionnées</h6>
                        <div id="selected-count">Aucune tranche sélectionnée</div>
                        <div id="selected-total" class="h5 mb-0 mt-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Sélectionnez les tranches à payer</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.process-multiple', $subscription) }}" method="POST" id="payment-form">
                        @csrf

                        <div class="alert alert-warning mb-4">
                            <i data-feather="info"></i>
                            <strong>Astuce :</strong> Cochez les tranches que vous souhaitez payer en une seule fois.
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="select-all">
                                                <label class="form-check-label" for="select-all"></label>
                                            </div>
                                        </th>
                                        <th>Tranche</th>
                                        <th>Date d'échéance</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                    <tr class="{{ $payment->isLate() ? 'table-danger' : '' }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input payment-checkbox"
                                                       type="checkbox"
                                                       name="payment_ids[]"
                                                       value="{{ $payment->id }}"
                                                       id="payment_{{ $payment->id }}"
                                                       data-amount="{{ $payment->amount }}">
                                                <label class="form-check-label" for="payment_{{ $payment->id }}"></label>
                                            </div>
                                        </td>
                                        <td><strong>#{{ $payment->payment_number }}</strong></td>
                                        <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                                        <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>
                                            @if($payment->isLate())
                                                <span class="badge badge-danger">
                                                    <i data-feather="alert-triangle"></i> En retard
                                                </span>
                                            @else
                                                <span class="badge badge-warning">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @error('payment_ids')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <hr>

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

                                <div class="alert alert-warning">
                                    <h6><i data-feather="alert-circle"></i> Important</h6>
                                    <ul class="mb-0">
                                        <li>Sélectionnez au moins une tranche à payer</li>
                                        <li>Assurez-vous d'avoir effectué le paiement du montant total avant de valider</li>
                                        <li>Conservez votre preuve de paiement jusqu'à confirmation</li>
                                        <li>En cas de problème, contactez l'administration avec votre référence</li>
                                    </ul>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="confirmPayment" required>
                                    <label class="form-check-label" for="confirmPayment">
                                        Je confirme avoir effectué ce paiement de <strong id="confirm-amount">0 FCFA</strong>
                                    </label>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
                                        <i data-feather="check-circle"></i> Confirmer les paiements
                                    </button>
                                    <a href="{{ route('subscriptions.show', $subscription) }}"
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    const selectedCountDiv = document.getElementById('selected-count');
    const selectedTotalDiv = document.getElementById('selected-total');
    const confirmAmountSpan = document.getElementById('confirm-amount');
    const submitBtn = document.getElementById('submit-btn');

    function updateSummary() {
        const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
        const count = checkedBoxes.length;
        let total = 0;

        checkedBoxes.forEach(checkbox => {
            total += parseFloat(checkbox.dataset.amount);
        });

        if (count === 0) {
            selectedCountDiv.textContent = 'Aucune tranche sélectionnée';
            selectedTotalDiv.textContent = '';
            submitBtn.disabled = true;
        } else {
            selectedCountDiv.textContent = count + ' tranche(s) sélectionnée(s)';
            selectedTotalDiv.textContent = 'Total : ' + total.toLocaleString('fr-FR') + ' FCFA';
            submitBtn.disabled = false;
        }

        confirmAmountSpan.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    }

    // Select/deselect all
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSummary();
    });

    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            updateSummary();
        });
    });

    // Initialize
    updateSummary();
});
</script>
@endpush
