
# API PHP

API PHP con Conexión a MySQL y Funcionalidad CRUD

## Descripción

Este proyecto es una API desarrollada en PHP que permite realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) en una base de datos MySQL. La API utiliza el paquete Composer y la biblioteca Bramus Router para gestionar las rutas y las peticiones HTTP.

**Nota:** Ten en cuenta que no todas las tablas están disponibles en esta versión, ya que el proyecto todavía está en construcción.

## Requisitos

Asegúrate de tener los siguientes requisitos antes de ejecutar la API:

- PHP
- MySQL 
- Composer

## Instalación

1. Clona este repositorio en tu máquina local o descárgalo como archivo ZIP.
			`git clone <https://github.com/emilynievesb/API-PHP>`

2. Accede al directorio del proyecto.
			`cd nombre-del-proyecto`

3. Instala las dependencias utilizando Composer.
			`composer install`

4. Configuración de la base de datos.

   - Crea una base de datos MySQL en tu servidor.
   - Importa el archivo SQL proporcionado en la carpeta `database/`.

5. Configuración de la conexión a la base de datos.

   - Abre el archivo `config/database.php`.
   - Modifica los valores de configuración para que coincidan con tu entorno de base de datos.
   
6. ¡La API está lista para ser utilizada! Puedes enviar peticiones a `http://localhost:8000` utilizando las rutas y métodos disponibles.

## Uso

A continuación se muestra una descripción de las rutas y métodos disponibles en la API:

- `GET /endpoint`: Obtener todos los registros de la tabla especificada.
- `POST /endpoint`: Agregar un nuevo registro a la tabla especificada.
- `PUT /endpoint/{id}`: Actualizar un registro existente en la tabla especificada.
- `DELETE /endpoint/{id}`: Eliminar un registro existente de la tabla especificada.

**Nota:** Reemplaza `endpoint` con el nombre de la tabla deseada.

## Contribución

Si deseas contribuir a este proyecto, puedes seguir los siguientes pasos:

1. Haz un fork de este repositorio.
2. Crea una rama para tu nueva funcionalidad (`git checkout -b nueva-funcionalidad`).
3. Realiza los cambios necesarios y commitea tus modificaciones (`git commit -am 'Agrega nueva funcionalidad'`).
4. Push a la rama (`git push origin nueva-funcionalidad`).
5. Abre un pull request en este repositorio.


Este proyecto está bajo la Licencia [MIT](LICENSE).




