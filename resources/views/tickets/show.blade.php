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
            @can('update', $ticket)
                <a href="{{ route('projectes.tickets.edit', [$projecte, $ticket]) }}" class="btn btn-ghost">Editar</a>
            @endcan
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
            <div class="field">
                <span class="field-label">Assignat a</span>
                <span class="field-value">{{ $ticket->assignat?->name ?? 'Sense assignar' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Hores registrades</span>
                <span class="field-value">{{ number_format((float) ($ticket->registres_temps_sum_hores ?? 0), 2) }} h</span>
            </div>
            <div class="field">
                <span class="field-label">Ticket pare</span>
                <span class="field-value">
                    @if($ticket->pare)
                        <a href="{{ route('projectes.tickets.show', [$projecte, $ticket->pare]) }}" style="color: var(--text); text-decoration: none;">
                            {{ $ticket->pare->codi_ticket }} - {{ $ticket->pare->titol }}
                        </a>
                    @else
                        Sense pare
                    @endif
                </span>
            </div>
            @if($ticket->descripcio)
                <div class="field" style="grid-column: 1 / -1;">
                    <span class="field-label">Descripcio</span>
                    <span class="field-value">{{ $ticket->descripcio }}</span>
                </div>
            @endif
        </div>
    </div>

    @if($ticket->fills->count() > 0)
        <div class="card">
            <div class="card-title">Tickets fills ({{ $ticket->fills->count() }})</div>
            @foreach($ticket->fills as $fill)
                <div style="display:flex; justify-content:space-between; gap:1rem; border-bottom:1px solid var(--border); padding:0.55rem 0;">
                    <a href="{{ route('projectes.tickets.show', [$projecte, $fill]) }}" style="color: var(--text); text-decoration:none;">
                        {{ $fill->codi_ticket }} - {{ $fill->titol }}
                    </a>
                    <span class="badge">{{ $fill->estat }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @can('assignar', $ticket)
        <div class="card">
            <div class="card-title">Assignar ticket</div>
            <form method="POST" action="{{ route('projectes.tickets.assignar', [$projecte, $ticket]) }}">
                @csrf
                @method('PATCH')

                <div class="form-group" style="max-width:420px;">
                    <label for="assignat_a">DEV del projecte *</label>
                    <select id="assignat_a" name="assignat_a" required>
                        <option value="">Selecciona un DEV...</option>
                        @foreach($devsEquip as $dev)
                            <option value="{{ $dev->id }}" {{ (string) old('assignat_a', $ticket->assignat_a) === (string) $dev->id ? 'selected' : '' }}>
                                {{ $dev->name }} ({{ $dev->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('assignat_a')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Assignar</button>
                </div>
            </form>
        </div>
    @endcan

    @can('canviarEstat', $ticket)
        @if(count($transicionsPossibles) > 0)
            <div class="card">
                <div class="card-title">Canviar estat</div>
                <div class="btn-group">
                    @foreach($transicionsPossibles as $estatNou)
                        <form method="POST" action="{{ route('tickets.canviarEstat', $ticket) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estat" value="{{ $estatNou }}">
                            <button type="submit" class="btn btn-ghost btn-sm">→ {{ $estatNou }}</button>
                        </form>
                    @endforeach
                </div>
                @error('estat')
                <div class="form-error" style="margin-top:0.75rem;">{{ $message }}</div>
                @enderror
            </div>
        @endif
    @endcan

    <div class="card">
        <div class="card-title">Registres de temps ({{ $ticket->registresTemps->count() }})</div>

        @if(auth()->user()->hasRole('DEV') && (int) $ticket->assignat_a === (int) auth()->id())
            <form method="POST" action="{{ route('tickets.registresTemps.store', $ticket) }}">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="data">Data *</label>
                        <input id="data" type="date" name="data" value="{{ old('data', now()->format('Y-m-d')) }}" required>
                        @error('data')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hores">Hores *</label>
                        <input id="hores" type="number" step="0.1" min="0.1" max="12" name="hores" value="{{ old('hores') }}" required>
                        @error('hores')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label for="descripcio_temps">Descripcio</label>
                        <textarea id="descripcio_temps" name="descripcio" rows="2">{{ old('descripcio') }}</textarea>
                        @error('descripcio')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @error('ticket_id')
                <div class="form-error" style="margin-top:0.5rem;">{{ $message }}</div>
                @enderror

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Afegir registre</button>
                </div>
            </form>
        @endif

        @forelse($ticket->registresTemps as $registre)
            <div style="border-bottom:1px solid var(--border); padding:0.65rem 0;">
                <div style="display:flex; justify-content:space-between; gap:1rem; align-items:center;">
                    <div>
                        <div style="font-weight:500;">{{ $registre->data->format('d/m/Y') }} · {{ number_format((float) $registre->hores, 2) }} h</div>
                        <div style="font-size:12px; color:var(--text-muted);">{{ $registre->user->name }}</div>
                        @if($registre->descripcio)
                            <div style="margin-top:0.25rem;">{{ $registre->descripcio }}</div>
                        @endif
                    </div>

                    @if((int) $registre->user_id === (int) auth()->id() || auth()->user()->hasRole('ADMIN', 'GESTOR'))
                        <form method="POST" action="{{ route('registresTemps.destroy', $registre) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm">Eliminar</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty" style="padding:1rem 0;">Encara no hi ha registres de temps.</div>
        @endforelse
    </div>

    @can('create', [App\Models\Comentari::class, $ticket])
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
                <div class="form-error" style="margin-top:0.5rem;">{{ $message }}</div>
                @enderror
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Afegir comentari</button>
                </div>
            </form>
        </div>
    @endcan

    <div class="card">
        <div class="card-title">Comentaris ({{ $ticket->comentaris->count() }})</div>
        @forelse($ticket->comentaris as $comentari)
            <div style="border-bottom:1px solid var(--border); padding:0.8rem 0;">
                <div style="display:flex; justify-content:space-between; gap:1rem;">
                    <div style="font-size:12px; color:var(--text-muted);">
                        {{ $comentari->autor->name }} · {{ $comentari->created_at->format('d/m/Y H:i') }}
                    </div>
                    @can('delete', $comentari)
                        <form action="{{ route('comentaris.destroy', $comentari) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm">Eliminar</button>
                        </form>
                    @endcan
                </div>
                <div style="margin-top:0.45rem;">{{ $comentari->text }}</div>
            </div>
        @empty
            <div class="empty">Encara no hi ha comentaris.</div>
        @endforelse
    </div>
@endsection

