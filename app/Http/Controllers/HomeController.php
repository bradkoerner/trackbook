<?php

namespace App\Http\Controllers;

use App\Lastfm;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
    	return view('home');
    }

    public function lastfm(Request $request) {
    	$lastfm = new Lastfm($request->input('username'));
    	return $lastfm->getTopTracks();
    }
}
