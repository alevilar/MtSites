<?php

App::uses('CakeEventListener', 'Event');
App::uses('Printaitor', 'Printers.Utility');
App::uses('ReceiptPrint', 'Printers.Utility');
App::uses('MtSites', 'MtSites.Utility');


/**
 * Nodes Event Handler
 *
 * @category Event
 * @package  Croogo.Nodes.Event
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class MtSitesUserLoginListener implements CakeEventListener {

/**
 * implementedEvents
 */
	public function implementedEvents() {
		return array(
			'User.afterLogin' => array(
				'callable' => 'onLogin',
				//'passParams' => true,
			),			
		);
	}



	


	public function onLogin( $event ) {
		// levantar settings y base de datos del usuario y del sitio tenant
		MtSites::load();

		$controller = $event->subject();

		$sites = ClassRegistry::init("MtSites.Site")->findFromUser( $controller->Session->read( 'Auth.User.id') );
		
		$controller->Session->write('Auth.User.Sites', $sites);
        

        // verifico si el usuario esta logueado y esta en la app core, entonces lo redirige a su tenant
		if ( !MtSites::isTenant() ) {
			$controller->Auth->loginRedirect = MtSites::getUserDefaultSiteUrl() ;
		}
	}

}