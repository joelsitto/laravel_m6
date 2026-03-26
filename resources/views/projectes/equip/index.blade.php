@extends('layouts.erp')

@section('title', 'Equip del projecte')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Equip del projecte</div>
            <div class="page-subtitle">{{ $projecte->nom }} ({{ $projecte->codi_projecte }})</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('projectes.show', $projecte) }}" class="btn btn-ghost">← Tornar</a>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Membres actuals ({{ $projecte->desenvolupadors->count() }})</div>

        @forelse($projecte->desenvolupadors as $dev)
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding:0.6rem 0;">
                <div>
                    <div style="font-weight:500;">{{ $dev->name }}</div>
                    <div style="font-size:12px; color:var(--text-muted);">{{ $dev->email }}</div>
                </div>

                <form method="POST" action="{{ route('projectes.equip.destroy', [$projecte, $dev]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm">Treure</button>
                </form>
            </div>
        @empty
            <div class="empty" style="padding:1rem 0;">Aquest projecte encara no te DEVs assignats.</div>
        @endforelse
    </div>

    <div class="card">
        <div class="card-title">Afegir DEV a l'equip</div>

        <form method="POST" action="{{ route('projectes.equip.store', $projecte) }}">
            @csrf

            <div class="form-group" style="max-width:460px;">
                <label for="user_id">Usuari DEV *</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Selecciona un DEV...</option>
                    @foreach($devsDisponibles as $devDisponible)
                        <option value="{{ $devDisponible->id }}" {{ (string) old('user_id') === (string) $devDisponible->id ? 'selected' : '' }}>
                            {{ $devDisponible->name }} ({{ $devDisponible->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Afegir membre</button>
            </div>
        </form>
    </div>
@endsection

