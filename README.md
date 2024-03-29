# Proyecto Symfony CountryCRUD

Este proyecto Symfony implementa un CRUD (Create, Read, Update, Delete) para gestionar información de países utilizando la API de restcountries.com.

## Requisitos

- PHP 7.4 o superior
- Composer
- Symfony CLI o servidor web (por ejemplo, Apache, Nginx)

## Instalación

1. Clona este repositorio en tu máquina local:

```bash
git clone https://github.com/Genjosanzo88/countryCRUD.git
```

## Instala las dependencias del proyecto con Composer:

```bash
cd proyecto-symfony
composer install
```

## Configura tu base de datos en el archivo .env si es necesario.
## Crea la base de datos y ejecuta las migraciones:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
## USO
Este pequeño CRUD permite crear, editar, mostrar y eliminar paises.
Nos muestra la bandera del pais que elegimos, como su moneda (pudiendo editarla) y agregar unas notas.

Para iniciar el proyecto:
```bash
symfony server:start
```

Ahora podemos acceder:
```bash
http://127.0.0.1:8000/
```
