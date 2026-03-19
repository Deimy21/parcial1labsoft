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
