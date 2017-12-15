<?php

namespace App\TopTracks;

use App\Lastfm\LastfmInterface;

class BillboardGlobal implements TopTracksInterface
{
	private $era;

	public function __construct($era) {
		$this->era    = 'semester.2012.fall.date';//$era.'.date';
	}
	
	public function getTopTracks() {
        
	}

}