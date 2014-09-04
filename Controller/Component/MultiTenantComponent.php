<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('MtSites', 'MtSites.Utility');
App::uses('Component', 'Controller');

class MultiTenantComponent extends Component {


	/**
 * Other components utilized by AuthComponent
 *
 * @var array
 */
	public $components = array('Auth', 'Session');



	public $authError = '';




	protected function _setDefaults () {

		$this->authError = __d('risto', 'No puedes acceder a este Sitio');		
	}

/**
 * Main execution method. Handles redirecting of invalid users, and processing
 * of login form data.
 *
 * @param Controller $controller A reference to the instantiating controller object
 * @return bool
 */
	public function startup(Controller $controller) {
		

		$methods = array_flip(array_map('strtolower', $controller->methods));
		$action = strtolower($controller->request->params['action']);

		$isMissingAction = (
			$controller->scaffold === false &&
			!isset($methods[$action])
		);

		if ($isMissingAction) {
			return true;
		}

		// verifico que el usuario sea del sitio al cual quiere ver
		if ( $this->autorizarUsuarioSitio($controller) || $this->_isLoginAction($controller)) {			
			return true;
		} else {					
			return $this->_unauthorized($controller);
		}		
	}




	public function autorizarUsuarioSitio( $controller ) {		
	 	$Site = ClassRegistry::init('MtSites.Site');     

		$siteName = MtSites::getSiteName();

	 	// verificar usuario logueado
        $auth_user = $this->Auth->user();

	 	// traer todos los sitios del usuario para escribir en sesion
		$sites = $Site->findFromUser( $auth_user['id']);

    	// ver el usuario pertenece al sitio
        if ( $Site->hasUser( $siteName, $auth_user['id'] ) ) {                	
        	$this->Session->write('Auth.User.Sites', $sites);	
        	return true;
        } else {
        	$this->Session->delete('Auth.User.Sites');
        }

        return false;
    }



    protected function _unauthorized(Controller $controller) {
		if ($this->Auth->unauthorizedRedirect === false) {
			throw new ForbiddenException($this->authError);
		}
		
		if ($this->Auth->unauthorizedRedirect === true) {

			if (  MtSites::isTenant() ) {
				$default = MtSites::domainUrl();
			} else {
				$default = MtSites::getUserDefaultSiteUrl();				
			}

			$url = $controller->referer($default, true);
		} else {
			$this->Session->setFlash(__d('risto', 'No tienes permisos para estar aquí'), 'Risto.flash_error');	
			$url = $this->Auth->unauthorizedRedirect;
		}
		
		$controller->redirect($url, null, true);
	}



/**
 * Normalizes $loginAction and checks if current request URL is same as login action.
 *
 * @param Controller $controller A reference to the controller object.
 * @return bool True if current action is login action else false.
 */
	protected function _isLoginAction(Controller $controller) {
		$url = '';
		if (isset($controller->request->url)) {
			$url = $controller->request->url;
		}
		$url = Router::normalize($url);
		$loginAction = Router::normalize($this->Auth->loginAction);

		return $loginAction === $url;
	}

	
}
