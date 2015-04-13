<?php

App::uses('MtSites', 'MtSites.Utility');
App::uses('IniReader', 'Configure');
App::uses('Hash', 'Utility');


/**
 * Class used for manipulation of Tenant Sites.
 *
 * @package       MtSites.Utility
 */
class TenantSettings {

	static function read ( ) {
		if ( !MtSites::isTenant() ) {
    		throw new CakeException(__("Solo se puede acceder dentro de un Tenant") );
    	}
    	$tenant = MtSites::getSiteName();

    	$tenantSetFile = APP . 'Tenants' . DS . $tenant . DS; 

    	/*
        if(!file_exists($tenantSetFile)) {
        	throw new NotFoundException( __("no se encontro el archivo de configuracion del tenant", $tenantSetFile ) );
        }
        */
    
    	$IniSetting = new IniReader($tenantSetFile);
    	$settings = $IniSetting->read( 'settings.ini' );
    	return $settings;
	}


	static function write ( $data ) {
		if ( !MtSites::isTenant() ) {
    		throw new CakeException(__("Solo se puede acceder dentro de un Tenant") );
    	}
    	$tenant = MtSites::getSiteName();

    	$tenantSetFile = APP . 'Tenants' . DS . $tenant . DS;     	
    
    	$IniSetting = new IniReader($tenantSetFile);
    	$settings = $IniSetting->read( 'settings.ini' );


    	$newData = Hash::merge($settings, $data);
		return $IniSetting->dump( 'settings.ini', $newData);
	}
}