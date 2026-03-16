@extends('layouts.erp')

@section('title', $client->nom)

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ $client->nom }}</div>
            <div class="page-subtitle">{{ $client->cif }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('clients.index') }}" class="btn btn-ghost">← Tornar</a>
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost">Editar</a>
            <form action="{{ route('clients.update', $client) }}" method="POST" style="display:inline;">
                @csrf @method('PUT')
                <input type="hidden" name="nom" value="{{ $client->nom }}">
                <input type="hidden" name="cif" value="{{ $client->cif }}">
                <input type="hidden" name="email_contacte" value="{{ $client->email_contacte }}">
                <input type="hidden" name="actiu" value="{{ $client->actiu ? 0 : 1 }}">
                <button type="submit" class="btn {{ $client->actiu ? 'btn-danger' : 'btn-ghost' }} btn-sm">
                    {{ $client->actiu ? 'Desactivar' : 'Activar' }}
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Informació del client</div>
        <div class="field-grid">
            <div class="field">
                <span class="field-label">Nom</span>
                <span class="field-value">{{ $client->nom }}</span>
            </div>
            <div class="field">
                <span class="field-label">CIF</span>
                <span class="field-value" style="font-family: var(--mono);">{{ $client->cif }}</span>
            </div>
            <div class="field">
                <span class="field-label">Email</span>
                <span class="field-value">{{ $client->email_contacte }}</span>
            </div>
            <div class="field">
                <span class="field-label">Telèfon</span>
                <span class="field-value">{{ $client->telefon ?? '—' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Estat</span>
                <span class="field-value">
                @if($client->actiu)
                        <span class="badge badge-actiu">Actiu</span>
                    @else
                        <span class="badge badge-inactiu">Inactiu</span>
                    @endif
            </span>
            </div>
            @if($client->direccio)
                <div class="field">
                    <span class="field-label">Adreça</span>
                    <span class="field-value">{{ $client->direccio }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Codi</th>
                <th>Nom</th>
                <th>Estat</th>
                <th>Inici</th>
                <th>Fi prevista</th>
            </tr>
            </thead>
            <tbody>
            @forelse($client->projectes as $projecte)
                <tr>
                    <td><a href="{{ route('projectes.show', $projecte) }}"
                           style="font-family: var(--mono); font-size: 12px; color: var(--accent); text-decoration: none;">{{ $projecte->codi_projecte }}</a>
                    </td>
                    <td>{{ $projecte->nom }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($projecte->estat) }}">{{ $projecte->estat }}</span>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--text-muted);">{{ $projecte->data_inici?->format('d/m/Y') ?? '—' }}</td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--text-muted);">{{ $projecte->data_fi_prevista?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty">Cap projecte per a aquest client.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection

