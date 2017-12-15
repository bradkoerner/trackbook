<?php

namespace App\TopTracks;

use App\Lastfm\LastfmInterface;
use GuzzleHttp\Client;

class LastfmUser implements TopTracksInterface {

	private $client;
	private $username;
	private $era;

	public function __construct($username, $era) {
		$this->username = $username;
		$this->era      = 'semester.2012.fall.epoch';//$era.'.epoch';
		$this->client = new Client([
			'base_uri' => 'http://ws.audioscrobbler.com/2.0/',
			'timeout'  => 2.0
		]);
	}
	
	public function getTopTracks() {
		// $weekly_chart_list = $this->client->get('?api_key='.config('lastfm.api_key').'&format=json&method=user.getWeeklyChartList&user='.$this->username);

		// $range = $this->findRange(json_decode($weekly_chart_list->getBody()->getContents())->weeklychartlist->chart);

		$response = $this->client->get('?api_key='.config('lastfm.api_key').'&format=json&method=user.getWeeklyTrackChart&user='.$this->username.'&from='.config('times.'.$this->era)[0].'&to='.config('times.'.$this->era)[1]);

		$top_tracks = json_decode($response->getBody());

		if (property_exists($top_tracks, 'error')) {
			return $top_tracks->message;
		}

		return $this->limitResponse($top_tracks, 30);
	}

	private function limitResponse($top_tracks, $limit) {
		return array_slice($top_tracks->weeklytrackchart->track, 0, $limit);
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