<?php

namespace App\Http\Controllers;

use App\TopTracks\LastfmUser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
    	return view('home');
    }

    public function lastfm(Request $request) {
    	$lastfm = new LastfmUser($request->input('username', ''));
    	return $lastfm->getTopTracks();
    }
}
