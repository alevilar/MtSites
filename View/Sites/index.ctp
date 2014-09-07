<h1><?php echo count($sites); ?> Sitios Disponibles</h1>
<ul>
<?php
foreach ($sites as $s ) {
?>
	<li><?php  echo $s['Site']['name']. " <i><b>".__('alias').":</b> ".$s['Site']['alias']."</i> (".__('%s usuarios', count($s['User'])).")"?></li>
<?php
}

?>
</ul>