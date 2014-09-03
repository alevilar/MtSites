<?php

App::uses('RistoAppModel', 'Risto.Model');


/**
 * Site Model
 *
 */
class Site extends RistoAppModel {

	public $hasAndBelongsToMany = array(
		'Users.User'
		);

	//public $hasMany = array('MtSites.SiteUser');


	public function hasUser ( $user_id ) {
		$site = $this->find('first', array(
				'conditions' => array(
					'Site.alias' => Configure::read('Site.alias'),
					),				
				));
		$users = Hash::extract($site, 'User.{n}.id');
		return in_array($user_id, $users);

	}

	public function findFromUser ( $user_id ) {
		$this->User->bindModel(
	        array('hasAndBelongsToMany' => array(
	                'Site' => array(
	                    'className' => 'MtSites.Site'
	                )
	            )
	        )
	    );


	    $sites = $this->User->find('first', array(
	    		'conditions' => array(
	    			'User.id' => $user_id
	    			),
	    		'contain' => array(
	    			'Site'
	    		)
	    	));

		if (!empty( $sites['Site'] )) {
			return $sites['Site'];
		} else {
			return null;
		}
	}
}
