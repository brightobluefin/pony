<?php

namespace App\Http\Modules\src\Entities;

use Bluefin\YahooGemini\GeminiAuthManager;
use GuzzleHttp\Client;

use App\Token;

class GeminiEntities
{
  protected $consumerKey;
  protected $consumerSecret;
  protected $ObjectItem;
  protected $client;

  public function __construct()
  {
    $this->consumerKey = env('CONSUMER_KEY');
    $this->consumerSecret =  env('CONSUMER_SECRET');
    $this->gm = new GeminiAuthManager;
    $this->client = new Client;
  }
}
