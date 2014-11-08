<?php

App::uses('RistoAppModel', 'Risto.Model');


/**
 * Site Model
 *
 */
class SiteUser extends RistoAppModel {


	public $belongsTo = array(
		'MtSites.Site',
		'MtSites.User',
		);
}
