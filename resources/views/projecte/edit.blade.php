<form action="{{ route('projecte.update', $projecte) }}" method="POST">
    @csrf

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" value="{{ old('nom', $projecte->nom) }}" required>

    <label for="estat">Estat</label>
    <select id="estat" name="estat" required>
        <option value="">Selecciona estat</option>
        <option value="PROPOSTA" {{ old('categoria', $projecte->estat) === 'PROPOSTA' ? 'selected' : '' }}>PROPOSTA</option>
        <option value="EN_CURS" {{ old('categoria', $projecte->estat) === 'EN_CURS' ? 'selected' : '' }}>EN_CURS</option>
        <option value="ENTREGAT" {{ old('categoria', $projecte->estat) === 'ENTREGAT' ? 'selected' : '' }}>ENTREGAT</option>
    </select>

    <button type="submit">Send</button>
</form>
