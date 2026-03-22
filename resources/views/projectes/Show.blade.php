@extends('layouts.erp')

@section('title', $projecte->codi_projecte)

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ $projecte->nom }}</div>
            <div class="page-subtitle">{{ $projecte->codi_projecte }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('projectes.index') }}" class="btn btn-ghost">← Tornar</a>
            <a href="{{ route('projectes.tickets.index', $projecte) }}" class="btn btn-ghost">Tickets</a>
            @can('update', $projecte)
                @if(!in_array($projecte->estat, ['FINALITZAT','CANCELAT']))
                    <a href="{{ route('projectes.edit', $projecte) }}" class="btn btn-ghost">Editar</a>
                @endif
            @endcan
        </div>
    </div>

    {{-- INFO GENERAL --}}
    <div class="card">
        <div class="card-title">Informació general</div>
        <div class="field-grid">
            <div class="field">
                <span class="field-label">Codi</span>
                <span class="field-value"
                      style="font-family: var(--mono); color: var(--accent);">{{ $projecte->codi_projecte }}</span>
            </div>
            <div class="field">
                <span class="field-label">Estat</span>
                <span class="field-value">
                <span class="badge badge-{{ strtolower($projecte->estat) }}">{{ $projecte->estat }}</span>
            </span>
            </div>
            <div class="field">
                <span class="field-label">Client</span>
                <span class="field-value">
                @can('view', $projecte->client)
                    <a href="{{ route('clients.show', $projecte->client) }}"
                       style="color: var(--text); text-decoration: none;">
                        {{ $projecte->client->nom }}
                    </a>
                @else
                    {{ $projecte->client->nom }}
                @endcan
                <div
                    style="font-size: 12px; color: var(--text-muted); font-family: var(--mono);">{{ $projecte->client->cif }}</div>
            </span>
            </div>
            <div class="field">
                <span class="field-label">Gestor</span>
                <span class="field-value">{{ $projecte->gestor->name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Data d'inici</span>
                <span class="field-value"
                      style="font-family: var(--mono);">{{ $projecte->data_inici?->format('d/m/Y') ?? '—' }}</span>
            </div>
            @if($projecte->data_fi_prevista)
                <div class="field">
                    <span class="field-label">Fi prevista</span>
                    <span class="field-value" style="font-family: var(--mono);">{{ $projecte->data_fi_prevista->format('d/m/Y') }}</span>
                </div>
            @endif
            <div class="field">
                <span class="field-label">Hores previstes</span>
                <span class="field-value" style="font-family: var(--mono);">{{ $projecte->pressupost_hores_previstes }}h</span>
            </div>
            @if($projecte->descripcio)
                <div class="field" style="grid-column: 1 / -1;">
                    <span class="field-label">Descripció</span>
                    <span class="field-value" style="color: var(--text-muted);">{{ $projecte->descripcio }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- CANVI D'ESTAT --}}
    @php
        $transicions = [
            'PLANIFICACIO' => ['EN_CURS', 'CANCELAT'],
            'EN_CURS'      => ['PAUSAT', 'FINALITZAT', 'CANCELAT'],
            'PAUSAT'       => ['EN_CURS', 'CANCELAT'],
            'FINALITZAT'   => [],
            'CANCELAT'     => [],
        ];
        $possibles = $transicions[$projecte->estat] ?? [];
    @endphp

    @can('update', $projecte)
    @if(count($possibles) > 0)
        <div class="card">
            <div class="card-title">Canviar estat</div>
            <div class="btn-group">
                @foreach($possibles as $nouEstat)
                    <form action="{{ route('projectes.canviarEstat', $projecte) }}" method="POST"
                          style="display:inline;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="estat" value="{{ $nouEstat }}">
                        <button type="submit" class="btn btn-ghost btn-sm">
                            → {{ $nouEstat }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif
    @endcan

    {{-- EQUIP --}}
    @if($projecte->desenvolupadors->count() > 0)
        <div class="card">
            <div class="card-title">Equip ({{ $projecte->desenvolupadors->count() }})</div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                @foreach($projecte->desenvolupadors as $dev)
                    <span
                        style="background: var(--border); padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 12px;">{{ $dev->name }}</span>
                @endforeach
            </div>
        </div>
    @endif
@endsection
