@extends('layouts.erp')

@section('title', $ticket->codi_ticket)

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">{{ $ticket->titol }}</div>
            <div class="page-subtitle">{{ $ticket->codi_ticket }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('projectes.tickets.index', $projecte) }}" class="btn btn-ghost">← Tornar</a>
            <a href="{{ route('projectes.tickets.edit', [$projecte, $ticket]) }}" class="btn btn-ghost">Editar</a>
        </div>
    </div>

    <div class="card">
        <div class="field-grid">
            <div class="field">
                <span class="field-label">Projecte</span>
                <span class="field-value">{{ $projecte->nom }} ({{ $projecte->codi_projecte }})</span>
            </div>
            <div class="field">
                <span class="field-label">Estat</span>
                <span class="field-value"><span class="badge">{{ $ticket->estat }}</span></span>
            </div>
            <div class="field">
                <span class="field-label">Creador</span>
                <span class="field-value">{{ $ticket->creador->name }}</span>
            </div>
            @if($ticket->descripcio)
                <div class="field" style="grid-column: 1 / -1;">
                    <span class="field-label">Descripcio</span>
                    <span class="field-value">{{ $ticket->descripcio }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title">Nou comentari</div>
        <form action="{{ route('tickets.comentaris.store', $ticket) }}" method="POST">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            <div class="form-group">
                <label for="text">Text *</label>
                <textarea id="text" name="text" rows="3" required>{{ old('text') }}</textarea>
                @error('text')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            @error('ticket_id')
            <div class="form-error" style="margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Afegir comentari</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-title">Comentaris ({{ $ticket->comentaris->count() }})</div>
        @forelse($ticket->comentaris as $comentari)
            <div style="border-bottom: 1px solid var(--border); padding: 0.8rem 0;">
                <div style="display: flex; justify-content: space-between; gap: 1rem;">
                    <div style="font-size: 12px; color: var(--text-muted);">
                        {{ $comentari->autor->name }} · {{ $comentari->created_at->format('d/m/Y H:i') }}
                    </div>
                    <form action="{{ route('comentaris.destroy', $comentari) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm">Eliminar</button>
                    </form>
                </div>
                <div style="margin-top: 0.45rem;">{{ $comentari->text }}</div>
            </div>
        @empty
            <div class="empty">Encara no hi ha comentaris.</div>
        @endforelse
    </div>
@endsection

