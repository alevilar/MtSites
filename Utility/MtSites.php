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

/**
 * Class used for manipulation of Tenant Sites.
 *
 * @package       MtSites.Utility
 */
class MtSites {

	public static $load = false;



	public static function load () {
		self::loadConfigFiles();
		self::loadTenantRol();
	}



	public static function loadTenantRol () {
		if ( self::isTenant() ) {
			$User = ClassRegistry::init('Users.User');
			$User->loadRole();
			$User->contain('Rol');
			$User->recursive = 1;
			$user = $User->read(null, CakeSession::read('Auth.User.id'));
			CakeSession::write('Auth.User.Rol', $user['Rol']);
		}
	}


	public static function loadSessionData ( $aliasName = null ) {
		$User = ClassRegistry::init('Users.User');
		if ( !empty($aliasName) ) {
			CakeSession::write('MtSites.current', $aliasName);
			self::loadTenantRol();
		} else {
			$User->unBind(array('unbindModel'=> array(
				'hasAndBelongsToMany' => array('Users.Rol')
				)));
		}
		$User->recursive = 1;
		$user = $User->read(null, CakeSession::read('Auth.User.id'));
		CakeSession::write('Auth.User', $user['User']);
		unset($user['User']);
		foreach ( $user as $k=>$v) {
			CakeSession::write("Auth.User.$k", $v);
		}		
	}


	public static function  isTenant() {
		$cur = CakeSession::read('MtSites.current');
		return (boolean) $cur;
	}


	/**
	 * Get site name from current URL
	 * if is not a tenant return false
	 * 
	 */
	public static function getSiteName () {
		return CakeSession::read('MtSites.current');	
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
			}	else {
				throw new CakeException("El archivo de configuracion para el sitio ". self::getSiteName(). " no pudo ser encontrado");
			}	
		}
	}

}