# Guia completa examen Laravel (basada en tu proyecto)

## 1) Mapa mental rapido del proyecto

Tu dominio ahora mismo:

- `Llibres` (libros)
- `Prestecs` (prestamos)
- `Bibliotecaris` (bibliotecarios)
- `User` (usuario ya existente)

Relaciones clave:

- `Llibre 1 --- N Prestecs`
- `User 1 --- N Prestecs`
- `Llibre N --- M Bibliotecaris` (tabla pivote `bibliotecari_llibre`)

Idea importante de examen:

- La FK siempre vive en la tabla hija (lado `N`).
- En `N:M` no hay hija de negocio; usas tabla pivote con 2 FKs.

## 2) Comandos imprescindibles (chuleta)

```powershell
php artisan make:model Llibres -m
php artisan make:model Prestecs -m
php artisan make:model Bibliotecaris -m

php artisan make:migration create_bibliotecari_llibre_table --create=bibliotecari_llibre

php artisan migrate
php artisan migrate:fresh --seed

php artisan make:controller LlibresController
php artisan make:controller PrestecsController

php artisan route:list
php artisan tinker
php artisan test
```

Para levantar servidor:

```powershell
php artisan serve
```

## 3) Migraciones: como pensar cada una

### `llibres` (simple)

Campos tipicos:

- `id`
- `titol`
- `categoria` enum `FICCIO`, `NOFICCIO`

Ejemplo:

```php
Schema::create('llibres', function (Blueprint $table) {
    $table->id();
    $table->string('titol');
    $table->enum('categoria', ['FICCIO', 'NOFICCIO']);
    $table->timestamps();
});
```

### `prestecs` (tabla hija con 2 FKs)

Necesitas:

- `usuari_id` -> `users`
- `llibre_id` -> `llibres`
- `actiu` (bool)
- `data` (datetime)

```php
Schema::create('prestecs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuari_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('llibre_id')->constrained('llibres')->cascadeOnDelete();
    $table->boolean('actiu');
    $table->dateTime('data');
    $table->timestamps();
});
```

### `bibliotecaris` (simple)

```php
Schema::create('bibliotecaris', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->timestamps();
});
```

### pivote `bibliotecari_llibre` (N:M)

```php
Schema::create('bibliotecari_llibre', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bibliotecari_id')->constrained('bibliotecaris')->cascadeOnDelete();
    $table->foreignId('llibre_id')->constrained('llibres')->cascadeOnDelete();
    $table->timestamps();

    $table->unique(['bibliotecari_id', 'llibre_id']);
});
```

## 4) Modelos correctos (muy tipico que pregunten esto)

### `app/Models/Prestecs.php`

Punto critico: `fillable` debe incluir FKs si usas `create()`.

```php
protected $table = 'prestecs';
protected $fillable = ['usuari_id', 'llibre_id', 'actiu', 'data'];
protected $casts = [
    'actiu' => 'boolean',
];

public function llibre(): BelongsTo
{
    return $this->belongsTo(Llibres::class, 'llibre_id');
}

public function usuari(): BelongsTo
{
    return $this->belongsTo(User::class, 'usuari_id');
}
```

### `app/Models/Llibres.php`

```php
protected $table = 'llibres';
protected $fillable = ['titol', 'categoria'];

public function prestecs(): HasMany
{
    return $this->hasMany(Prestecs::class, 'llibre_id');
}

public function bibliotecaris(): BelongsToMany
{
    return $this->belongsToMany(
        Bibliotecaris::class,
        'bibliotecari_llibre',
        'llibre_id',
        'bibliotecari_id'
    );
}
```

### `app/Models/Bibliotecaris.php`

```php
protected $table = 'bibliotecaris';
protected $fillable = ['nom'];

public function llibres(): BelongsToMany
{
    return $this->belongsToMany(
        Llibres::class,
        'bibliotecari_llibre',
        'bibliotecari_id',
        'llibre_id'
    );
}
```

## 5) Como se el nombre de la FK (`llibre_id`, `usuari_id`, etc.)

