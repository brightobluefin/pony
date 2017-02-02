<?php

namespace App\Console\Commands;

use Bluefin\YahooGemini\Gemini;
use Illuminate\Console\Command;

class Delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pony:delete {object} {id=1494860}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $gm = new Gemini();
        $gm->delete($this->argument('object'), $this->argument('id'));
    }
}
