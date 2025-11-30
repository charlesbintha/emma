@extends('layouts.app')

@section('title', $perfume->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $perfume->name }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perfumes.index') }}">Parfums</a></li>
                <li class="breadcrumb-item active">{{ $perfume->name }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    @if($perfume->image_url)
                        <img src="{{ Storage::url($perfume->image_url) }}" alt="{{ $perfume->name }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                    @else
                        <div class="bg-light p-5 rounded mb-3">
                            <i data-feather="package" style="width: 100px; height: 100px;" class="text-muted"></i>
                        </div>
                    @endif

                    <h3 class="mb-2">{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</h3>

                    @if($perfume->is_available && $perfume->stock_quantity > 0)
                        <span class="badge badge-success mb-3">
                            <i data-feather="check-circle"></i> En stock ({{ $perfume->stock_quantity }} unités)
                        </span>
                    @else
                        <span class="badge badge-danger mb-3">
                            <i data-feather="x-circle"></i> Rupture de stock
                        </span>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('perfumes.edit', $perfume) }}" class="btn btn-primary">
                            <i data-feather="edit"></i> Modifier ce parfum
                        </a>
                        <form action="{{ route('perfumes.destroy', $perfume) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce parfum ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i data-feather="trash-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Détails du parfum</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $perfume->name }}</h4>
                    <h6 class="text-muted mb-4">{{ $perfume->brand }}</h6>

                    <div class="mb-4">
                        <h6><strong>Description</strong></h6>
                        <p>{{ $perfume->description ?: 'Aucune description disponible' }}</p>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Fournisseur</label>
                            <p><strong>{{ $perfume->supplier->name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Prix</label>
                            <p><strong>{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Stock disponible</label>
                            <p><strong>{{ $perfume->stock_quantity }} unités</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Statut</label>
                            <p>
                                @if($perfume->is_available)
                                    <span class="badge badge-success">Disponible</span>
                                @else
                                    <span class="badge badge-secondary">Non disponible</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($perfume->tontineSubscriptions->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Inscriptions aux tontines</h5>
                </div>
                <div class="card-body">
                    <p>Ce parfum a été choisi par <strong>{{ $perfume->tontineSubscriptions->count() }}</strong> participant(s) dans des tontines.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
