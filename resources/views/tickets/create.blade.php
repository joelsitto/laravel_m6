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
                    <label for="estat">Estat</label>
                    <select id="estat" name="estat">
                        @foreach(['NOU', 'OBERT', 'TANCAT'] as $estat)
                            <option value="{{ $estat }}" {{ old('estat', 'NOU') === $estat ? 'selected' : '' }}>{{ $estat }}</option>
                        @endforeach
                    </select>
                    @error('estat')
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

