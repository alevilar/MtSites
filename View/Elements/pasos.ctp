<?php $currentClass = "paso-current-".$current; ?>
<div class="col-md-12" id="mt-sites-pasos">
	<hr>
	<div class="title">
			Setup
	</div>
	<div class="paso paso-1 <?php echo $currentClass?>">
		<div class="arrow"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
		<div class="numero">1</div>
		<div class="descripcion">Datos de la Empresa</div>
	</div>

	<div class="paso paso-2 <?php echo $currentClass?>">
		<div class="arrow"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
		<div class="numero">2</div>
		<div class="descripcion">Salón de Ventas</div>
	</div>

	<div class="btn-siguiente">
			
			<?php echo $this->Form->button(__('Continuar').'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			', array('type'=>'submit','class'=>'btn btn-success btn-lg pull-right',
				'style' => 'margin-right: 15px;'
			)) ?>
	</div>
</div>

<?php echo $this->Html->css('MtSites.pasos');?>