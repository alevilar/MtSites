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
		'Session'
		);


/**
 * Sets up the configuration for the model, and loads databse from Multi Tenant Site
 *
 * @param Model $model Model using this behavior.
 * @param array $config Configuration options.
 * @return void
 */
	public function setup( Model $model, $config = array() ) {
		// si son del core usar default
		if ( in_array( $model->name,  $this->coreModels ) ){		
			$model->useDbConfig = 'default';	
		} else {
			// usar tenant
			$model->useDbConfig = 'tenant';	
		}
		return true;
	}
}