Regla:

1. Lo defines en migracion (`foreignId('llibre_id')`).
2. Ese nombre manda en BD.
3. En Eloquent, si no coincide con convencion, lo pasas explicito.

Ejemplo claro:

- Si tu columna es `usuari_id`, no `user_id`, entonces:
  - `belongsTo(User::class, 'usuari_id')`
  - `hasMany(Prestecs::class, 'usuari_id')`

## 6) Rutas de examen (las tuyas)

En `routes/web.php` (dentro de `middleware('auth')`):

```php
Route::prefix('llibres')->name('llibres.')->group(function () {
    Route::get('/create', [LlibresController::class, 'create'])->name('create');
    Route::post('/create', [LlibresController::class, 'store'])->name('store');
    Route::get('/cataleg', [LlibresController::class, 'index'])->name('index');
    Route::get('/senseprestecs', [LlibresController::class, 'sensePrestecs'])->name('senseprestecs');
    Route::get('/eliminar/{llibres}', [LlibresController::class, 'delete'])->name('delete');
    Route::get('/modificar/{llibres}', [LlibresController::class, 'edit'])->name('edit');
    Route::post('/modificar/{llibres}', [LlibresController::class, 'update'])->name('update');
});

Route::get('/bibliotecari/assignar/{idLlibre}/{idBibliotecari}', [LlibresController::class, 'assignarBibliotecari'])
    ->name('bibliotecari.assignar');

Route::prefix('prestecs')->name('prestecs.')->group(function () {
    Route::get('/{llibre}/{user}', [PrestecsController::class, 'create'])->name('create');
});

Route::get('/retornar/{idPrestec}', [PrestecsController::class, 'retornar'])->name('prestecs.retornar');
```

## 7) Controladores: implementacion de los 3 puntos del examen

### A) Asignar bibliotecario a libro

`/bibliotecari/assignar/{idLlibre}/{idBibliotecari}`

Version estudiante (muy clara):

```php
public function assignarBibliotecari(int $idLlibre, int $idBibliotecari)
{
    $llibre = Llibres::findOrFail($idLlibre);
    $bibliotecari = Bibliotecaris::findOrFail($idBibliotecari);

    if (!$llibre->bibliotecaris->contains($bibliotecari->id)) {
        $llibre->bibliotecaris()->attach($bibliotecari->id);
    }

    return $llibre->load('bibliotecaris');
}
```

Version mas pro (1 linea de asignacion):

```php
$llibre->bibliotecaris()->syncWithoutDetaching([$bibliotecari->id]);
```

### B) Libros sin prestamos

`/llibres/senseprestecs`

```php
public function sensePrestecs()
{
    return Llibres::whereDoesntHave('prestecs')->get();
}
```

Variante sin prestamos activos (otra pregunta tipica):

```php
return Llibres::whereDoesntHave('prestecs', function ($q) {
    $q->where('actiu', true);
})->get();
```

### C) Retornar prestamo con auth

`/retornar/{idPrestec}`

Regla: solo si el prestamo es del usuario logeado y `actiu=true`.

```php
public function retornar(int $idPrestec)
{
    $prestec = Prestecs::findOrFail($idPrestec);

    if ($prestec->usuari_id === auth()->id() && $prestec->actiu) {
        $prestec->update(['actiu' => false]);
    }

    return $prestec->fresh();
}
```

## 8) Blade create/edit de `llibres` (minimo y correcto)

### `resources/views/llibres/create.blade.php`

```blade
<form action="{{ route('llibres.store') }}" method="POST">
    @csrf

    <label for="titol">Titol</label>
    <input type="text" id="titol" name="titol" value="{{ old('titol') }}" required>

    <label for="categoria">Categoria</label>
    <select id="categoria" name="categoria" required>
        <option value="">Selecciona categoria</option>
        <option value="FICCIO" {{ old('categoria') === 'FICCIO' ? 'selected' : '' }}>FICCIO</option>
        <option value="NOFICCIO" {{ old('categoria') === 'NOFICCIO' ? 'selected' : '' }}>NOFICCIO</option>
    </select>

    <button type="submit">Send</button>
</form>
```

