<?php
App::uses('RistoAppController', 'Risto.Controller');


/**
 * Sites Controller
 *
 */
class SitesController extends RistoAppController {

	public $sfaffold;


	public function index () {
		$user = $this->Auth->getUser();
		$sites = $this->Site->find('all', array(
			'conditions' => array(
				'User.id' => $user['id'];
				)
			));

		$this->set(compact('sites'));
	}

}