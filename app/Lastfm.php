<?php

namespace App;

use GuzzleHttp\Client;

class Lastfm {

	private $client;
	private $username;
	private $era;

	public function __construct($username) {
		$this->username = $username;
		$this->era      = 'semester.2012.fall';
		$this->client = new Client([
			'base_uri' => 'http://ws.audioscrobbler.com/2.0/',
			'timeout'  => 2.0
		]);
	}
	
	public function getTopTracks() {
		// $weekly_chart_list = $this->client->get('?api_key='.config('lastfm.api_key').'&format=json&method=user.getWeeklyChartList&user='.$this->username);

		// $range = $this->findRange(json_decode($weekly_chart_list->getBody()->getContents())->weeklychartlist->chart);

		$result = $this->client->get('?api_key='.config('lastfm.api_key').'&format=json&method=user.getWeeklyTrackChart&user='.$this->username.'&from='.config('lastfm.'.$this->era)[0].'&to='.config('lastfm.'.$this->era)[1]);

		return array_slice(json_decode($result->getBody()->getContents())->weeklytrackchart->track, 0, 30);
	}

	// Don't need this since full date ranges work for getWeeklyTrackChart
	private function findRange($weekly_chart_list) {
		$range = [
			'from' => '',
			'to'   => ''
		];

		foreach($weekly_chart_list as $week) {

			if ($week->to >= config('lastfm.'.$this->era)[0] && $range['from'] == '') {
				$range['from'] = $week->from;
			}

			if ($week->to >= config('lastfm.'.$this->era)[1]) {
				$range['to'] = $week->to;
				break;
			}
		}

		return $range;
	}
}