<form action="{{ route('llibres.update', $llibres) }}" method="POST">
    @csrf

    <label for="titol">Titol</label>
    <input type="text" id="titol" name="titol" value="{{ old('titol', $llibres->titol) }}" required>

    <label for="categoria">Categoria</label>
    <select id="categoria" name="categoria" required>
        <option value="">Selecciona categoria</option>
        <option value="FICCIO" {{ old('categoria', $llibres->categoria) === 'FICCIO' ? 'selected' : '' }}>FICCIO</option>
        <option value="NOFICCIO" {{ old('categoria', $llibres->categoria) === 'NOFICCIO' ? 'selected' : '' }}>NOFICCIO</option>
    </select>

    <button type="submit">Send</button>
</form>
