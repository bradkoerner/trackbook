<?php

namespace App\TopTracks;

use App\Lastfm\LastfmInterface;
use GuzzleHttp\Client;

class BillboardGlobal implements TopTracksInterface {

    private $client;
	private $era;

	public function __construct($era) {
		$this->era = 'semester.2012.fall.date';//$era.'.date';
		$this->client = new Client([
			'base_uri' => 'http://billboard.modulo.site/rank/song/'
		]);
	}
	
	public function getTopTracks() {
        $compiled_tracks = $this->compileTracks();
        return $this->sortTracks($compiled_tracks);
	}

    private function compileTracks() {
        $compiled_tracks = [];

        for ($i = 1; $i <= 30; $i++) {
            $response = $this->client->get($i.'?from='.config('times.'.$this->era)[0].'&to='.config('times.'.$this->era)[1]);

            $tracks = json_decode($response->getBody());

            foreach ($tracks as $track) {
                if (array_key_exists($track->song_id, $compiled_tracks)) {
                    $compiled_tracks[$track->song_id]['count']++;
                } else {
                    $compiled_tracks[$track->song_id] = [
                        'count' => 1,
                        'song'  => $track
                    ];
                }
            }
        }
        
        return $compiled_tracks;
    }

    private function sortTracks($compiled_tracks) {
        usort($compiled_tracks, function($a, $b) {
            return strnatcasecmp($b['count'], $a['count']);
        });

        return $compiled_tracks;
    }

}