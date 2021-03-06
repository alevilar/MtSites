<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('BaseAuthorize', 'Controller/Component/Auth');
App::uses('MtSites', 'MtSites.Utility');
App::uses('Hash', 'Utility');

/**
 * An authorization adapter for AuthComponent. Provides the ability to authorize using a controller callback.
 * Your controller's isAuthorized() method should return a boolean to indicate whether or not the user is authorized.
 *
 * {{{
 *	public function isAuthorized($user) {
 *		if (!empty($this->request->params['admin'])) {
 *			return $user['role'] === 'admin';
 *		}
 *		return !empty($user);
 *	}
 * }}}
 *
 * the above is simple implementation that would only authorize users of the 'admin' role to access
 * admin routing.
 *
 * @package       Cake.Controller.Component.Auth
 * @since 2.0
 * @see AuthComponent::$authenticate
 */
class MtSitesAuthorize extends BaseAuthorize {

public $name = "MtSitesAuthorize";


/**
 * Checks user authorization using a controller callback.
 *
 * @param array $user Active user data
 * @param CakeRequest $request Request instance.
 * @return bool
 */
	public function authorize($user, CakeRequest $request) {
                //sesion expiro
                if( empty( $user['id'] ) ){
                       return false;
                }

                //si es admin general esta autorizado
                if(array_key_exists('is_admin', $user) && $user['is_admin']) {
                        return true;
                }


                // si es pantalla de instalacion de nuevo tenant
                if ( $request->params['plugin'] == 'mt_sites' && $request->params['controller'] == 'sites' && $request->params['action'] == 'install'){
                    return true;
                }

                if ( $request->params['plugin'] == 'install' && $request->params['controller'] == 'configurations' && $request->params['action'] == 'first_configuration_wizard'){
                    return true;
                }
                
                if ( !array_key_exists('tenant', $request->params) && empty($request->params['tenant']) ){
                        // es pagina global. O sea, no estoy dentro del tenant
                       // debug( $request->params );
                        if ( $request->params['action'] == 'display' ) {
                      return true;
                        }
            }
               

                if ( !array_key_exists('Site', $user) ) {
                    // el usuario no tiene sitios asignados. No puede entrar a ningun lado
                    return false;   
                }



                // listar sitios del la variable de sesion del usuario actual
                $siteAlias = Hash::extract( $user['Site'], '{n}.alias' );
                if ( array_key_exists('tenant', $request->params) && in_array( $request->params['tenant'], $siteAlias ) ) {
                    // si el usuario tiene, entre sus sitios al sitio actual, entonces esta autorizado                      
                    return true;
                }

                // acceso a la pagina de edicion y de cambio de contraseña propia de cada usuario registrado y logueado. 
                if (  (strtolower($request->params['controller']) ==  "users" 
                        && strtolower($request->params['action']) == 'my_edit') 
                    or (strtolower($request->params['controller']) ==  "users" 
                        && strtolower($request->params['action']) == 'change_password') ){
                      //Si el controlador es users y el action es my_edit o change_password te dejara entrar siempre que estes logeado.
                      return true;
                }


		return false;
	}

}
