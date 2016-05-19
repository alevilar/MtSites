<?php echo $this->Html->script('/mt_sites/js/jstz.min', true); ?>

<?php echo $this->Html->css('/mt_sites/css/install'); ?>


<div id="new-site-creator">
<?php echo $this->Form->create('Site', array('url'=>array('plugin'=>'mt_sites', 'controller'=>'sites', 'action'=> 'install')));?>   

    <?php

    echo $this->Form->hidden('timezone', array('id'=>'timezone'));

    echo $this->Form->input('name', array(
        'label' => __d('install', 'Introducir el Nombre'),
        'placeholder' => __d('install', 'Ej: Mc Burger'),
        'id' => 'inpup-site-name',
        'class' => 'form-control input-lg',
        'data-url-check-name' => Router::url(array('plugin'=>'mt_sites','controller'=>'sites','action'=>'checkname'), true),
        'div' => array('style'=>'position:relative'),
        'after' => $this->Html->image('/risto/img/spinner.gif', array('class'=>'spinner'))
    ));
    

    if ( $this->Session->read('Auth.User.is_admin') ) {
        ?>
		<legend><?php echo __('Tipo de Comercio') ?></legend>
        <?php
        echo $this->Form->input('type', array(
                'options' => array(
                    'restaurant' => __('Gastronomía'),
                    'generic' => __('Retail'),
                    'hotel' => __('Hotelería'),
                ),
                'type' => 'radio',
                'legend' => false,
                'label' => false,
                'default' => 'restaurant',
            )
        );
    } else {
        // para todos los usuarios setear como tipo de comercio gastronomico por defecto
        echo $this->Form->hidden('type', array('value'=>'restaurant'));
    }
    

    echo $this->Form->submit(__d('install', 'Crear Comercio'), array(
                'id' => 'btn-form-submit',
                'class' => 'btn btn-lg  btn-success btn-block disabled', 'div' => false)); 

    ?>
    <br>
    <div id="alias-name-container" class="alert alert-success">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
        <small>Nombre correcto, ¡ya puedes crear tu comercio!<br>Podrás acceder directamente desde:</small><br>
        <span class="alias-url"><?php echo Router::url('/', true);?></span><strong id="alias-name" class="alias-name">nombre</strong>
        </div>
    


    <?php echo $this->Form->end(); ?>


<?php echo $this->Html->script('/mt_sites/js/install', true); ?>
</div>