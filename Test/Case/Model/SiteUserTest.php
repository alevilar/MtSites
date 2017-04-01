<?php
App::uses('SiteUser', 'MtSites.Model');

/**
 * SiteUser Test Case
 */
class SiteUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.mt_sites.site_user',
		'plugin.mt_sites.user',
		'plugin.mt_sites.site'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SiteUser = ClassRegistry::init('MtSites.SiteUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SiteUser);

		parent::tearDown();
	}

}
