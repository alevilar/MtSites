<?php
/**
 * MultiTenant behavior class.
 *
 * Enables Multi Tenant, changin the model database connection depending on site
 *
 * CakePHP :  Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP Project
 * @package       Cake.Model.Behavior
 * @since         CakePHP v 1.2.0.4487
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('ModelBehavior', 'Model');
App::uses('AclNode', 'Model');
App::uses('Hash', 'Utility');

/**
 * ACL behavior
 *
 * Enables objects to easily tie into an ACL system
 *
 * @package       Cake.Model.Behavior
 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/acl.html
 */
class MultiTenantBehavior extends ModelBehavior {


	// listado de los nombres de modelos que NO son TENANT o sea, que son genericos de la app core
	public $coreModels = array(
		'User',
		'Site',
		'SiteUser',
		'SiteSetup',
		'Install',
		);


/**
 * Sets up the configuration for the model, and loads databse from Multi Tenant Site
 *
 * @param Model $model Model using this behavior.
 * @param array $config Configuration options.
 * @return void
 */
	public function setup( Model $model, $config = array() ) {
		if ( in_array( $model->name,  $this->coreModels ) ){
			// si son del core usar default
			$model->useDbConfig = 'default';	
		} else {
			// usar el correspondiente al tenant
			$currentTenant = CakeSession::read('MtSites.current');
			if ( !empty($currentTenant) ) {

				// listar sources actuales
				$sources = ConnectionManager::enumConnectionObjects();

				//copiar del default
				$tenantConf = $sources['default'];

				// colocar el nombre de la base de datos
				$tenantConf['database'] = $tenantConf['database'] ."_". $currentTenant;

				// crear la conexion con la bd
				$confName = 'tenant_'.$currentTenant;
				ConnectionManager::create( $confName, $tenantConf );

				// usar tenant para este model
				$model->useDbConfig = $confName;	
			} else {
				throw new MissingConnectionException(__("Se esta queriendo acceder a un Modelo Tenant (%s), pero no estoy en un Tenant", $model->name));
			}
		}		
		return true;
	}
}