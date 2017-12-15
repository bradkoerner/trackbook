<?php

namespace App\Console\Commands;

use App\Billboard;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetBillboardTop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billboard_top:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and process Billboard top tracks to determine top overall track for era.';

    /**
     * HTTP client
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'base_uri' => 'http://billboard.modulo.site/rank/song/'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $compiled_tracks = $this->compileTracks();
        $track_list      = $this->sortTracks($compiled_tracks);
        $this->storeTracks($track_list);
    }

    /**
     * Get top Billboard tracks from every week within era
     */
    private function compileTracks() {
        $compiled_tracks = [];

        for ($i = 1; $i <= 40; $i++) {
            $response = $this->client->get($i.'?from='.config('times.semester.2012.fall.date')[0].'&to='.config('times.semester.2012.fall.date')[1]);

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

    /**
     * Sort tracks by number of occurrences in top tracks
     *
     * @param array $compiled_tracks Unsorted list tracks
     *
     * @return array Sorted top tracks
     */
    private function sortTracks($compiled_tracks) {
        usort($compiled_tracks, function($a, $b) {
            return strnatcasecmp($b['count'], $a['count']);
        });

        return array_slice($compiled_tracks, 0, 30);
    }

    /**
     * Save top tracks
     *
     * @param array $track_list
     *
     * @return boolean
     */
    private function storeTracks($track_list) {
        DB::transaction(function () use ($track_list) {
            for ($i = 0; $i < count($track_list); $i++) {
                Billboard::create([
                    'season'     => 'fall',
                    'year'       => '2012',
                    'metric'     => 'semester',
                    'rank'       => $i+1,
                    'artist'     => $track_list[$i]['song']->display_artist,
                    'track'      => $track_list[$i]['song']->song_name,
                    'spotify_id' => $track_list[$i]['song']->spotify_id,
                    'count'      => $track_list[$i]['count']
                ]);
            }
        });
    }
}
