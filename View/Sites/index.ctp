<h1>Sitios Disponibles</h1>
<ul>
<?php

foreach ($sites as $s ) {
?>
	<li><?php  echo $s['Site']['name']?></li>
<?php
}

?>
</ul>