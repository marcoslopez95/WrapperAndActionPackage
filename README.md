## Agradecimientos:

- Maikel Bello

## Información del Paquete

El paquete `marcoslopez95/wrap-and-action-package` es una librería de Laravel que facilita la creación de clases Action y Wrapper mediante comandos Artisan. [1](#0-0)

## Instalación

```bash
composer require marcoslopez95/wrap-and-action-package
```

## Requisitos

- PHP ^8.1 [2](#0-1)
- Laravel ^10.0|^11.0|^12.0 [3](#0-2)

## Comandos Artisan Disponibles

### 1. Crear una clase Action

```bash
php artisan make:action {name}
```

**Ejemplo:**
```bash
php artisan make:action UserRegistrationAction
```

Este comando genera una clase en `app/Actions/UserRegistrationAction.php` con el namespace `App\Actions`. [4](#0-3) [5](#0-4)

### 2. Crear una clase Wrapper

```bash
php artisan make:wrapper {name}
```

**Ejemplo:**
```bash
php artisan make:wrapper UserDataWrapper
```

Este comando genera una clase en `app/Wrapper/UserDataWrapper.php` con el namespace `App\Wrapper`. [6](#0-5) [7](#0-6)

## Estructura de Archivos Generados

| Comando | Directorio | Namespace |
|---------|------------|-----------|
| `make:action` | `app/Actions/` | `App\Actions` |
| `make:wrapper` | `app/Wrapper/` | `App\Wrapper` |

## Funcionalidades de las Clases Wrapper

Las clases Wrapper extienden de `Illuminate\Support\Collection` y proporcionan métodos útiles para el manejo de datos:

- `setProperty(string $property, \Closure $fn)` - Establece propiedades dinámicamente
- `getBool(string $input, mixed $default = null)` - Obtiene valores booleanos
- `getInt(string $input, int $default = null)` - Obtiene valores enteros
- `getFloat(string $input, float $default = null)` - Obtiene valores flotantes
- `getArray(string $input, ?array $default = [])` - Obtiene arrays
- `getCollect(string $input, ?array $default = null)` - Obtiene Collections [8](#0-7)

## Ejemplos de Uso

### Ejemplo de Action Class
```php
<?php

namespace App\Actions;

class UserRegistrationAction 
{
    public function invoke(): mixed
    {
        // Lógica de registro de usuario
        return $result;
    }
}
```

### Ejemplo de Wrapper Class
```php
<?php

namespace App\Wrapper;

use Manu\WrapAndActionPackage\Wrapper;

class UserDataWrapper extends Wrapper
{
    public function getName(): string
    {
        return $this->get('name', '');
    }
    
    public function getAge(): int
    {
        return $this->getInt('age', 0);
    }
}
```
---
Imaginemos que estamos trabajando con una estructura grande en el request
```php
<?php
// namespace
// uses

class UserController extends Controller{

    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|string|min:3',
           'email' => 'required|string|email'
           'addresses' => 'required|array',
           'addresses.*.name' => 'required|name',
           'addresses.*.zip_code' => 'required|name',
           'addresses.*.city_id' => 'required|exists:cities,id',
           'addresses.*.name' => 'required|name',
           // otros 20 campos
        ])  
        
        $user = User::create($request->only(['name','email']));
        
        collect($request->addresses)->each(function(array $address) use ($user){
            $data = $address;
            // parseo y manipulación de la data
            $user->addresses()->create($data);
        });
    }
}
```

Tendríamos que manipular/parsear/evaluar todos los campos necesarios. Otros tendríamos que recorrerlos para poder procesarlos.
Allí es donde nace la necesidad de usar un Wrapper para la manipulación fácil del objeto del request.

```php
<?php

namespace App\Wrapper;

use Illuminate\Support\Collection;
use Manu\WrapAndActionPackage\Wrapper;

class UserDataWrapper extends Wrapper
{
    public function getData(): array
    {
        return [
        'name' => $this->get('name'),
        'email' => $this->get('email'),
        ];
    }
    
    public function getAddresses(): Collection
    {
        return $this->castMany('addresses', AddressesWrapper::class);
    }
}

// AddressesWrapper.php

class AddressesWrapper extends Wrapper
{
    public function getData(): array
    {
        // parseo y manipulación de data.
        return [
            'name' => $this->get('name'),
            'zip_code' => $this->get('zip_code'),
            'city_id' => $this->get('city_id'),
            'name' => $this->get('name'),
        ];
    }
}
```

De esta manera al yo necesitar manipular los datos tengo una clase que se encargará únicamente de manipular y parsear los datos que necesite para poder guardarlos de mejor forma.

```php
<?php
// namespace
// uses
use App\Wrapper\UserDataWrapper;

class UserController extends Controller{

    public function store(UserRequest $request, UserDataWrapper $wrapper, UserStoreAction $action)
    {
        $user = $action->invoke(new UserDataWrapper($request->all()));
        return response()->json($user);
    }
}

// App/Actions/UserStoreAction.php
<?php
 
namespace App\Actions;  
  
class UserStoreAction{

    public function invoke(UserDataWrapper $data): User
    {
        $user = User::create($data->getData());
        
        $data->getAddresses()->each(fn($address) => $user->addresses()->create($address));
        
        return $user;
    }
}
```
 
## Licencia

MIT License [9](#0-8)

## Autor

**Marcos M. López**
- Email: marcoslopez1895@gmail.com
- Homepage: https://marcoslopez95.github.io [10](#0-9)

## Notes

El paquete utiliza el sistema de `GeneratorCommand` de Laravel para crear las clases, lo que garantiza consistencia con las convenciones del framework. Los comandos están registrados automáticamente cuando la aplicación se ejecuta en modo consola a través del service provider del paquete.

Wiki pages you might want to explore:
- [Overview (marcoslopez95/WrapperAndActionPackage)](/wiki/marcoslopez95/WrapperAndActionPackage#1)
- [Code Generation (marcoslopez95/WrapperAndActionPackage)](/wiki/marcoslopez95/WrapperAndActionPackage#3)
