<?php
App::uses('MtSitesAppModel', 'MtSites.Model');
/**
 * SiteUser Model
 *
 * @property User $User
 * @property Site $Site
 */
class SiteUser extends MtSitesAppModel {
	public $useTable = "sites_users";


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'site_id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'uuid' => array(
				'rule' => array('uuid'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'site_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Site' => array(
			'className' => 'Site',
			'foreignKey' => 'site_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);



	public $filterArgs = array(
        'user_id' => array(
            'type' => 'value',
            ),
        'site_id' => array(
            'type' => 'value',
            ),
        'username' => array(
            'type' => 'like',
            'field' => 'User.username',
            ),
        'email' => array(
            'type' => 'value',
            'field' => 'User.email',
            ),
        'site_alias' => array(
            'type' => 'value',
            'field' => 'Site.alias',
            ),
    );


    public function dismissUserFromSite ( $site_alias, $user_id = null) {
    	if ( is_null( $user_id) ) {
    		$user_id = $this->id;
    	}

    	$this->Site->recursive = -1;
    	$site = $this->Site->findByAlias($site_alias);
    	$siteUser['site_id'] = $site['Site']['id'];
		$siteUser['user_id'] = $user_id;
		$ret = $this->deleteAll($siteUser, false);
		return $ret;
    }


}
