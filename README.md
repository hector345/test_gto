# Configuración del proyecto Laravel

Sigue estos pasos para configurar tu proyecto Laravel:

## Requisitos previos y herramientas de desarrollo

- PHP >= 8.2.1
- Composer
- Servidor de base de datos (MySQL)
- Servidor web (Apache)
- Node v20
- IDE: Visual Studio Code
- DBMS: DbShema y DBeaver


## Pasos de configuración

1. **Clonar el repositorio**

`git clone https://github.com/usuario/proyecto.git`


2. **Instalar dependencias**

Navega hasta el directorio del proyecto y ejecuta el siguiente comando para instalar las dependencias:

`composer install`
`yarn`
`yarn build && yarn dev`


3. **Configurar el entorno**

Copia el archivo `.env` en la raíz del proyecto adjunto en el correo o usa el elenv de ejemplo y cambia la configuracion de bases de datos.

Luego, abre el archivo `.env` y configura las variables de entorno para tu aplicación, incluyendo las credenciales de la base de datos y cualquier otra configuración específica de tu aplicación.

4. **Generar la clave de la aplicación**

Ejecuta el siguiente comando para generar la clave de la aplicación:

`php artisan key:generate`

5. **Configurar el servidor**

Agrega un virtual host y agrégalo en el archivo .env 

6. **Ejecutar las migraciones y los seeders**

Ejecuta el siguiente comando para realizar las migraciones y llenar la base de datos con datos de prueba:


`php artisan migrate --seed`

7. **Iniciar el servidor**

Finalmente, inicia el servidor.

8. **Sitio en Producción**

<!-- link https://inventarios.desarrollodigital.tech/ -->

Ingresa en <a href="https://inventario.desarrollodigital.tech" target="_blank">https://inventario.desarrollodigital.tech</a>

<!-- con los usuarios -->
Administrador: administrador@inventario.desarrollodigital.tech
Almacenista: almacenista@inventario.desarrollodigital.tech

Contraseña: password

# EJERCICIOS

## Conocimientos SQL

1.1 Describe el funcionamiento general de la sentencia JOIN

Es para unir 2 o más tablas de distintas formas comúnmente usando las llaves primarias y foráneas para unirlas.

1.2 ¿Cuáles son los tipos de JOIN y cuál es el funcionamiento de los mismos?

INNER JOIN:
Trae las coincidencias de ambas tablas.

LEFT JOIN:
Trae la columna y las filas que coinciden con la tabla de la derecha.

FULL JOIN:
Trae las filas de todas las tablas.

RIGHT JOIN:
Trae las filas de la tabla de la derecha y los registros que coinciden.

1.3 ¿Cuál es el funcionamiento general de los TRIGGER y qué propósito tienen?

Son eventos que se disparan cuando se realiza alguna instrucción específica en la base de datos, es muy útil para los casos que se requieran automatizar tareas.

1.4 ¿Qué es y para qué sirve un STORED PROCEDURE?

Son para almacenar sentencias comúnmente usadas y permiten ahorrar líneas de código y establecer patrones y orden.

Consultas Prácticas

Traer todos los productos que tengan una venta (1.5):
