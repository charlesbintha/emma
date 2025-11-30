@extends('layouts.app')

@section('title', 'Créer une Tontine')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Créer une Tontine</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tontines.index') }}">Tontines</a></li>
                <li class="breadcrumb-item active">Créer</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Formulaire de création</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tontines.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Nom de la tontine <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Ex: Tontine Parfums Premium 2024" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder="Description de la tontine...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="start_date">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    <small class="form-text text-muted">
                                        Date du 1er versement (souvent le 5 ou le 20 du mois)
                                    </small>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="end_date">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    <small class="form-text text-muted">
                                        Date du 4ème versement (45 jours après le début)
                                    </small>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Les tontines "En attente" ne sont pas encore ouvertes aux inscriptions
                                    </small>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i data-feather="info"></i> Calendrier des paiements (45 jours)</h6>
                                    <p class="mb-1">La tontine dure <strong>1 mois et 15 jours</strong>. Les paiements sont répartis ainsi :</p>
                                    <ul class="mb-0" id="payment-dates-preview">
                                        <li><strong>1er versement</strong> : Date de début (Jour 0)</li>
                                        <li><strong>2ème versement</strong> : 15 jours après (Jour 15)</li>
                                        <li><strong>3ème versement</strong> : 30 jours après (Jour 30)</li>
                                        <li><strong>4ème versement</strong> : 45 jours après - Date de fin (Jour 45)</li>
                                    </ul>
                                    <p class="mb-0 mt-2 text-muted small">
                                        <i class="fa fa-lightbulb"></i> Astuce : Commencez le 5 du mois pour avoir les échéances le 5, 20, 5, 20.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Créer la tontine
                                </button>
                                <a href="{{ route('tontines.index') }}" class="btn btn-secondary">
                                    <i data-feather="x"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const paymentPreview = document.getElementById('payment-dates-preview');

    function formatDate(date) {
        const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
        return date.toLocaleDateString('fr-FR', options);
    }

    function addDays(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    function updateEndDate() {
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = addDays(startDate, 45);

            // Mettre à jour le champ date de fin
            endDateInput.value = endDate.toISOString().split('T')[0];

            // Calculer et afficher les dates de paiement
            const date1 = startDate;
            const date2 = addDays(startDate, 15);
            const date3 = addDays(startDate, 30);
            const date4 = endDate;

            paymentPreview.innerHTML = `
                <li><strong>1er versement</strong> : ${formatDate(date1)} (Jour 0)</li>
                <li><strong>2ème versement</strong> : ${formatDate(date2)} (Jour 15)</li>
                <li><strong>3ème versement</strong> : ${formatDate(date3)} (Jour 30)</li>
                <li><strong>4ème versement</strong> : ${formatDate(date4)} (Jour 45)</li>
            `;
        }
    }

    startDateInput.addEventListener('change', updateEndDate);

    // Si une date est déjà définie, mettre à jour
    if (startDateInput.value) {
        updateEndDate();
    }
});
</script>
@endpush
