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

                return true;
	}
}