<?php

/*
$validTenant = '[a-z0-9-]+';
Router::connect('/:tenant'
		, array('controller' => 'home')
		, array(
			'tenant' => $validTenant,
			'persist' => 'tenant',
			));
Router::connect('/:tenant/:controller'
		, array()
		, array(
			'tenant' => $validTenant,
			'persist' => 'tenant',
			));

Router::connect('/:tenant/:controller/:action/*'
		, array()
		, array(
			'tenant' => $validTenant,
			'persist' => 'tenant',
			));

unset($validTenant);*/