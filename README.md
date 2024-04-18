<p align="center"><a href="https://www.mascotasperdidaspy.org" target="_blank">Mascotas Perdidas PY</a></p>

## Acerca del proyecto - About the project

Construido sobre Laravel, usando sus tecnologías existentes más otras auxiliares como jQuery, Bootstrap, Leaflet; el proyecto busca dar una respuesta a la problemática de las mascotas perdidas y encontradas en el país, de modo a que se puedan ubicar más fácilmente por medio de herramientas geográficos.

--

Build on top of Laravel, using its build in component plus other auxiliarys like jQuery, Bootstrap, Leaflet; the project seeks to respond the problematic of the lost and found mascots in the country, in a way that they could be found easily by the means of geografic tools.




## Contribuir con el proyecto - Contribute to the project

De momento no estoy buscando auspicio económico de ningún tipo, el proyecto a nivel código está hosteado acá en github como para que cualquiera pueda clonarlo, mejorarlo y redistribuirlo como mejor le parezca. Y a nivel de ejecución se está hosteando en Linode que podría escalar de ser necesario, pero por ahora me la banco solo.

Si puede ser útil los PR con mejoras y correcciones, que en serio son la forma en la que los proyectos open source crecen y mejoran.

-- 

At the moment I am not looking for financial sponsorship of any kind, the project at the code level is hosted here on github so that anyone can clone it, improve and redistribute it as they see fit. And at the execution level it is being hosted on Linode which could be scaled if necessary, but for now me la banco solo.

PRs with improvements and corrections can be useful, which are  the way in which open source projects grow and improve.

## Trabajando localmente con la copia del código fuente - Working locally with a copy of the source code
 Requisitos - Requirements
 * MySQL >= 8 (o MariaDB equivalente)
 * PHP >= 8.1
 * Composer
 * NodeJS (creo que la version actual ronda la 20)

 En windows, para desarrollo pueden usar laragon  que ya trae todo esto en su instalación
 

 Luego de clonar el proyecto, copien el archivo ".env.example" a uno con nombre ".env"
 

 En ese archivo tienen que configurar las credenciales de base de datos, cambiar el `APP_ENV` de production a development, y activar el debug, así como otras características que encontraran en el archivo


 Descomprimir los archivos que estan en la carpeta database/seeders (1.tar.gz, 2.tar.gz, 3.tar.gz, 4.tar.gz) 
 

 Despues de configurar el archivo ".env" en la consola tienes que ejecutar los siguientes comandos

```
composer install
npm install
npm run build
php artisan migrate --seed
php artisan key:generate
```

Finalmente, para poder levantar localmente el proyecto ejecutar

```
php artisan serve
```

--

In windows, for development you can use laragon  that already have all this in its installation

After cloning the project, copy the ".env.example" file with the name ".env"

In that file you have to configure the credentials of the database, change the `APP_ENV` from production to development, and activate the debug, like other features that you will find in that file

Decompress the files in the folder database/seeders (1.tar.gz, 2.tar.gz, 3.tar.gz, 4.tar.gz)

After configuring the ".env" file, in the console you should run the following comands:

```
composer install
npm install
npm run build
php artisan migrate --seed
php artisan key:generate
```

Finally, to execute locally the project execute:

```
php artisan serve
```



## Vulnerabilidades de seguridad - Security Vulnerabilities

Si descubres una vulnerabilidad o un error serio con el proyecto, me puedes contactar por twitter (ahora le dicen X) en [@p431i7o](https://twitter.com/p431i7o)

If you discover a security vulnerability or a serious error within the project, you can reach me at my twitter (now is called X) [@p431i7o](https://twitter.com/p431i7o) 

## Licencia - License

El código lo libero con licencia MIT que es la misma que usa Laravel [MIT license](https://opensource.org/licenses/MIT).

