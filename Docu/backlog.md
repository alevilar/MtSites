# TAREAS EN PROCESO

- Investigacion: como hacer que los subdominios sean mas dinamicos en apache (usar como wildcards)
- Crear ModelSites
- Crear MtSitesBehavior
- Crear MtSitesAuthorize
 

*mas detalles a continuación...*

### Detalles de cada punto en proceso
 
Investigacion. Definir creacion de subdominios en apache

Crear **Model Sites** con los siguientes campos:
- id
- name
- user_id
- databaseHost
- databaseUser
- databasePass
-  ...  todos los campos requeridos por DATABASE_CONFIG de Cake
  
Notar que 1 Sitio pertenece a 1 usuario



Crear **MtSitesBehavior**. Alli aplicaremos toda la lógica Multi Tenant que ayudará a decidir, para cada modelo, a que base de datos apuntar seteando el atributo del modelo *$useDbConfig*. 
Ej: public `$useDbConfig = site_my_nice_paxapos`;
Cuando el Behavior inicia, se crea, "on the fly", el array de configuracion para el sitio actual utilizando el    metodo  ConnectionManagger::create($name, $configArray)

Para mayor información sobre "ConnectionManagger create" method. 
[Ver Documentación Cake] (http://api.cakephp.org/2.5/class-ConnectionManager.html#_create)


Crear nuevo **MtSitesAuthorize** donde se manejará la autorización para acceder a los sitios según al sitio que pertenezcan.
[Ver Documentación Cake](http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html#creating-custom-authorize-objects)
	
	
# Pendientes	

- Refactorizar codigo para usar carpeta con nombre del sitio para archivos del webroot
- Seguridad para que otros usuarios no pueda acceder a la carpeta de imagenes de otros sitios
- Incluir Plugin Upload.Upload para administrar archivos como attachments
- script de instalacion:
  - crear subdominio en apache. Ver si se puede hacer con un alias.
  - establecer permisos ACL del primer usuario
  - Crear carpeta de usuario en webroot/files
  - crear BD para tenat
  - notificar por mail
  - actualizar tabla principal con usuario y sitio

- agregar Panel administrativo en sitio
- automatizar instalacion nuevo sitio

 
  
# TAREAS REALIZADAS

- tareas de Investigacion
  - cargar tenant con su base de datos propia
  - usar subdominios como tenants


