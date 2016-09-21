<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bluefin\YahooGemini\Gemini;
use Bluefin\YahooGemini\GeminiAuthManager;

class sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync {object} {sourceId=1494748} {advertiserId=1494860}';

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
    public function __construct(GeminiAuthManager $gmAuth)
    {
        parent::__construct();
        
        $gmAuth->checkTokenExpiry();
        $token = $gmAuth->getAccessToken();
        $this->gm = new Gemini($token);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->gm->sync($this->argument('object'), $this->argument('sourceId'),$this->argument('advertiserId'));
    }
}
