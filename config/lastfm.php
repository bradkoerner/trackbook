<?php

return [
	'name'      => 'Trackbook',
	'api_key'   => env('LASTFM_API_KEY'),
	'secret'    => env('LASTFM_SECRET'),
	'registred' => 'Kroensburg',

	// Epoc times
	'season' => [
	],
	'semester' => [
		'2012' => [
			'spring' => [],
			'summer' => [],
			'fall'   => [1344988800, 1356998400]
		]
	]
];