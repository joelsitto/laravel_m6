@extends('layouts.erp')

@section('title', isset($client) ? 'Editar Client' : 'Nou Client')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ isset($client) ? 'Editar Client' : 'Nou Client' }}</div>
            <div
                class="page-subtitle">{{ isset($client) ? $client->cif : 'Omple el formulari per crear un nou client' }}</div>
        </div>
    </div>

    <div class="card">
        <form action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nom de l'empresa *</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $client->nombre ?? '') }}"
                           required>
                    @error('nombre')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="cif">CIF / NIF *</label>
                    <input type="text" id="cif" name="cif" value="{{ old('cif', $client->cif ?? '') }}" required
                           placeholder="B12345678">
                    @error('cif')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email_contacte">Email de contacte *</label>
                    <input type="email" id="email_contacte" name="email_contacte"
                           value="{{ old('email_contacte', $client->email_contacte ?? '') }}" required>
                    @error('email_contacte')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="telefon">Telèfon</label>
                    <input type="text" id="telefon" name="telefon" value="{{ old('telefon', $client->telefon ?? '') }}"
                           placeholder="+34 600 000 000">
                    @error('telefon')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label for="direccio">Adreça</label>
                    <textarea id="direccio" name="direccio">{{ old('direccio', $client->direccio ?? '') }}</textarea>
                    @error('direccio')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @if(isset($client))
                    <div class="form-group">
                        <label for="actiu">Estat</label>
                        <select id="actiu" name="actiu">
                            <option value="1" {{ old('actiu', $client->actiu) ? 'selected' : '' }}>Actiu</option>
                            <option value="0" {{ !old('actiu', $client->actiu) ? 'selected' : '' }}>Inactiu</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    {{ isset($client) ? 'Guardar canvis' : 'Crear client' }}
                </button>
                <a href="{{ isset($client) ? route('clients.show', $client) : route('clients.index') }}"
                   class="btn btn-ghost">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
@endsection
