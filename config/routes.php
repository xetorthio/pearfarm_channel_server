<?php

	/**
	* This File is ment for creating your routes
	* see: http://wiki.github.com/jetviper21/nimble/routing for more information
	* ex.
	* Route::resources("form");
	* R('')->controller('test')->action('index')->on('GET');
	*/

	R('')->controller('LandingController')->action('index')->on('GET');
	R('/channel')->controller('ChannelController')->action('index')->on('GET');
?>