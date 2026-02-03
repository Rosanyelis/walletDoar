<<<<<<<< Guía de actualización >>>>>>>>>>>  
Versión anterior inmediata: 2.0.0
Versión actual: 2.0.1

Actualizaciones de funciones:
1. Actualización del registro de transacciones
2. Corrección de errores y mejoras

Usa los siguientes comandos en tu terminal para actualizar de v2.0.0 a v2.0.1

1. Actualizar Composer para añadir el nuevo paquete (asegúrate de estar en la raíz del proyecto)
   composer update

2. Añadir el nuevo archivo de migración
   php artisan migrate

3. Actualizar o añadir todos los seeders (asegúrate de estar en la raíz del proyecto)
   php artisan db:seed --class=Database\\Seeders\\Update\\VersionUpdateSeeder

4. Enlazar con Storage
   php artisan storage:link

5. Limpiar la caché de vistas (asegúrate de estar en la raíz del proyecto)
   php artisan view:clear


Guía de instalación limpia

1. Actualizar Composer para actualizar todos los paquetes PHP/Laravel
   composer update

2. Poblar la base de datos con los datos necesarios
   php artisan migrate:fresh --seed

3. Enlazar con Storage
   php artisan storage:link

4. Crear token para autenticación API ejecutando el siguiente comando
   php artisan passport:install
