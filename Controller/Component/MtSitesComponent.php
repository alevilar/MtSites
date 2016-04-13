<?php

App::uses('MtSites', 'MtSites.Utility');

/**
 * Authentication control component class
 *
 * Binds access control with user authentication and session management.
 *
 * @package       Cake.Controller.Component
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html
 */
class MtSitesComponent extends Component {

	public function initialize(Controller $controller) {
		$this->request = $controller->request;		
        		
                MtSites::load( $this->request );

                $urlEditConfig = array('plugin'=>'install', 'controller'=>'configurations', 'action'=>'first_configuration_wizard');

                if (  MtSites::isTenant() && !Configure::read('Site.configurado') && 
                	  !Hash::contains($this->request->params, $urlEditConfig) // o sea, no estoy en la misma pagina de edicion
                	){
                	$controller->redirect($urlEditConfig );
                }

                return true;
	}
}