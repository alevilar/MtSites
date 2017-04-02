<?php




if (!defined('TENANT_PATH') ) {
	define("TENANT_PATH", APP . 'Tenants');
}

			
App::uses('MtSites', 'MtSites.Utility');
App::uses('MtSitesUserLoginListener', 'MtSites.Event');
App::uses('CakeEventManager', 'Event');


MtSites::loadSessionData();
CakeEventManager::instance()->attach( new MtSitesUserLoginListener );

