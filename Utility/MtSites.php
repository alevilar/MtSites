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
		self::loadDatabase();
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
		if ( file_exists( TENANT_PATH . DS . self::getSiteName() . DS . 'settings.ini' ) ) {
			App::uses('IniReader', 'Configure');
			Configure::config('ini', new IniReader( TENANT_PATH . DS . self::getSiteName() . DS ));				
			Configure::load( 'settings', 'ini');
		}	else {
			throw new CakeException("El archivo de configuracion para el sitio ". self::getSiteName(). " no pudo ser encontrado");
		}	
	}


	/**
	 * Sets database to $config DATABASE named 'tenant' in database.php
	 */
	public static function loadDatabase () {

		$cons = ConnectionManager::enumConnectionObjects();
		App::uses('ConnectionManager', 'Model');
		if ( !empty( $cons ) && array_key_exists('tenant', $cons) ) {
			$databaseName =  $cons['tenant']['database_prefix'] . self::getSiteName();
			ConnectionManager::$config->tenant['database'] = $databaseName;
		} else {
			throw new CakeException("Archivo database.php mal configurado o falta el datasource llamado 'tenant' ademas del default");
			
		}
	}
}