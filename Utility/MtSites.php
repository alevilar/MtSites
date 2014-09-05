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




	/**
	* checks from current URL
	* returns true if is Tenant or false is in core domain
	* Ej: http://example.com => false
	* Ej: http://tenant1.example.com => true
	**/
	public static function isTenant () {
		if ( self::getSiteName() ) {
			return true;
		}
		return false;
	}

	/**
	*
	* returns main core domain URL full.  from current URL
	* Ej: http://example.com
	**/
	public static function domainUrl() {
		return Configure::read('Site.protocol') .  Configure::read('Site.domain');
	}


	/**
	*
	* returns main core domain URL full.  from current URL
	* Ej: http://tenant1.example.com
	**/
	public static function tenantUrl() {
		return Configure::read('Site.protocol') . self::getSiteName() . "." . Configure::read('Site.domain');
	}


	public static function load () {
		if ( !self::$load ) {
			self::loadConfigFiles();
			self::loadDatabase();
		}
		self::$load = true;
	}


	/**
	 * Return the url of the first site founded from array from of Auth user
	 */
	public static function getUserDefaultSiteUrl ( $user_id = null) {
		$siteName = self::getUserSiteName();
		if ( $siteName ) {
			return Configure::read('Site.protocol') . $siteName. "." . Configure::read('Site.domain') . '/dashboard';
		} 
		return null;
	}

	/**
	 * Return the first site from array from of Auth user
	 */
	public static function getUserSiteName () {

		if (CakeSession::check('MtSites')) {			
			$sites = CakeSession::read('MtSites');		
			$sitealias = Hash::extract($sites, '{n}.alias');
			$res = false;
			if ( count($sitealias) ) {
				$res = $sitealias[0];
			}
			return $res;	
		} else {
			return null;
		}
	}

	/**
	 * Get site name from current URL
	 * if is not a tenant return false
	 */
	public static function getSiteName () {
		if ( env('SERVER_NAME') ) {
			$servername = env('SERVER_NAME');

			if( isset($servername ) ){
				preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $servername, $match);
				if ( !empty( $match ) && !empty($match[1])) {
					return $match[1];
				}
			}
		}
		return false;	
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
		}		
	}


	/**
	 * Sets database to $config DATABASE named 'tenant' in database.php
	 */
	public static function loadDatabase () {
		App::uses('ConnectionManager', 'Model');
		if ( !empty( ConnectionManager::$config ) ) {
			$databaseName = ConnectionManager::$config->tenant['database_prefix'] . self::getSiteName();
			ConnectionManager::$config->tenant['database'] = $databaseName;
		}
	}
}