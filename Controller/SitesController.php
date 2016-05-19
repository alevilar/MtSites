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
		$this->Auth->allow(array('install', 'checkname'));		
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
		return $this->redirect($this->referer());		
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

                    // tengo que poner el link directo en string porque si uso el array del Routes aun no tiene definido este nuevo tenant el routes.php
                    $this->redirect( '/' . $site_slug );
            } else {
            	$addMes = '';
            	if (!empty($this->Site->validationErrors['Installer'])){
            		$addMes = " ".implode( ', ',$this->Site->validationErrors['Installer']);
            	}
                $this->Session->setFlash( __( "No se pudo crear el Sitio.") . $addMes , 'Risto.flash_error');
            }
        }
    }


    /**
     * 
     * 	Funcion Para chequear el nombre y el tenant que no exista
     * devuelve 
     * 				string Alias con un nombre unico
     **/
    public function checkname(){
    	if( $this->request->is('post') ){
    		$aliasName = $this->Site->__buscarAliasName( $this->request->data['Site']['name'] );
    		$this->set('aliasName', $aliasName);
    		$this->set('_serialize', array('aliasName'));
    	}
    }


}