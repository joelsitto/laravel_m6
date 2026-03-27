<form action="{{ route('llibres.store') }}" method="POST">
    @csrf

    <label for="titol">Titol</label>
    <input type="text" id="titol" name="titol" value="{{ old('titol') }}" required>
    @error('titol')
    <div>{{ $message }}</div>
    @enderror

    <label for="categoria">Categoria</label>
    <select id="categoria" name="categoria" required>
        <option value="">Selecciona categoria</option>
        <option value="FICCIO" {{ old('categoria') === 'FICCIO' ? 'selected' : '' }}>FICCIO</option>
        <option value="NOFICCIO" {{ old('categoria') === 'NOFICCIO' ? 'selected' : '' }}>NOFICCIO</option>
    </select>
    @error('categoria')
    <div>{{ $message }}</div>
    @enderror

    <button type="submit">Send</button>
</form>
