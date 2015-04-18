<?php
/**
 * Library of Multi Tenant functions for Ristorantino
 *
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       MtSites.Utility
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('CakeSession', 'Model/Datasource');
App::uses('Hash', 'Utility');
App::uses('ConnectionManager', 'Model');
App::uses('CakeRequest', 'Network');

/**
 * Class used for manipulation of Tenant Sites.
 *
 * @package       MtSites.Utility
 */
class MtSites {

	public static $load = false;

	public static $tenant = null;




	/**
	*
	*	Indica si estoy actualmente dentro de un tenant o en el sitio global
	*
	*	@return Boolean true si estoy en un tenant, false si no lo estoy
	**/
	public static function  isTenant() {
		return (boolean) self::$tenant;
	}


	/**
	 * Get site name from current URL
	 * if is not a tenant return false
	 * 
	 */
	public static function getSiteName () {
		return self::$tenant;	
	}
	

	

	/**
	*
	*	basicamente lo que hace esta funcion es recuperar los Sitios del usuario
	*	sirve para cuando cambiamos algo que implique refrescar los datos previamente cargados
	*	en sesion cuando hice la autenticacion
	*
	*
	**/
	public static function loadSessionData ( $aliasName = null ) {
		if ( !CakeSession::check('Auth.User.id') ) {
			return false;
		}
		
		$User = ClassRegistry::init('Users.User');
		if ( !empty($aliasName) ) {
			self::$tenant = $aliasName;
			self::__loadTenantRol();
		}
		$User->contain('Site');
		$user = $User->read(null, CakeSession::read('Auth.User.id'));
		CakeSession::write('Auth.User', $user['User']);
		unset($user['User']);
		foreach ( $user as $k=>$v) {
			CakeSession::write("Auth.User.$k", $v);
		}		
	}





	/**
	*
	*	Esta funcion es llamada por MtSitesComponent
	*	Es la encargada de inicializar la lógica para que funcione el modo TENANT
	*
	*	@param CakeRequest $request 
	*
	**/
	public static function load ( CakeRequest $request ) {
		self::__initTenantVar( $request );
		self::loadConfigFiles();
		self::__loadTenantRol();
	}






	/**
	 * Loads settings files from Tenant folder 
	 */
	public static function loadConfigFiles () {
		// Read configuration file from ini file
		if ( self::isTenant() ) {
			if ( file_exists( TENANT_PATH . DS . self::getSiteName() . DS . 'settings.ini' ) ) {
				App::uses('IniReader', 'Configure');
				Configure::config('ini', new IniReader( TENANT_PATH . DS . self::getSiteName() . DS ));				
				Configure::load( 'settings', 'ini');


				App::uses('CakeNumber', 'Utility');
				CakeNumber::defaultCurrency(Configure::read('Geo.currency_code'));

			}	else {
				throw new CakeException("El archivo de configuracion para el sitio ". self::getSiteName(). " no pudo ser encontrado");
			}	
		}
	}



	/**
	*
	*	Inicialiiza la variable estatica $tenant
	*	Toma el valor del tenan del CakeRequest 
	*
	*	@param CakeRequest $request
	*	@return Bool true si estoy en un tenant, false si no lo estoy
	**/
	private static function __initTenantVar( CakeRequest $request ) {
		
		if ( !array_key_exists( 'tenant', $request->params ) ) {
        	return false;	
        }

        // si el usuario tiene, entre sus sitios al sitio actual, entonces esta autorizado        	
		self::$tenant = $request->params['tenant'];

    	return true;
	}

	/**
	*
	*	Carga en Session los roles aplicados para el usuario en el Tenant actual
	*
	*
	**/
	private static function __loadTenantRol () {
		if ( self::isTenant() && CakeSession::check('Auth.User.id') ) {
			$User = ClassRegistry::init('Users.User');
			$User->contain('Rol');
			$User->recursive = 1;
			$user = $User->read(null, CakeSession::read('Auth.User.id'));
			CakeSession::write('Auth.User.Rol', $user['Rol']);
		}
	}


}