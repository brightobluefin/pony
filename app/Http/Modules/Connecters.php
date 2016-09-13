<?php

namespace App\Http\Modules;

use Bluefin\YahooGemini\Gemini;
use Bluefin\YahooGemini\YahooOAuth2;

use App\Token;

class Connecters
{
  protected $consumerKey;
  protected $consumerSecret;

  public function __construct(){

    $this->consumerKey=env('CONSUMER_KEY');
    $this->consumerSecret= env('CONSUMER_SECRET');
    $this->gn=new Gemini;
  }
  public function saveToken($token)
  {
    $tokens=empty(Token::first())? new Token:Token::first();

    $tokens->access_token=$token->access_token;
    $tokens->refresh_token=$token->refresh_token;
    $tokens->expires_in=date("H:i:s",$token->expires_in + strtotime(date('H:i:s', time())));

    $tokens->save();
  }
  public function getToken($location)
  {
    return $this->gn->newToken($this->consumerKey,$this->consumerSecret,$location);
  }
  public function getAccessToken()
  {
    return Token::first()->access_token;
  }
  public function getRefreshToken()
  {
    return Token::first()->refresh_token;
  }
  public function getExpiry()
  {
    return Token::first()->expires_in;
  }
  public function updateToken()
  {
    $yah=new YahooOAuth2;
    $refreshToken=$this->getRefreshToken();
    $redirect_uri=" ";

    return $yah->update_access_token($this->consumerKey,$this->consumerSecret,$redirect_uri,$refreshToken);
  }
  public function checkTokenExpiry()
  {
    if((strtotime(date('H:i:s', time())) + 600)> strtotime($this->getExpiry()))
    {
      $tokens=$this->updateToken();
      $this->saveToken($tokens);
    }
  }
  function getRequest($client,$object,$parameter)
   {
     $token=$this->getAccessToken();
     $response =$client->request('GET','https://api.gemini.yahoo.com/v2/rest/'.$object.'/'.$parameter,
     [
       'headers' => ['Authorization'=>'Bearer '.$token]
     ]);
     return json_decode($response->getBody())->response;
   }

   function putRequest($token,$client)
   {
     $response =$client->request('PUT','https://api.gemini.yahoo.com/v2/rest/campaign',
     [
       'headers' => ['Authorization'=>'Bearer '.$token],
       'json' => [
         'id'=>352666689,
         'budget'=>50
       ]
     ]);
     return '<pre>'.print_r(json_decode($response->getBody()), true).'</pre>';
   }

   function postRequest($client,$object,$values)
   {
     $token=$this->getAccessToken();
     $response =$client->request('POST','https://api.gemini.yahoo.com/v2/rest/'.$object,
     [
       'headers' => ['Authorization'=>'Bearer '.$token],
       'json' => [
         "status"=>$values['status'],
         "campaignName"=>  $values['campaignName'],
         "budget"=> $values['budget'],
         "language"=> $values['language'],
         "budgetType"=> $values['budgetType'],
         "advertiserId"=> $values['advertiserId'],
         "channel"=>$values['channel'],
         "objective"=> $values['objective'],
         "isPartnerNetwork"=> $values['isPartnerNetwork'],
         "advancedGeoPos"=> $values['advancedGeoPos'],
         "advancedGeoNeg"=> $values['advancedGeoNeg']
       ]
     ]);
     return '<pre>'.print_r(json_decode($response->getBody()), true).'</pre>';
   }
}
?>
