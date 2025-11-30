@extends('layouts.app')

@section('title', 'Modifier ' . $supplier->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Modifier un Fournisseur</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Fournisseurs</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5>Formulaire de modification</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="name">Nom du fournisseur <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $supplier->name) }}"
                                   placeholder="Ex: Parfums de France" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $supplier->email) }}"
                                   placeholder="contact@fournisseur.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Téléphone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                   placeholder="+225 XX XX XX XX XX">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="address">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3"
                                      placeholder="Adresse complète du fournisseur">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($supplier->perfumes()->count() > 0)
                        <div class="alert alert-info">
                            <i data-feather="info"></i>
                            Ce fournisseur a <strong>{{ $supplier->perfumes()->count() }} parfum(s)</strong> associé(s).
                        </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary">
                                <i data-feather="x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
