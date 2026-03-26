@extends('layouts.erp')

@section('title', 'Nou Ticket')

@section('content')
    @can('create', [App\Models\Ticket::class, $projecte])
    <div class="page-header">
        <div>
            <div class="page-title">Nou Ticket</div>
            <div class="page-subtitle">Projecte {{ $projecte->codi_projecte }}</div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('projectes.tickets.store', $projecte) }}" method="POST">
            @csrf
            <input type="hidden" name="projecte_id" value="{{ $projecte->id }}">

            <div class="form-grid">
                <div class="form-group full">
                    <label for="titol">Titol *</label>
                    <input id="titol" name="titol" type="text" value="{{ old('titol') }}" required>
                    @error('titol')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ticket_pare_id">Ticket pare (opcional)</label>
                    <select id="ticket_pare_id" name="ticket_pare_id">
                        <option value="">Sense pare</option>
                        @foreach($ticketsPareDisponibles as $ticketPare)
                            <option value="{{ $ticketPare->id }}" {{ (string) old('ticket_pare_id') === (string) $ticketPare->id ? 'selected' : '' }}>
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
                    <textarea id="descripcio" name="descripcio" rows="4">{{ old('descripcio') }}</textarea>
                    @error('descripcio')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @error('projecte_id')
            <div class="form-error" style="margin-top: 0.5rem;">{{ $message }}</div>
            @enderror

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Crear ticket</button>
                <a href="{{ route('projectes.tickets.index', $projecte) }}" class="btn btn-ghost">Cancel lar</a>
            </div>
        </form>
    </div>
    @endcan
@endsection