### `resources/views/llibres/modificar.blade.php`

Defaults con valor actual del libro:

```blade
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
```

## 9) Validacion (extra que da puntos)

En `store` y `update`:

```php
$request->validate([
    'titol' => ['required', 'string', 'max:255'],
    'categoria' => ['required', 'in:FICCIO,NOFICCIO'],
]);
```

Luego:

```php
Llibres::create($request->only(['titol', 'categoria']));
```

y en update:

```php
$llibres->update($request->only(['titol', 'categoria']));
```

## 10) Errores tipicos (los mas probables del examen)

### Error: `Field 'usuari_id' doesn't have a default value`

Causa:

- No esta en `$fillable`, o lo escribiste mal (`llibres_id` vs `llibre_id`).

Solucion:

- `protected $fillable = ['usuari_id', 'llibre_id', 'actiu', 'data'];`

### Error de `constrained('user')`

Causa:

- Tabla incorrecta (`user` en singular).

Solucion:

- `constrained('users')` o `constrained()` si la columna es `user_id`.

### `belongsTo` no encuentra FK

Causa:

- Convencion distinta (ej: `usuari_id`).

Solucion:

- `belongsTo(User::class, 'usuari_id')`.

### `select` no marca valor actual en edit

Causa:

- Poner `selected` en `<select>` y no en `<option>`.

Solucion:

- Comparar en cada `<option>` con `old('categoria', $llibres->categoria)`.

## 11) Diferentes maneras de hacer lo mismo (para argumentar en examen)

### A) IDs manuales (tu estilo actual)

Ruta:

```php
/bibliotecari/assignar/{idLlibre}/{idBibliotecari}
```

Controller:

```php
Llibres::findOrFail($idLlibre);
Bibliotecaris::findOrFail($idBibliotecari);
```

Ventaja:

- Super explicito para examen.

### B) Route Model Binding (mas limpio)

Ruta:

```php
/bibliotecari/assignar/{llibre}/{bibliotecari}
```

Controller:

```php
public function assignarBibliotecari(Llibres $llibre, Bibliotecaris $bibliotecari)
```

Ventaja:

- Menos codigo, mas Laravel.

### A) `attach` + `contains`

Mas didactico:

```php
if (!$llibre->bibliotecaris->contains($bibliotecari->id)) {
    $llibre->bibliotecaris()->attach($bibliotecari->id);
}
```

### B) `syncWithoutDetaching`

Mas pro:

```php
$llibre->bibliotecaris()->syncWithoutDetaching([$bibliotecari->id]);
```

### A) Responder JSON/lista

Util para comprobar rapido:

```php
return Llibres::all();
```

### B) Redirigir a vista

Mas app web clasica:

```php
return redirect()->route('llibres.index');
```

## 12) Como probar rapido en local (flujo util para examen)

```powershell
php artisan migrate:fresh
php artisan serve
php artisan route:list
```

Crear bibliotecario manual (si lo piden):

```powershell
php artisan tinker
```

```php
\App\Models\Bibliotecaris::create(['nom' => 'Biblio 1']);
```

Probar endpoints:

- Crear prestamo: `http://127.0.0.1:8000/prestecs/1/1`
- Asignar bibliotecario: `http://127.0.0.1:8000/bibliotecari/assignar/1/1`
- Libros sin prestamos: `http://127.0.0.1:8000/llibres/senseprestecs`
- Retornar prestamo: `http://127.0.0.1:8000/retornar/1`

## 13) Plantilla express para cualquier relacion en examen

### 1:N

1. En migracion hija:

```php
$table->foreignId('padre_id')->constrained('padres')->cascadeOnDelete();
```

2. En hija:

```php
public function padre() { return $this->belongsTo(Padre::class, 'padre_id'); }
```

3. En padre:

