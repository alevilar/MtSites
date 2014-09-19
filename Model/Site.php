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

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'required' => true, 'allowEmpty' => false,
                'message' => 'Favor de ingresar un alias.'
            ),
            'unique_name' => array(
                'rule' => array('isUnique', 'name'),
                'message' => 'Este alias ya esta en uso.'
            ),
            'name_min' => array(
                'rule' => array('minLength', '3'),
                'message' => 'El alias debe de tener por lo menos 3 caracteres.'
            ),
            'name_urlvalida' => array(
                'rule' => 'url',
                'message' => 'El url resultante no sera valido, revise que contenga caracteres validos.'
            )
        )
    );


	public function aliasConvert ( $text ) {
		return strtolower( Inflector::slug( $text ) );
	}

	public function beforeSave( $options = array() ) {
		$alias = $this->aliasConvert( $this->data['Site']['name'] );
		$ran = null;

		do{
			// buscar un alias disponible
			if ( is_null( $ran ) ) {				
				// first run
				$ran = true;
			} else {
				// is not first run
				$ran = substr( String::uuid(), (int)rand(0, 16), (int)rand(4, 7));
				debug($ran);
				$alias = $this->aliasConvert( $alias . "_" . $ran );
			}

			// buscar para ver si existe
			$this->recursive = -1;
			$site = $this->findByAlias( $alias );
			
		} while ( !empty($site) );
		$this->data['Site']['alias'] = $alias;
		return true;
	}

	public function hasUser ( $site_alias, $user_id ) {
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
	    			'Site' => array(
	    				'conditions' => array(
	    					'Site.alias' => $site_alias
	    					)
	    				)
	    		)
	    	));


		return !empty($sites['Site']);

	}


	/**
	 *  Me devuelve todos los sitios del usuario
	 * @param int $id UserId
	 */	
	public function fromUser ( $user_id ) {
		
	    $sites = $this->User->find('first', array(
	    		'conditions' => array(
	    			'User.id' => $user_id
	    			),
	    		'contain' => array(
	    			'Site',
	    		)
	    	));

		if (!empty( $sites['Site'] )) {
			return $sites['Site'];
		} else {
			return null;
		}
	}
}
