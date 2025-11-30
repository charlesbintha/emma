@extends('layouts.app')

@section('title', 'Modifier ' . $tontine->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Modifier une Tontine</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tontines.index') }}">Tontines</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Formulaire de modification</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tontines.update', $tontine) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Nom de la tontine <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $tontine->name) }}"
                                           placeholder="Ex: Tontine Parfums Premium 2024" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder="Description de la tontine...">{{ old('description', $tontine->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="start_date">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date"
                                           value="{{ old('start_date', $tontine->start_date->format('Y-m-d')) }}" required>
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
                                           id="end_date" name="end_date"
                                           value="{{ old('end_date', $tontine->end_date ? $tontine->end_date->format('Y-m-d') : '') }}" required>
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
                                        <option value="pending" {{ old('status', $tontine->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="active" {{ old('status', $tontine->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $tontine->status) === 'completed' ? 'selected' : '' }}>Complétée</option>
                                        <option value="cancelled" {{ old('status', $tontine->status) === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
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
                                        @php
                                            $dates = $tontine->getPaymentDueDates();
                                        @endphp
                                        @if(count($dates) > 0)
                                            <li><strong>1er versement</strong> : {{ $dates[1]->format('D d M Y') }} (Jour 0)</li>
                                            <li><strong>2ème versement</strong> : {{ $dates[2]->format('D d M Y') }} (Jour 15)</li>
                                            <li><strong>3ème versement</strong> : {{ $dates[3]->format('D d M Y') }} (Jour 30)</li>
                                            <li><strong>4ème versement</strong> : {{ $dates[4]->format('D d M Y') }} (Jour 45)</li>
                                        @else
                                            <li><strong>1er versement</strong> : Date de début (Jour 0)</li>
                                            <li><strong>2ème versement</strong> : 15 jours après (Jour 15)</li>
                                            <li><strong>3ème versement</strong> : 30 jours après (Jour 30)</li>
                                            <li><strong>4ème versement</strong> : 45 jours après - Date de fin (Jour 45)</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @if($tontine->subscriptions()->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6><i data-feather="alert-triangle"></i> Attention</h6>
                                    <p class="mb-0">Cette tontine a déjà <strong>{{ $tontine->subscriptions()->count() }} inscription(s)</strong>. Modifier certains paramètres peut affecter les paiements existants.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Mettre à jour
                                </button>
                                <a href="{{ route('tontines.show', $tontine) }}" class="btn btn-secondary">
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
