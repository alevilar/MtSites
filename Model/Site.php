<?php

App::uses('RistoAppModel', 'Risto.Model');
App::uses('Validation', 'Utility');
App::uses('Folder', 'Utility');
App::uses('Installer', 'Install.Utility');


/**
 * Site Model
 *
 */
class Site extends RistoAppModel {

    private  $info;

	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'Users.User',
			'joinTable' => 'sites_users',
			'foreignKey' => 'site_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),	
	);


    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'required' => true, 'allowEmpty' => false,
                'message' => 'Favor de ingresar un nombre del comercio.'
            ),
            'unique_name' => array(
                'rule' => array('isUnique', 'name'),
                'message' => 'Este nombre del comercio ya está en uso.'
            ),
            'name_min' => array(
                'rule' => array('minLength', '3'),
                'message' => 'El nombre del comercio debe de tener por lo menos 3 caracteres.'
            ),
            'name_urlvalida' => array(
                'rule' => 'checkurl',
                'message' => 'La url del comercio no será valido, revise que contenga caracteres válidos.'
            ),
            'characters' => array(
                'rule' => array('custom', '/^[a-z0-9 ]*$/i'),
                'message'  => 'Solo se admiten caracteres alfanuméricos con/sin espacios para el nombre del comercio.'
            )
        ),
        'type' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'required' => true, 'allowEmpty' => false,
                'message' => 'Favor de seleccionar un tipo de sitio.'
            )
        )
    );

    public function checkurl($check)
    {
        $check['Site']['name'] = "http://$_SERVER[HTTP_HOST]/".Inflector::slug($this->data['Site']['name']);
        return Validation::url($check['Site']['name'],true);
    }

	public function aliasConvert ( $text ) {
		return strtolower( Inflector::slug( $text ) );
	}

	public function beforeSave( $options = array() ) {
		$this->data['Site']['alias'] = $this->__buscarAliasName( $this->data['Site']['name'] );

		return $this->__crearEstructuratenant( $this->data['Site']['alias'] );
	}


	/**
	*
	*	Crea las carpetas y la base de datos para el tenant
	*
	*	@param string $siteName nombre del Sitio
	*	@return boolean true si pudo ser creado todo perfectamente
	*
	**/
	private function __crearEstructuratenant ( $alias ) {
		try {
			Installer::createTenantsDir( $alias );
			Installer::copyTenantSettingFile( $this->data );
			Installer::dumpTenantDB( $this->data );
		} catch (Exception $e) {
			$this->validationErrors['Installer']['msg'] = $e->getMessage();
			$this->log('Error creando estructura tenant '.$e->getMessage() );
			Installer::deleteSite($alias);	  
			return false;
		}
		return true;
	}

	/**
	*
	*	Busca un nombre de alias. Si existiese, entonces agrega  un valor aleatorio para garantizar que
	* 	sea unico
	*	@param string $siteName nombre del Sitio
	*	@return string
	**/
	private function __buscarAliasName ( $siteName ) {
		$alias = $this->aliasConvert( $siteName );
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
		return $alias;

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



    public function beforeDelete($cascade = true) {
    	$s = $this->find('first', array(
            'conditions' => array('Site.id' => $this->id),
            'recursive' => -1,
        ));

    	return Installer::deleteSite($s['Site']['alias']);	       	       
    }
}