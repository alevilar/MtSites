TAREAS EN PROCESO

        - Ponerle nombre a los sitios y a las sucursales
        - Logica de los archivos: como almacenar archivos del core y de cada usuario
        - Definir base de datos para sitio generico
        - Definir base de datos para sucursales
        - Definir implementacion de ACL y  permisos para sucursales
        - Definir implementacion ACL y  permisos para sitios





Definir estructura:	
	- Ponerle nombre a los sitios y a las sucursales
	- Logica de los archivos: como almacenar archivos del core y de cada usuario
	- Definir base de datos para sitio generico
	- Definir base de datos para sucursales
	- Definir implementacion de ACL y  permisos para sucursales
	- Definir implementacion ACL y  permisos para sitios
	- crear sitio: usuario, contraseña y nombre del comercio. El usuario sera el nombre del sitio, y el nombre del comercio será la 1er sucursal
	- automatizar instalacion nuevo sitio






Tareas de programacion a realizar
		

	Archivo routes_sites.php
			Se deberá realizar un archivo que lea los sitios y las convierta en rutas.

			Ejemplo: Si existe un Sitio cuyo alias sea: "mi_alias", entonces se deberá crear una ruta a:
			 http://localhost/mi_alias
			 el comportamiento es similar al routes de cake, tal como hace con los plugins activados. Pero, en este caso, con los alias de los sitios.

	Levantar datasource basado en el sitio actual. 			
			Esto quiere decir, que, basandose en el sitio que fue ruteado, se debe levantar como y colocar como default datasource a la base de datos del sitio.

	AuthComponent
			En lugar de solo validar por username y password, que lo haga tambien por sitio (si el usuario pertenece al sito)
		
	MVC De la tabla sites con $scaffolded views
			El usuario que crea un sitio, se convierte en administrador del mismo. Esto quiere decir que al crear un sitio, se debe setear el usuario en la tabla sites_users (BD general) y en la tabla roles_users (particular a un sitio) se debe colocar como rol admin

	Script creacion de bases de datos x sitio
			Al crear un nuevo sitio se deberá migrar y crear una nueva base de datos
			Lo mejor seria que se utilice un schema de cake. Pero por ahora tambien seria valido tirar un puro SQL.

	Permisos App General
			el registro de usuarios no requiere de permisos (es publico)
			el usuario debe estar registrado y logeado para crear sitios
			un usuario crea sitios

	Permisos Sitio
			solo el usuario admin puede ver, editar y modificar roles dentro del sitio



	

Detalles para comprender el proceso del enrutamiento, el AuthComponent y la carga de la BD del Sitio

	El proceso es el siguiente:
		1) Router lee si existe sitio, o mejor dicho, carga todos los sitios al enrutador.
		2) Cuando viene peticion, si no existe sitio, devolver: pagina no existe
		3) Si existe, ver si User esta logueado
		4) Si logueado, ver si User pertenece a Sitio
		5) Si pertenece, levantar database del sitio como default datasource
		6) Al cargar DB del Sitio, verificar permisos de usuario segun rol y ACL
