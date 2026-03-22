@extends('layouts.erp')

@section('title', 'Tickets')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Tickets de {{ $projecte->nom }}</div>
            <div class="page-subtitle">{{ $tickets->total() }} registres</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('projectes.show', $projecte) }}" class="btn btn-ghost">← Tornar al projecte</a>
            @can('create', [App\Models\Ticket::class, $projecte])
                <a href="{{ route('projectes.tickets.create', $projecte) }}" class="btn btn-primary">+ Nou Ticket</a>
            @endcan
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Codi</th>
                <th>Titol</th>
                <th>Estat</th>
                <th>Creador</th>
                <th>Comentaris</th>
                <th>Accions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td style="font-family: var(--mono);">{{ $ticket->codi_ticket }}</td>
                    <td>{{ $ticket->titol }}</td>
                    <td>
                        <span class="badge">{{ $ticket->estat }}</span>
                    </td>
                    <td>{{ $ticket->creador->name }}</td>
                    <td style="font-family: var(--mono);">{{ $ticket->comentaris_count }}</td>
                    <td>
                        <a href="{{ route('projectes.tickets.show', [$projecte, $ticket]) }}" class="btn btn-ghost btn-sm">Veure</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"><div class="empty">Cap ticket trobat.</div></td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $tickets->links() }}
    </div>
@endsection

