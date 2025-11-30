@extends('layouts.app')

@section('title', 'Modifier ' . $perfume->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Modifier un Parfum</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('perfumes.index') }}">Parfums</a></li>
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
                    <form action="{{ route('perfumes.update', $perfume) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="supplier_id">Fournisseur <span class="text-danger">*</span></label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                        <option value="">Sélectionnez un fournisseur</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ (old('supplier_id', $perfume->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="name">Nom du parfum <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $perfume->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="brand">Marque <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                           id="brand" name="brand" value="{{ old('brand', $perfume->brand) }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4">{{ old('description', $perfume->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="price">Prix (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $perfume->price) }}"
                                           min="0" step="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="stock_quantity">Quantité en stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $perfume->stock_quantity) }}"
                                           min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($perfume->image_url)
                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    <div>
                                        <img src="{{ Storage::url($perfume->image_url) }}" alt="{{ $perfume->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label" for="image_url">Nouvelle image (optionnel)</label>
                                    <input type="file" class="form-control @error('image_url') is-invalid @enderror"
                                           id="image_url" name="image_url" accept="image/*">
                                    <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_available"
                                               name="is_available" value="1" {{ old('is_available', $perfume->is_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">
                                            Disponible à la vente
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <img id="imagePreview" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 200px; display: none;">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Mettre à jour
                                </button>
                                <a href="{{ route('perfumes.show', $perfume) }}" class="btn btn-secondary">
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
document.getElementById('image_url').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
