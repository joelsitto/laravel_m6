@extends('layouts.erp')

@section('title', 'Editar Ticket')

@section('content')
    @can('update', $ticket)
    <div class="page-header">
        <div>
            <div class="page-title">Editar Ticket</div>
            <div class="page-subtitle">{{ $ticket->codi_ticket }}</div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('projectes.tickets.update', [$projecte, $ticket]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full">
                    <label for="titol">Titol *</label>
                    <input id="titol" name="titol" type="text" value="{{ old('titol', $ticket->titol) }}" required>
                    @error('titol')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ticket_pare_id">Ticket pare (opcional)</label>
                    <select id="ticket_pare_id" name="ticket_pare_id">
                        <option value="">Sense pare</option>
                        @foreach($ticketsPareDisponibles as $ticketPare)
                            <option value="{{ $ticketPare->id }}" {{ (string) old('ticket_pare_id', $ticket->ticket_pare_id) === (string) $ticketPare->id ? 'selected' : '' }}>
                                {{ $ticketPare->codi_ticket }} - {{ $ticketPare->titol }}
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_pare_id')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group full">
                    <label for="descripcio">Descripcio</label>
                    <textarea id="descripcio" name="descripcio" rows="4">{{ old('descripcio', $ticket->descripcio) }}</textarea>
                    @error('descripcio')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar canvis</button>
                <a href="{{ route('projectes.tickets.show', [$projecte, $ticket]) }}" class="btn btn-ghost">Cancel lar</a>
            </div>
        </form>
    </div>
    @endcan
@endsection

