<?php

namespace App\TopTracks;

use App\Lastfm\LastfmInterface;
use GuzzleHttp\Client;

class LastfmUser implements TopTracksInterface {

	/**
	 * HTTP client
	 *
	 * @var GuzzleHttp\Client
	 */
	private $client;

	/**
	 * LastFM username
	 *
	 * @var string
	 */
	private $username;

	/**
	 * Timeframe to be viewed (eg. Summer 2012)
	 *
	 * @var string
	 */
	private $era;

	/**
	 * @param string $username
	 * @param string $era
	 */
	public function __construct($username, $era) {
		$this->username = $username;
		$this->era      = 'semester.2012.fall.epoch';//$era.'.epoch';
		$this->client   = new Client([
			'base_uri' => 'http://ws.audioscrobbler.com/2.0/',
			'timeout'  => 2.0
		]);
	}
	
	/**
	 * Get top LastFM user tracks within era
	 *
	 * @return array top tracks
	 */
	public function getTopTracks() {
		$response = $this->client->get('?api_key='.config('lastfm.api_key').'&format=json&method=user.getWeeklyTrackChart&user='.$this->username.'&from='.config('times.'.$this->era)[0].'&to='.config('times.'.$this->era)[1]);

		$top_tracks = json_decode($response->getBody());

		if (property_exists($top_tracks, 'error')) {
			return $top_tracks->message;
		}

		return $this->limitResponse($top_tracks, 30);
	}

	/**
	 * Limit number of tracks in array
	 *
	 * @param array $top_tracks Top tracks from LastFM
	 * @param int   $limit      Number of tracks to limit to
	 *
	 * @return 
	 */
	private function limitResponse($top_tracks, $limit) {
		return array_slice($top_tracks->weeklytrackchart->track, 0, $limit);
	}
}