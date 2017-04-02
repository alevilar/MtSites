<?php
/**
 * Copyright 2010 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="users form">
	<?php echo $this->Form->create("SiteUser", array('type'=>'post')); ?>
		<fieldset>

			<legend><?php echo __d('users', 'Añadir usuario en %s', $site['Site']['name']); ?></legend>
			


			<?php
				echo $this->Form->hidden('user_id');
				echo $this->Form->hidden('site_id');

				echo $this->Form->input('Rol', array(
					'options' => $roles, 
					'class' => 'form-control', 
					'empty' => 'Seleccionar rol del usuario'
					));
			?>
			<br>

			<?php echo $this->Form->button(__('Añadir usuario existente en mi comercio'), array('class'=>'btn btn-primary')); ?>		

			<?php echo $this->Html->link(__('Cancelar'), array('action'=>'index'), array('class' => 'btn btn-default pull-right')); ?>

	
	<?php echo $this->Form->end(); ?>
</div>
