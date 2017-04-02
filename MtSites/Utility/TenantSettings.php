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


    /**
    *
    *   Lee el archivo completo settings.ini del Tenant
    *
    *   @param String $tenant Nombre del tenant. Si nada es pasado aqui, debo estar dentro de un tenant para que funcione
    **/
	static function read ( $tenant = '') {
        if (empty($tenant)) {
    		if ( !MtSites::isTenant() ) {
        		throw new CakeException(__("Solo se puede leer settings.ini estando dentro de un Tenant") );
        	}
            $tenant = MtSites::getSiteName();
        }

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


    /**
    *
    *   Lee el archivo completo settings.ini del Tenant
    *
    *   @param String $tenant Nombre del tenant. Si nada es pasado aqui, debo estar dentro de un tenant para que funcione
    **/
	static function write ( $data, $tenant = '' ) {
        if (empty($tenant)) {
    		if ( !MtSites::isTenant() ) {
        		throw new CakeException(__("Solo se puede escribir settings.ini estando dentro de un Tenant") );
        	}
            $tenant = MtSites::getSiteName();
        }
        $tenantSetFile = APP . 'Tenants' . DS . $tenant . DS;       
        $fileName = 'settings.ini';

        $IniSetting = new IniReader($tenantSetFile);
        if (file_exists( $tenantSetFile.$fileName )) {
            $settings = $IniSetting->read( $fileName );
        	$data = Hash::merge($settings, $data);
        }

		return $IniSetting->dump( 'settings.ini', $data);
	}
}