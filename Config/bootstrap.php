<?php




if (!defined('TENANT_PATH') ) {
	define("TENANT_PATH", APP . DS . 'Tenants');
}

			
App::uses('MtSites', 'MtSites.Utility');
App::uses('MtSitesUserLoginListener', 'MtSites.Event');
App::uses('CakeEventManager', 'Event');
App::uses('ConnectionManager', 'Model');



MtSites::loadConfigFiles();


CakeEventManager::instance()->attach( new MtSitesUserLoginListener );
