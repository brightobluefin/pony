<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bluefin\YahooGemini\Gemini;
use Bluefin\YahooGemini\GeminiAuthManager;

class delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete {object} {advertiserId=1494860}';

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
        $this->gm->delete($this->argument('object'), $this->argument('advertiserId'));
    }
}
