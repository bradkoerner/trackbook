<?php

namespace App\Http\Controllers;

use App\TopTracks\BillboardGlobal;
use App\TopTracks\LastfmUser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
    	return view('home');
    }

    public function lastfm(Request $request) {
        $username = $request->input('username');
    	if ($username != '') {
    		$service = new LastfmUser($request->input('username'), '');
    	} else {
    		$service = new BillboardGlobal('');
    	}
    	
    	return $service->getTopTracks();
    }
}
