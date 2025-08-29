Prueba Técnica:
-----------------------------------


-----------------------------------
Preguntas:
-----------------------------------
1. ¿Es necesario crear un endpoint logout?
   
En este caso es necesario un endpoint logout  porque la autenticación que utilizamos, funciona mediante sesiones. Utiliza cookies para identificar la sesión.
Utilizamos POST con token para CSRF para evitar este tipo de ataques, al finalizar se elimina el token de sesión.


2. ¿Cómo se puede implementar la funcionalidad "Dar de baja" de una cuenta?

Método 1: Eliminación Lógica 
Para este primer método, en lugar de borrar el registro del usuario de la base de datos, se añade un campo (por ejemplo, deleted_user) a la tabla users. Al dar de baja el usuario, simplemente se actualiza este campo.
Esto permite recuperar la cuenta en el futuro fácilmente.

Método 2: Eliminación Física Directa
Eliminar el registro del usuario directamente de la base de datos en el momento en que se solicita la baja.
Es el método más simple, pero en caso de necesitar recuperar el usuario tendríamos que depender de rollback de la BBDD o backups, ajenos al software que desarrollamos. 


Método 3: Proceso de Baja con Confirmación del usuario.
Este método al solicitar confirmación adicional antes de la eliminación.
Tenemos la certeza que el usuario solicita la baja.
La aplicación por ejemplo envía un correo electrónico de confirmación con un enlace único y de un solo uso. Después eliminaríamos físicamente el registro de la BBDD.
Esto evita eliminaciones accidentales. Pero es mucho más complejo de implementar que otros métodos.

