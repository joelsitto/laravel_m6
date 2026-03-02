@extends('layouts.erp')

@section('title', 'Clients')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Clients</div>
            <div class="page-subtitle">{{ $clients->total() }} registres</div>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">+ Crear Client</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Nom</th>
                <th>CIF</th>
                <th>Email</th>
                <th>Telèfon</th>
                <th>Projectes</th>
                <th>Estat</th>
                <th>Accions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
                <tr>
                    <td><a href="{{ route('clients.show', $client) }}"
                           style="color: var(--text); text-decoration: none; font-weight: 500;">{{ $client->nombre }}</a>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--text-muted);">{{ $client->cif }}</td>
                    <td style="font-size: 13px;">{{ $client->email_contacte }}</td>
                    <td style="font-size: 13px; color: var(--text-muted);">{{ $client->telefon ?? '—' }}</td>
                    <td style="font-family: var(--mono); font-size: 13px;">{{ $client->projectes_count }}</td>
                    <td>
                        @if($client->actiu)
                            <span class="badge badge-actiu">Actiu</span>
                        @else
                            <span class="badge badge-inactiu">Inactiu</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-ghost btn-sm">Veure</a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty">Cap client trobat.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $clients->links() }}
    </div>
@endsection
