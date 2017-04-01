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
<?php 
echo $this->Form->create("User"); 
echo $this->Form->input("id", array('value'=>$user_id)); 
?>
		<fieldset>

<?php echo $this->Form->input('site_id', array('class' => 'form-control', 'empty' => 'Seleccionar', 'label'=>__("Vincular con otro Comercio"))); ?>
<br>
		</fieldset>
	<?php echo $this->Form->button('Enviar', array('class'=>'btn btn-success')); ?>		
	<?php echo $this->Html->link(__('Cancelar'), array('action'=>'index'), array('class'=>'btn btn-default') ); ?>

	<?php echo $this->Form->end(); ?>
</div>
