# Sites Plugin

Multi Tenant Site for multiple Db´s in same App for Ristorantino Mágico



 - *bootstrap.php*. Es donde se cargan los archivos de configuracion del sitio (settings.ini). Tambien agrega el evento para escuchar "on User.login"

 - *Event/MtSitesUserLoginListener*. Es para escuchar eventos despues del login

 - *MtSitesComponent* es para validar que el usuario tenga permisos para acceder y manejar redirecciones dependiendo donde estoy. Es como una especie de AuthComponent

  - *MultiTenantBehavior* es para decirle a cada Model la base de datos que debe usar. En base a si el Model es tenant o no. Se configuran los Models del core aqui para que no sean leidos como "tenants".

 - *Utility.MtSites* es una libreria de funciones para manejar la logica Multi Tenant. Nos permite, por ejemplo saber si estoy en un tenant o en el dominio global.
'''Ej: MtSites::isTenant()'''

