<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class billboard_favorites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billboard_favorites:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and process Billboard top tracks to determine top overall track for era.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    }
}
