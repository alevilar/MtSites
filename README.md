# Sites Plugin

Multi Tenant Site for multiple Db´s in App


*El plugin tiene 3 aristas principales*

- **MVC Sitios** (tabla sites). Permite crear nuevos "sitios". Esos sitios, son simplemente una entidad en la base de datos. Un registro. El sitio tiene, como campos, un nombre y un alias, este ultimo es alfanumerico y servirá para identificarlo mediante la URL. Por ejemplo: creo el Sitio llamado "Alejandro Vilar" cuyo alias es "alevilar".
Cada sitio puede tener una cantidad indefinida de usuarios vinculados a él.

- **Enrutador** (* archivo routes_sites.php*). Maneja las rutas de Cake haciendo que los sitios sean accesibles mediante URL. Siguiendo con el ejemplo, mi sitio ahora podria ser accedido si voy a la url: http://localhost/alevilar

- **Autenticacipon basada en username, pass y sitio** (*customizar AuthComponent para que lea esos 3 atributos*) Si otro usuario quiere ir a mi sitio. Deberia aparecerle "acceso denegado". Cada usuario solo puede ingresar al sitio que le pertenece.


## Reqs & instalacion

Se necesita tener Croogo instalado y funcionando
https://github.com/croogo/croogo
para instalar croogo hacer:
	composer create-project croogo/app app
	cd app
	composer install

luego, migrar el schema de este plugin para tener la estructura de la base de datos
"cake schema create sites"


Luego se debe colocar este plugin dentro de la carpeta app/Plugins
y cargarlo colocando en 
app/Config/bootstrap

la siguiente linea: 
CakePlugin::load('Sites', array('router' => true));