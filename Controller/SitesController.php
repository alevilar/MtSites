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

}