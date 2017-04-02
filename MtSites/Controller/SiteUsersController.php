<?php
App::uses("RistoAppController", "Risto.Controller");


class SiteUsersController extends RistoAppController {


	public function __mustBeTenant (){
		if ( !MtSites::isTenant()) {
        	throw new ForbiddenException( __("El Tenant no es válido o no fue encontrado en el sistema"));
        }

		parent::beforeRender();
		$this->set('model', $this->modelClass);
	}



/**
 * Admin add
 *
 * @return void
 */
	public function add() {		
        
        $site = $this->SiteUser->Site->findByAlias(MtSites::getSiteName() );
        $this->request->data['Site']['id'] = $site['Site']['id'];
		
		if ( $this->request->is('post') ) {	
            
			$this->request->data[$this->modelClass]['tos'] = true;
			$this->request->data[$this->modelClass]['email_verified'] = true;
			//save new user
			if ($this->SiteUser->User->add($this->request->data)) {
				$this->Session->setFlash(__d('users', 'The User has been saved'));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__d('users', 'The User couldn`t be saved'), 'Risto.flash_error');
			}
		}
		$roles = $this->SiteUser->User->Rol->find('list');
		$this->set('model', 'User');
		$this->set(compact( 'roles', 'site'));
	}



/**
 * Admin edit
 *
 * @param null $userId
 * @return void
 */
	public function edit($userId = null) {

		if ( $this->request->is('post')) {
			unset ( $this->request->data[$this->modelClass]['last_login'] );

			
			$this->SiteUser->bindModel(array(
		        'hasMany' => array(
		            'RolUser' => array(
		                'classname' => 'Users.RolUser',
		            ) 
		        ) 
		    ));

			$rolUser = array();
			if (!empty($this->request->data['Rol']['Rol'])) {
			    	$rolUser[] = array(
			    		'rol_id' => $this->request->data['Rol']['Rol'],
			    		'user_id' => $userId,
			    		);
			

			$this->SiteUser->RolUser->deleteAll(array('RolUser.user_id' => $userId ));

			if ( $this->SiteUser->RolUser->saveMany( $rolUser ) ) {
				$this->Session->setFlash(__d('users', 'User saved'));
			} else {
				$this->Session->setFlash(__d('users', 'Error saving'), 'Risto.flash_error');
			}
		  } else {
		  	$this->Session->setFlash(__d('users', 'Error: no has elegido el rol del usuario, intentelo de nuevo.'), 'Risto.flash_error');
		  }
		  $this->redirect($this->referer());
		}

		if (empty($this->request->data)) {
			$this->SiteUser->recursive = 1;
			$this->request->data = $this->SiteUser->read(null, $userId);
			unset($this->request->data[$this->modelClass]['password']);
		}

		$roles = $this->SiteUser->User->Rol->find('list');
		$this->set(compact( 'roles'));
		$this->render('admin_form');
	}

	
/**
 * Admin add
 *
 * @return void
 */
	public function delete_from_tenant ( $user_id ) {
		$this->__mustBeTenant ();

		if ( $this->request->is(array('post', 'put') ) ) {
			$alias = MtSites::getSiteName();			
			if ( $this->SiteUser->dismissUserFromSite($alias, $user_id) ) {
				$user['user_id'] = $user_id;
				$this->SiteUser->User->RolUser->deleteAll($user, false);
				$this->Session->setFlash(__d('users','El usuario fue removido del comercio'));
			} else {
				$this->Session->setFlash(__d('users','Error al remover al usuario del comercio.'), 'Risto.flash_error');
			}
		}

		$this->redirect($this->referer() );
	}



    public function assign_other_site($user_id) {

		if ($this->request->is( array('post','put')) ) {
			$siteId = $this->request->data['User']['site_id'];
			if ( $this->SiteUser->User->addIntoSite($siteId, $user_id) ) {
				$this->Session->setFlash(__d('users','El usuario fue vinculado satisfactoriamente con el comercio'));
			} else {
				$this->Session->setFlash(__d('users','Error al vincular el usuario con el comercio.'), 'Risto.flash_error');

			}
			$this->redirect( $this->referer() );
		}

		$this->SiteUser->recursive = 1;
		$this->request->data = $this->SiteUser->read(null, $user_id);
		$Role = ClassRegistry::init("Users.Role");

		$currLogUser = $this->Auth->user();
		if ( $currLogUser['is_admin'] ) {
			// si es admin listar todos los comercios disponibles
			$sites = $this->SiteUser->Site->find("list", array('order'=> array('Site.name'=>'ASC')));
		} else {
			// si no es admin, listar solo los comercios disponibles
			$sites = $currLogUser['Site'];
			$sites = Hash::combine($sites, '{n}.id', '{n}.name');
			asort($sites);
		}

		$this->SiteUser->User->id = $user_id;
		$this->SiteUser->User->contain(array('Site'));
		$userSites = $this->SiteUser->find("list", array('conditions'=> array('SiteUser.user_id'=> $user_id), 'fields'=> array('SiteUser.site_id', 'SiteUser.site_id')));

		foreach ( $userSites as $us ) {
			// eliminar del listado los sitios que el usuario ya tiene vinculados
			unset($sites[$us]);
		}


		$this->set(compact('sites', 'user_id'));
	}
	
}