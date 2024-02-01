Proyecto Aulas Steam

Proyecto Aulas Steam
====================

Contenido
---------

*   [Requisitos](#requisitos)
*   [Descripción](#descripcion)
*   [Uso](#uso)
    *   [Instrucciones](#instrucciones)
*   [Docker-Compose](#docker-compose)
    *   [Servicios](#servicios)
    *   [Volúmenes](#volúmenes)
    *   [Redes](#redes)

* * *

Requisitos
----------

El proyecto requiere que la persona interesada cuente con Docker y Docker Compose instalados en su sistema. Además, ten en cuenta que algunos archivos y carpetas pesados, como la carpeta Moodle, Moodledata, y archivos de base de datos, no están incluidos en el repositorio de Git debido a su tamaño considerable. Deberán ser proporcionados por otros medios.

Descripción
-----------

El proyecto Aulas Steam utiliza Docker Compose para orquestar varios servicios necesarios para ejecutar la solución. Estos servicios incluyen bases de datos como PostgreSQL y MariaDB, así como aplicaciones como Keycloak, un backend y un frontend.

Uso
---

### Instrucciones

1.  Se recomienda clonar este repositorio en la máquina local.
2.  Navegar a la carpeta del proyecto.
3.  Ejecutar el siguiente comando:

```
    docker-compose -f "docker-compose-narino.yml" up -d
```

Esto iniciará todos los servicios en segundo plano.

5.  Acceder a las aplicaciones a través de los siguientes enlaces:

*   Keycloak: [http://steam.narino.gov.co:8080](http://steam.narino.gov.co:8080)
*   Backend: [http://steam.narino.gov.co:4000](http://steam.narino.gov.co:4000)
*   Frontend: [http://steam.narino.gov.co:3000](http://steam.narino.gov.co:3000)
*   Moodle: [http://steam.narino.gov.co:5000](http://steam.narino.gov.co:5000)
*   Mailhog (para pruebas de correo): [http://steam.narino.gov.co:8025](http://steam.narino.gov.co:8025)

6.  Detener los servicios cuando se haya terminado:

```
    docker-compose down
```

* * *

Docker-Compose
--------------

### Servicios

*   **keycloak\_db**: Base de datos PostgreSQL para Keycloak.
*   **mailhog**: Herramienta de prueba para correos electrónicos.
*   **mariadb**: Base de datos MariaDB para la aplicación.
*   **mariadb2**: Otra instancia de MariaDB para una segunda base de datos.
*   **keycloak**: Plataforma de gestión de identidad y acceso.
*   **backend**: Backend de la aplicación Aulas Steam Reporter.
*   **frontend**: Frontend de la aplicación Aulas Steam Reporter.
*   **moodle**: Aplicación Moodle.

### Volúmenes

*   **pgdata**: Volumen persistente para la base de datos PostgreSQL.
*   **mariadb1**: Volumen persistente para la base de datos MariaDB (primera instancia).
*   **mariadb2**: Volumen persistente para la base de datos MariaDB (segunda instancia).

### Redes

*   **aulas-steam-net**: Red de puente para la comunicación entre los servicios.