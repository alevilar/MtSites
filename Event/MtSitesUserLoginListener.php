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
			'Users.Controller.Users.afterLogin' => array(
				'callable' => 'onLogin',
			),			
		);
	}
	


	public function onLogin( $event ) {
		$controller = $event->subject();
		$user = $controller->Auth->user();
		
		$sites = $user['Site'];

		if ( count($sites) == 1 ) {
			$controller->Auth->loginRedirect = array('tenant'=> $sites[0]['alias'], 'plugin'=>'risto', 'controller'=>'pages', 'action' => 'display', 'dashboard');
		}

		if ( count($sites) == 0 ) {
			$controller->Auth->loginRedirect = array( 'plugin'=>'mt_sites', 'controller'=>'sites', 'action' => 'install');
		}
	}

}