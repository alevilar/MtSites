<?php
App::uses('RistoAppController', 'Risto.Controller');
App::uses('MtSites','MtSites.Utility');

/**
 * Sites Controller
 *
 */
class SitesController extends RistoAppController {

	//public $sfaffold;

	public function beforeFilter () {

		parent::beforeFilter();
		$this->Auth->allow(array('install'));		
	}

	public function index () {
		$user = $this->Auth->user();
		$sites = $this->Site->find( "all" );

		$this->set(compact('sites'));
	}


	public function delete ( $id ) {
		$this->Site->id = $id;
		if (!$this->Site->exists()) {
			throw new NotFoundException(__('Invalid %s', 'Site'));
		}

		$this->request->allowMethod('post', 'delete');
		if ($this->Site->delete()) {
			// recargar datos del usuario con el nuevo sitio            
            MtSites::loadSessionData();

			$this->Session->setFlash(__('The %s has been deleted.', 'Site'));
		} else {
			$this->Session->setFlash(__('The %s could not be deleted. Please, try again.', 'Site'));
		}
		return $this->redirect(array('action' => 'index'));		
	}



	public function install()
    {
        if( $this->request->is('post') )
        {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');

            $ip = env('HTTP_X_FORWARDED_FOR');
	        if ( empty($ip) ) {
	            $ip = $this->request->clientIp();
	        }
            $this->request->data['Site']['ip'] = $ip;

            $this->Site->create();
            if( $this->Site->save( $this->request->data ) ) {
                    $site_slug = $this->Site->field('alias');
                    // recargar datos del usuario con el nuevo sitio                    
                    MtSites::loadSessionData( $site_slug );
                    $this->Session->setFlash(__d('install',"¡Has Creado un Nuevo Comercio \"$site_slug\"!"));
                    $this->redirect( array('tenant' => $site_slug, 'plugin'=>'risto','controller' => 'pages', 'action' => 'display', 'home') );
            } else {
                $this->Session->setFlash("No se pudo crear el Sitio.", 'Risto.flash_error');
            }
        }
    }


}