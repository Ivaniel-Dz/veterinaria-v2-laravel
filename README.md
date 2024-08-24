# Veterinaria v2

## Tecnologías usadas
- Laravel 11
- Vue 3
- Inertia
- Mysql
- Tailwind
- axios

## Instalación
1. Creación del proyecto
```bash
composer create-project laravel/laravel:^11.0 veterinaria
```

2. Ingresar al proyecto
```bash
cd veterinaria
```

>Documentación: [Larastarters](https://github.com/LaravelDaily/Larastarters?tab=readme-ov-file)

3. Instalación del paquete Larastarters
```bash
composer require laraveldaily/larastarters --dev
```

4. Instalación de las dependencias
```bash
php artisan larastarters:install
```
Opciones 1 escogida:
1: Laravel Breeze  & Inertia (Tailwind)

Opciones 2 escogida:
2: tailwindcomponents

4. Ejecutamos la migración de la base de datos después de haber configurado el archivo .env
```bash
php artisan migrate
```

5. Ejecutamos los servidores
Laravel
```bash
php artisan serve
```

Vite
```bash
npm run dev
```

6. Instalación de Plugin para ordenar las clases de tailwind
```bash
npm install -D prettier prettier-plugin-tailwindcss
```

7. Instalación de axios para el form
```bash
npm install axios
```

Crear el archivo `.prettierrc.js` en el proyecto y dentro solo agregar `{}`

## Crear las navegación en el dashboard
1. Crea el componente: Mensaje, Service, Dashboard.
2. En el componente Navigation que se encuentra en `js/Layouts/Navigation.vue`.
    - Se agrega el link de los componente a traves del parámetro router().
3. en el archivo web.php donde están declaradas los routers se agrega los routers de los componentes creadas.

## Creación de las migraciones

```bash
php artisan make:migration create_mensajes_table --create=mensajes
```

```bash
php artisan make:migration create_propietarios_table --create=propietarios
```

```bash
php artisan make:migration create_mascotas_table --create=mascotas
```

```bash
php artisan make:migration create_servicios_table --create=servicios
```

```bash
php artisan make:migration create_citas_table --create=citas
```

## Crear Model
```bash
php artisan make:model Name
```

## Crear Controller
```bash
php artisan make:controller PhotoController --resource
```

## Crear Factory: datos de pruebas
```bash
php artisan make:seeder MensajeSeeder
```
```bash
php artisan make:factory MensajeFactory
```
```bash
php artisan migrate:fresh --seed
```

## Modelo, Vista, controlador de Formulario de Mensajes

### 1. Modelo `Mensaje`

El modelo `Mensaje` representa un mensaje en la aplicación. Está ubicado en `app/Models/Mensaje.php`.

##### Atributos:

- `$nombre`: Nombre del remitente del mensaje.
- `$email`: Dirección de correo electrónico del remitente del mensaje.
- `$descripcion`: Contenido del mensaje.

##### Métodos:

- `protected $fillable`: Especifica los atributos que pueden ser asignados en masa al crear o actualizar una instancia de `Mensaje`.

---

### 2. Controlador `MensajeController`

El controlador `MensajeController` maneja las acciones relacionadas con los mensajes en la aplicación. Se encuentra en `app/Http/Controllers/MensajeController.php`.

##### Métodos:

- `index()`: Este método maneja la lógica para mostrar una lista paginada de mensajes. Utiliza el modelo `Mensaje` para obtener los mensajes de la base de datos y luego renderiza una vista usando Inertia.

---

### 3. Rutas

Las rutas asociadas con el controlador `MensajeController` están definidas en el archivo de rutas `routes/web.php`.

- **GET /mensajes**: Ruta para mostrar una lista paginada de mensajes. Esta ruta utiliza el método `index()` del controlador `MensajeController`.

---

### 4. Vistas

La vista `'Mensajes/Index'` es utilizada para mostrar la lista paginada de mensajes. Esta vista se renderiza utilizando Inertia y se espera que reciba un conjunto de mensajes como datos.

---

Esta documentación proporciona una descripción general de los componentes principales relacionados con el manejo de mensajes en la aplicación. Los modelos, controladores, rutas y vistas trabajan en conjunto para permitir a los usuarios ver y gestionar mensajes de manera efectiva.

## Pendiente
1. CRUD completo de citas
2. Enviar mensaje por form
3. Eliminar y actualizar mensajes desde el dashboard

## Problemas que hubo a la hora de desarrollo
### 1. Envio del form a la base de dato

**Problema Inicial**: Al intentar enviar un formulario desde una aplicación Vue hacia un backend Laravel, se presentó el error: **"The POST method is not supported for route /"**. Esto indicaba que no existía una ruta configurada para manejar solicitudes POST en el endpoint raíz (`/`).

### Pasos para Solucionar el Problema

1. **Revisión de Configuración Inicial**:
   - Se verificó que el formulario Vue no estaba configurado correctamente para enviar datos a la ruta adecuada en Laravel.
   - No existía una ruta definida para manejar solicitudes POST en `web.php`.

2. **Actualización del Formulario Vue**:
   - Se modificó el formulario Vue para utilizar el método `POST` y se cambió el atributo `action` para apuntar a la ruta correcta (`/mensajes`).
   - Se utilizó `axios` para enviar la solicitud POST y manejar la respuesta desde el servidor.

   **Cambios realizados**:
   - Implementación de `v-model` en los campos del formulario para la reactividad.
   - Adición de una función `submitForm` que utiliza `axios.post` para enviar datos al backend Laravel.
   - Se incluyó el token CSRF dinámicamente en la solicitud para cumplir con las políticas de seguridad de Laravel.

3. **Configuración de Rutas en Laravel**:
   - Se añadió una nueva ruta POST en `web.php` para manejar la creación de mensajes:
   
   ```php
   Route::post('mensajes', [MensajeController::class, 'store'])->name('mensajes.store');
   ```

4. **Creación del Método Store en el Controlador**:
   - Se creó el método `store` en `MensajeController` para validar y guardar los datos recibidos en la base de datos.

   **Código del método `store`**:
   ```php
   public function store(Request $request)
   {
       $request->validate([
           'nombre' => 'required|string|max:255',
           'email' => 'required|email|max:255',
           'descripcion' => 'required|string|max:500'
       ]);

       Mensaje::create($request->all());

       return response()->json(['message' => 'Mensaje enviado exitosamente'], 200);
   }
   ```

5. **Pruebas y Verificación**:
   - Se realizaron pruebas exhaustivas para asegurar que el formulario envía los datos correctamente y que estos se almacenan en la base de datos MySQL.
   - Se verificó que la ruta POST está funcionando y manejando las solicitudes como se espera.

### Instalaciones y Configuraciones Realizadas

- **Axios**: Se instaló y configuró `axios` para manejar las solicitudes HTTP desde el cliente Vue.
- **Token CSRF**: Configuración correcta del token CSRF para asegurar las solicitudes POST en Laravel.
- **Rutas y Controladores**: Actualización de las rutas en `web.php` y creación de un método `store` en el controlador correspondiente.

### Conclusión

El problema fue solucionado exitosamente al configurar adecuadamente las rutas de Laravel y el formulario Vue. La aplicación ahora permite el envío de datos a la base de datos MySQL sin errores, asegurando una comunicación eficiente y segura entre el cliente y el servidor.

---

## Mejoras del sistema
- Que muestre en el formulario de citas que hora y dia están disponibles
- Asignar veterinario automáticamente de acuerdo su disponibilidad
- Asignar roles

## Preview de la web
### Landing Page
![preview](/public/img/preview/preview1.jpeg)
### Landing Page (Modo Oscuro)
![preview](/public/img/preview/preview2.jpeg)
### Login
![preview](/public/img/preview/preview3.jpeg)
### Registro
![preview](/public/img/preview/preview4.jpeg)
### Recuperación de contraseña
![preview](/public/img/preview/preview5.jpeg)
### Dashboard
![preview](/public/img/preview/preview6.jpeg)
