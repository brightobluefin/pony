<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Modules\Connecters;
use GuzzleHttp\Client;

class Sync implements  ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        \Log::info('it worked');
        // $this->$cn->postRequest($this->client,$this->object,$this->items,$this->id);
    }
}
