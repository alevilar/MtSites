<?php

App::uses('CakeEventListener', 'Event');
App::uses('Printaitor', 'Printers.Utility');
App::uses('ReceiptPrint', 'Printers.Utility');


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

		$controller = $event->subject();
		$current_subdomain = Configure::read('Site.alias');
			
        
		$sites = ClassRegistry::init("MtSites.Site")->findFromUser( $controller->Session->read( 'Auth.User.id') );

        
        if ((sizeof($sites) > 1) && in_array($current_subdomain, $sites)) {
            // Redirect para seleccionar el dominio
            $fullUrl = array('controller' => 'sites', 'action' => 'index');
            $controller->redirect( $fullUrl );
        } 

        if ((sizeof($sites) == 1) && ($current_subdomain != $sites[0] )) {
            // Redirect al dominio
            $fullUrl = Configure::read('Site.protocol'). Configure::read('Site.alias') . "." . Configure::read('Site.domain');
            $controller->redirect( $fullUrl );
        }

						
	}

}