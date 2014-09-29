<h1><?php echo count($sites); ?> Sitios Disponibles</h1>
<div class="list-group">
<?php
foreach ($sites as $s ) {
?>
	<div class="list-group-item">

		<?php 

    	
    	echo $this->Form->postLink('X', array( 'tenant' => false, 'plugin'=>'install' ,'controller' => 'site_setup', 'action' => 'deletesite/'.$s['Site']['alias']), array(
		'confirm' => 'Are you sure want to delete site named '.$s['Site']['name'].'?',
		'class'=>'btn btn-danger btn-sm pull-right',
		'title' => __("Eliminar")
		));

		?>

		<?php 

		echo  $this->Html->link( __('Ir a %s', $s['Site']['name']) , array( 'tenant' => $s['Site']['alias'], 'plugin'=>'risto' ,'controller' => 'pages', 'action' => 'display', 'dashboard' ), array('class'=>'' ));
		?>
		<i><b><?php echo __('alias')?>:</b> <?php echo $s['Site']['alias'] ?></i><?php echo " (".__('%s usuarios', count($s['User'])).")" ?>
		
		<?php if ( count($s['User']) ) { ?>
		<div>
			<b>Usuarios:</b><br>
			<?php 
			$users = [];
			foreach ($s['User'] as $u) {
				$users[] = $u['username'];
			}
			echo implode( ", ", $users);
			 ?> 
		</div>
    	<?php } ?>

	</div>
<?php
}

?>
</div>