```php
public function hijos() { return $this->hasMany(Hijo::class, 'padre_id'); }
```

### N:M

1. Crear pivote con 2 FKs y unique.
2. `belongsToMany` en ambos modelos.
3. Asignar con `attach`, `sync`, `syncWithoutDetaching`.

## 14) Mini simulacro (como te lo pueden preguntar)

1. Haz migracion y modelo `Prestecs` con relaciones a `users` y `llibres`.
2. Muestra libros que nunca se han prestado.
3. Haz endpoint de devolver prestamo solo si el user autenticado es dueno.
4. Haz formulario edit con defaults.

Si sabes hacer eso, estas muy bien preparado.

## 15) Checklist final para hoy (repaso 20-30 min)

- [ ] Se explicar donde va la FK en `1:N`
- [ ] Se crear pivote `N:M` con `unique`
- [ ] Se usar `belongsTo`, `hasMany`, `belongsToMany`
- [ ] Se cuando usar FK explicita (`usuari_id`, `llibre_id`)
- [ ] Se construir `create` y `edit` Blade con `old(...)`
- [ ] Se hacer `whereDoesntHave(...)`
- [ ] Se validar con `$request->validate(...)`
- [ ] Se arreglar error de `$fillable`

## 16) Middleware y Policies (muy preguntable en examen)

### Que es cada cosa (regla rapida)

- Middleware: filtro antes de entrar al controlador (ej: usuario autenticado, rol, email verificado).
- Policy: regla de autorizacion sobre un recurso concreto (ej: este prestamo es de este usuario).

Piensalo asi:

- Quieres filtrar acceso general a una ruta -> Middleware.
- Quieres decidir si un usuario puede hacer una accion sobre un modelo -> Policy.

### Comandos Artisan

```powershell
php artisan make:middleware CheckIsAdmin
php artisan make:policy PrestecPolicy --model=Prestecs
```

### Middleware basico (ejemplo)

Archivo: `app/Http/Middleware/CheckIsAdmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}
```

Registro del alias del middleware:

- En proyectos recientes suele hacerse en `bootstrap/app.php`.
- En proyectos antiguos puede estar en `app/Http/Kernel.php`.

Uso en rutas:

```php
Route::get('/admin', fn () => 'Zona admin')->middleware('check.admin');
```

Tambien por grupo:

```php
Route::middleware(['auth', 'check.admin'])->group(function () {
    Route::get('/admin/prestecs', [PrestecsController::class, 'index']);
});
```

### Policy basica (ejemplo aplicado a Prestecs)

Archivo: `app/Policies/PrestecPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\Prestecs;
use App\Models\User;

class PrestecPolicy
{
    public function retornar(User $user, Prestecs $prestec): bool
    {
        return $prestec->usuari_id === $user->id && $prestec->actiu === true;
    }
}
```

Registro de policy:

- Si tu version tiene auto-discovery y nombres convencionales, puede detectarse sola.
- Si no, puedes mapearla manualmente en `AppServiceProvider` o `AuthServiceProvider` (segun estructura de tu proyecto).

### Usar policy en controlador

En `app/Http/Controllers/PrestecsController.php`:

```php
public function retornar(int $idPrestec)
{
    $prestec = Prestecs::findOrFail($idPrestec);

    $this->authorize('retornar', $prestec);

    $prestec->update(['actiu' => false]);

    return $prestec->fresh();
}
```

### Usar policy directamente en ruta

```php
Route::get('/retornar/{prestec}', [PrestecsController::class, 'retornar'])
    ->middleware('can:retornar,prestec');
```

### Usar policy en Blade

```blade
@can('retornar', $prestec)
    <a href="{{ route('prestecs.retornar', $prestec->id) }}">Retornar</a>
@endcan
```

### Diferencia practica para examen

- Sin policy: validas con `if (...)` dentro del controlador (mas simple para empezar).
- Con policy: codigo mas limpio, reusable y profesional.

Mini frase de examen:

"Middleware controla el acceso a la peticion; Policy controla permisos por recurso y accion".



