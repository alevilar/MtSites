<?php



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
		
		if ( !CakeSession::started() ) {
			CakeSession::write('MtSites.current', null );
			//throw new UnauthorizedException("MtSites unathorized");
		}

		if ( array_key_exists( 'tenant', $this->request->params ) ) {
        	// si el usuario tiene, entre sus sitios al sitio actual, entonces esta autorizado        	
			CakeSession::write('MtSites.current', $this->request->params['tenant'] );
        	MtSites::load();
        	return true;
        } else {
        	CakeSession::write('MtSites.current', null );
        }
	}
}