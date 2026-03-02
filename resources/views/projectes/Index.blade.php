@extends('layouts.erp')

@section('title', 'Projectes')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Projectes</div>
            <div class="page-subtitle">{{ $projectes->total() }} registres</div>
        </div>
        <a href="{{ route('projectes.create') }}" class="btn btn-primary">+ Nou Projecte</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Codi</th>
                <th>Nom</th>
                <th>Client</th>
                <th>Estat</th>
                <th>Gestor</th>
                <th>Inici</th>
                <th>Fi prevista</th>
                <th>Hores est.</th>
                <th>Hores reals</th>
                <th>Accions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($projectes as $projecte)
                <tr>
                    <td>
                        <a href="{{ route('projectes.show', $projecte) }}"
                           style="font-family: var(--mono); font-size: 12px; color: var(--accent); text-decoration: none;">
                            {{ $projecte->codi_projecte }}
                        </a>
                    </td>
                    <td style="font-weight: 500;">{{ $projecte->nom }}</td>
                    <td style="font-size: 13px; color: var(--text-muted);">{{ $projecte->client->nombre }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($projecte->estat) }}">{{ $projecte->estat }}</span>
                    </td>
                    <td style="font-size: 13px;">{{ $projecte->gestor->name }}</td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--text-muted);">{{ $projecte->data_inici?->format('d/m/Y') ?? '—' }}</td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--text-muted);">{{ $projecte->data_fi_prevista?->format('d/m/Y') ?? '—' }}</td>
                    <td style="font-family: var(--mono); font-size: 12px;">{{ $projecte->pressupost_hores_estimades }}
                        h
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px; color: {{ $projecte->pressupost_hores_reals > $projecte->pressupost_hores_estimades ? 'var(--danger)' : 'var(--success)' }}">
                        {{ $projecte->pressupost_hores_reals }}h
                    </td>
                    <td>
                        <a href="{{ route('projectes.show', $projecte) }}" class="btn btn-ghost btn-sm">Veure</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <div class="empty">Cap projecte trobat.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $projectes->appends(request()->query())->links() }}
    </div>
@endsection
