@extends('layouts.erp')

@section('title', isset($projecte) ? 'Editar Projecte' : 'Nou Projecte')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ isset($projecte) ? 'Editar Projecte' : 'Nou Projecte' }}</div>
            <div
                class="page-subtitle">{{ isset($projecte) ? $projecte->codi_projecte : 'El codi es generarà automàticament' }}</div>
        </div>
    </div>

    <div class="card">
        <form action="{{ isset($projecte) ? route('projectes.update', $projecte) : route('projectes.store') }}"
              method="POST">
            @csrf
            @if(isset($projecte))
                @method('PUT')
            @endif

            <div class="form-grid">

                {{-- Codi (readonly a edit, ocult a create) --}}
                @if(isset($projecte))
                    <div class="form-group">
                        <label>Codi de projecte</label>
                        <input type="text" value="{{ $projecte->codi_projecte }}" readonly>
                    </div>
                @endif

                {{-- Client (només a create) --}}
                @if(!isset($projecte))
                    <div class="form-group">
                        <label for="client_id">Client *</label>
                        <select id="client_id" name="client_id" required>
                            <option value="">Selecciona un client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} ({{ $client->cif }}){{ !$client->actiu ? ' — INACTIU' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                        <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                @endif

                {{-- Gestor --}}
                <div class="form-group">
                    <label for="gestor_id">Gestor *</label>
                    <select id="gestor_id" name="gestor_id" required>
                        <option value="">Selecciona un gestor</option>
                        @foreach($gestors as $gestor)
                            <option value="{{ $gestor->id }}" {{ old('gestor_id', $projecte->gestor_id ?? '') == $gestor->id ? 'selected' : '' }}>
                                {{ $gestor->name }} ({{ $gestor->rol }})
                            </option>
                        @endforeach
                    </select>
                    @error('gestor_id')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group {{ isset($projecte) ? '' : '' }}">
                    <label for="nom">Nom del projecte *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $projecte->nom ?? '') }}" required>
                    @error('nom')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @if(isset($projecte))
                    <div class="form-group">
                        <label for="estat">Estat</label>
                        <select id="estat" name="estat">
                            @foreach(['PLANIFICACIO','EN_CURS','PAUSAT','FINALITZAT','CANCELAT'] as $estat)
                                <option
                                    value="{{ $estat }}" {{ old('estat', $projecte->estat) == $estat ? 'selected' : '' }}>
                                    {{ $estat }}
                                </option>
                            @endforeach
                        </select>
                        @error('estat')
                        <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                @endif

                <div class="form-group">
                    <label for="data_inici">Data d'inici</label>
                    <input type="date" id="data_inici" name="data_inici"
                           value="{{ old('data_inici', isset($projecte) ? $projecte->data_inici?->format('Y-m-d') : '') }}">
                    @error('data_inici')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="data_fi_prevista">Fi prevista</label>
                    <input type="date" id="data_fi_prevista" name="data_fi_prevista"
                           value="{{ old('data_fi_prevista', isset($projecte) ? $projecte->data_fi_prevista?->format('Y-m-d') : '') }}">
                    @error('data_fi_prevista')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="pressupost_hores_previstes">Hores previstes *</label>
                    <input type="number" id="pressupost_hores_previstes" name="pressupost_hores_previstes"
                           value="{{ old('pressupost_hores_previstes', $projecte->pressupost_hores_previstes ?? '') }}"
                           min="1" step="0.01" required placeholder="100">
                    @error('pressupost_hores_previstes')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label for="descripcio">Descripció</label>
                    <textarea id="descripcio" name="descripcio"
                              rows="3">{{ old('descripcio', $projecte->descripcio ?? '') }}</textarea>
                    @error('descripcio')
                    <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    {{ isset($projecte) ? 'Guardar canvis' : 'Crear projecte' }}
                </button>
                <a href="{{ isset($projecte) ? route('projectes.show', $projecte) : route('projectes.index') }}"
                   class="btn btn-ghost">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
@endsection
