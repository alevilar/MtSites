<?php

// $servername = env("HTTP_HOST");

if (array_key_exists('SERVER_NAME', $_SERVER)) {
	$servername = $_SERVER['SERVER_NAME'];



	if( isset($servername)){
		// debug( $_SERVER['SERVER_NAME'] );
		//debug( env("HTTP_HOST") );
		preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $servername, $match);

		
		if ( !empty( $match )) {
			$siteName = $match[1];
			Configure::write('Site.alias', $siteName);	

			// Read configuration file from ini file
			if ( file_exists( App::pluginPath('MtSites') . 'TenantsConf/' . $siteName.'_config.ini' ) ) {
				App::uses('IniReader', 'Configure');
				Configure::config('ini', new IniReader( App::pluginPath('MtSites') . 'TenantsConf/' ));				
				Configure::load( $siteName.'_config', 'ini');
			}
		}

	}

}





App::uses('MtSitesUserLoginListener', 'MtSites.Event');
App::uses('CakeEventManager', 'Event');


CakeEventManager::instance()->attach( new MtSitesUserLoginListener );
