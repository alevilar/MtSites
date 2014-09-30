<?php
App::uses('RistoAppController', 'Risto.Controller');


/**
 * Sites Controller
 *
 */
class SitesController extends RistoAppController {

	//public $sfaffold;

	public function beforeFilter () {

		parent::beforeFilter();
		//$this->Auth->allow(array('index'));
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
			$this->Session->setFlash(__('The %s has been deleted.', 'Site'));
		} else {
			$this->Session->setFlash(__('The %s could not be deleted. Please, try again.', 'Site'));
		}
		return $this->redirect(array('action' => 'index'));		
	}

}