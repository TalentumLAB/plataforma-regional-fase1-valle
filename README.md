# LMS - DESPLIEGUE CON DOCKER EN EL servidor

## Contenido
  * [Descripción](#descripcion)
  * [Prerrequisitos](#prerrequisitos)
  * [Despliegue en Docker utilizando el docker-compose](#despliegue)

<a name="descripcion"></a>
## Descripción

Este proyecto contiene la información necesaria para desplegar la solucón de lms en el servidor utilizando Docker. 

### Software utilizado:

* Sistema operativo del servidor: Ubuntu server versión 20.04.05
* Docker: Versión 20.10.21
* Docker compose: Version 2.12.2
* MySQL: Versión 8.0.31 o mariadb 10
* PostgreSQL: Version 13

<a name="prerrequisitos"></a>
## Prerrequisitos

### Motores de bases de datos
En el equipo deben estar los motores de base de datos PostgreSQl y MySql correctamente instalados y aceptando conexiones externas. 

### Credenciales de acceso
Se deben solicitar las siguientes credenciales:

* Credenciales de acceso por SSH al servidor.
* Credenciales de acceso a MySQL.
* Credenciales de acceso a Postgres
* Usuario en el Ubuntu server con privilegios de root.

### Conexión al servidor mediante SSH
Para establecer la conexión puede utilizar el software PuTTY que puede descargar dando clic [aquí.][putty]

[putty]: https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html

### Conecta el servidor a Internet
Conectar un cable de red con acceso a Internet en el purto LAN del servidor y verifique que tiene conexión a Internet.

<a name="despliegue"></a>
## Despliegue en Docker utilizando el docker-compose

### Clonar el proyecto:

Si la carpeta /servidor/repositories no existe, ejecute los siguientes comandos:

```bash
    cd /servidor
    mkdir repositories
    cd repositories
    git clone https://github.com/TalentumLAB/lms_regional.git
    cd lms_regional/
```

Si la carpeta /servidor/repositories existe, ejecute los siguientes comandos:

```bash
    cd /servidor/repositories
    git clone https://gitlab.com/lms/integration_servidor.git
    cd integration_servidor/
```

### Creación de usuario y bases de datos en MySql:

Para crear las bases de datos y un usuario para accederla, se deben ejecutar los siguientes comandos. En este punto es requerido el usuario de MySQL.  


* Acceso a mysql: Reemplazar la palabra (nombre_usuario) por el usuario con privilegios de root que usará para conectarse a la base de datos MySql 

```bash
    mysql -u nombre_usuario -p
```

* Creación del usuario:

```bash
    CREATE USER 'lms-db-user'@'%' IDENTIFIED BY 'lms2022*';
```

* Creación y asignación de permisos sobre la base de datos lms_db:

```bash
    CREATE DATABASE lms_db;
    GRANT ALL PRIVILEGES ON lms_db.* TO 'lms-db-user'@'%';
    FLUSH PRIVILEGES;
```

* Creación y asignación de permisos sobre la base de datos moodle_db:

```bash
    CREATE DATABASE moodle_db;
    GRANT ALL PRIVILEGES ON moodle_db.* TO 'lms-db-user'@'%';
    FLUSH PRIVILEGES;
    Exit;
```

### Cargue de las bases de datos MySql

Para cargar la información en las bases de datos creadas en el paso anterior, se deben ejecutar los siguientes comandos:

* Base de datos lms_db:

```bash
    mysql -u root -p lms_db < databases/prod/backend_db.sql
```

* Base de datos moodle_db:

```bash
    mysql -u root -p moodle_db < databases/prod/moodle_db.sql
```

### Creación de usuario y base de datos en PostgreSQL:

Para crear la base de datos y un usuario para accederla, se deben ejecutar los siguientes comandos. En este punto es requerido el usuario de PostgreSQL.  


* Acceso a postgres:

```bash
    su postgres
```

* Creación del usuario:

```bash
    CREATE USER lms_db_user with encrypted password 'lms2022*';
```

* Creación y asignación de permisos sobre la base de datos lms_db:

```bash
    CREATE DATABASE keycloak;
    GRANT ALL PRIVILEGES ON DATABASE keycloak to lms_db_user;
```

### Despliegue de la solución

Para el despliegue de la solución se deben ejecutar el siguiente comando:

```bash
    docker-compose -p "lms" up
```