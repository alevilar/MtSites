<?php $currentClass = "paso-current-".$current; ?>
	<div class="center" id="mt-sites-pasos">
	<div class="row">
		<hr>

		<div class="pasos-list">
			<div class="title col-sm-2">
					Setup
			</div>

			<div class="paso paso-1  col-sm-3 <?php echo $currentClass?>">
				<div class="arrow"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
				<div class="numero">1</div>
				<div class="descripcion">Datos de la Empresa</div>
			</div>

			<div class="paso paso-2  col-sm-3 <?php echo $currentClass?>">
				<div class="arrow"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
				<div class="numero">2</div>
				<div class="descripcion"><?php echo Inflector::pluralize( Configure::read('Mesa.tituloMozo') )?></div>
			</div>

			<div class="paso paso-3  col-sm-3 <?php echo $currentClass?>">
				<div class="arrow"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
				<div class="numero">3</div>
				<div class="descripcion">Productos</div>
			</div>

			<div class="title col-sm-1 <?php echo $currentClass?>">
					Fin
			</div>
		</div>
	</div>

	<div class="row">
		<div class="clearfix"></div>
		<div class="nav">
			<div class="col-sm-4">
				<?php
				if (!empty($backLink) ) {
					echo $this->Html->link(
						'<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>'
						.'Volver', $backLink, array(
						'class' => 'btn btn-default btn-block btn-lg',
						'escape' => false,
					));
				}
				?>
			</div>
			<div class="col-sm-4"></div>
			<div class="col-sm-4">

				<?php
				if (!empty($nextLink) ) {
					echo $this->Html->link(
						'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
						.'Continuar', $nextLink, array(
						'class' => 'btn btn-primary btn-block btn-lg',
						'escape' => false,
					));
				}
				?>


				<?php 
				if ( !empty($formId)) {
					echo $this->Form->button(__('Continuar').'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					', array('type'=>'submit',
						'class'=>'btn btn-success btn-lg btn-block',
						'form' => $formId,
					));
				}
				 ?>
			</div>
		</div>
	</div>
	</div>
<?php echo $this->Html->css('MtSites.pasos');?>