<form action="{{ route('projecte.store') }}" method="POST">
    @csrf

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
    @error('nom')
    <div>{{ $message }}</div>
    @enderror

    <label for="estat">Estat</label>
    <select id="estat" name="estat" required>
        <option value="">Selecciona estat</option>
        <option value="PROPOSTA" selected>PROPOSTA</option>
        <option value="EN_CURS">EN_CURS</option>
        <option value="ENTREGAT">ENTREGAT</option>
    </select>
    @error('estat')
    <div>{{ $message }}</div>
    @enderror

    <button type="submit">Send</button>
</form>
