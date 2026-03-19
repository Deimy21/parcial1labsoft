# 🌱 Parcial Proyecto Vivero

Sistema de administración de viveros desarrollado en Laravel que permite gestionar productores, fincas, viveros, labores y productos de control agrícola.

---

## 👥 Integrantes del Grupo

| Nombre |
|--------|
| **Gustavo Adolfo Chiquito Betancurt**  |
| **Deimy Michelle Godoy Guzman**  |
| **Oscar Omar Moreno Cruz**  |
| **Victor Wilson Rosero Cuatin**  |
| **Luis Fernando Caicedo Caicedo**  |

---

## 📊 Diagrama de Clases

![Diagrama de Clases](https://github.com/Deimy21/parcial1labsoft/blob/master/public/diagrama%20de%20clases.jpeg)

---

## 📋 Descripción del Proyecto

Sistema de administración de viveros que permite gestionar:

- **Productores**: Personas propietarias de fincas (documento, nombre, apellido, teléfono, correo)
- **Fincas**: Terrenos asociados a productores (número de catastro, municipio)
- **Viveros**: Espacios de cultivo dentro de fincas (código, tipo de cultivo)
- **Labores**: Actividades realizadas en viveros (fecha, descripción)
- **Productos de Control**: Insumos agrícolas con herencia (STI):
  - **Hongo**: periodo_carencia, nombre_hongo
  - **Plaga**: periodo_carencia
  - **Fertilizante**: fecha_ultima_aplicacion

---

### Relaciones Principales

- **Productor** ↔ **Finca**: Uno a muchos (un productor puede tener varias fincas)
- **Finca** ↔ **Vivero**: Uno a muchos (una finca puede alojar varios viveros)
- **Vivero** ↔ **Labor**: Uno a muchos (en un vivero se realizan múltiples labores)
- **Labor** ↔ **ProductoControl**: Muchos a uno (una labor emplea un producto de control)
- **ProductoControl** (herencia): Hongo, Plaga y Fertilizante mediante STI

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **Laravel** | 12 | Framework principal |
| **PHP** | 8.2 | Lenguaje de programación |
| **SQLite/MySQL** | - | Base de datos |
| **Eloquent ORM** | - | Modelado de datos y relaciones |
| **Single Table Inheritance (STI)** | - | Herencia para productos de control |
| **PHPUnit** | 11 | Pruebas unitarias |
| **Blade** | - | Motor de plantillas |

---
## ⚙️ Instalación y Configuración

### Requisitos Previos

- PHP >= 8.2
- Composer
- MySQL o SQLite
- Node.js (opcional, para frontend)

### Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/parcial-proyecto-vivero.git
cd parcial-proyecto-vivero

# 2. Instalar dependencias de PHP
composer install

# 3. Copiar archivo de entorno
cp .env.example .env

# 4. Configurar base de datos en .env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:5LNlcsDWpDjaWs1sRjlJXlEyscAQEFJgXYso3JAEH1I=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

# 5. Generar clave de aplicación
php artisan key:generate

# 6. Ejecutar migraciones
php artisan migrate:fresh --seed

# 7. Iniciar servidor de desarrollo
php artisan serve
