<?php

namespace App\Http\Modules;

use Bluefin\YahooGemini\Gemini;
use Bluefin\YahooGemini\YahooOAuth2;

use App\Token;

class Connecters
{
  protected $consumerKey;
  protected $consumerSecret;
  protected $ObjectItem;

  public function __construct(){

    $this->consumerKey = env('CONSUMER_KEY');
    $this->consumerSecret =  env('CONSUMER_SECRET');
    $this->gn = new Gemini;
  }
  public function saveToken($token)
  {
    $tokens = empty(Token::first())? new Token:Token::first();

    $tokens->access_token = $token->access_token;
    $tokens->refresh_token = $token->refresh_token;
    $tokens->expires_in = date("H:i:s", $token->expires_in + strtotime(date('H:i:s',  time())));

    $tokens->save();
  }
  public function getToken($location)
  {
    return $this->gn->newToken($this->consumerKey, $this->consumerSecret, $location);
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
    $yah = new YahooOAuth2;
    $refreshToken = $this->getRefreshToken();
    $redirect_uri = " ";

    return $yah->update_access_token($this->consumerKey, $this->consumerSecret, $redirect_uri, $refreshToken);
  }
  public function checkTokenExpiry()
  {
    if((strtotime(date('H:i:s',  time())) + 600)> strtotime($this->getExpiry()))
    {
      $tokens = $this->updateToken();
      $this->saveToken($tokens);
    }
  }
  public function getRequest($client, $object, $parameter)
   {
    //  echo 'https://api.gemini.yahoo.com/v2/rest/'.$object.'/'.$parameter;
     $index = 500;
     $itemsCount = $this->getResponse($client, $object, 'count'.$parameter);
     $result = $this->getResponse($client, $object, $parameter);

     while ($index<$itemsCount)
     {
       $result = array_merge($result, $this->getResponse($client, $object, $parameter,'&si='.$index));
       $index+=500;
     }
     return $result;
   }

  private function getResponse($client, $object, $parameter, $index='')
    {
      $token = $this->getAccessToken();
      $response  = $client->request('GET', 'https://api.gemini.yahoo.com/v2/rest/'.$object.'/'.$parameter.$index,
      [
        'headers'  => ['Authorization' =>'Bearer '.$token]
      ]);
      return json_decode($response->getBody())->response;
    }

   public function putRequest($client,$object,$parameter)
   {
     $token = $this->getAccessToken();
     $response  = $client->request('PUT', 'https://api.gemini.yahoo.com/v2/rest/campaign',
     [
       'headers'  => ['Authorization' =>'Bearer '.$token],
       'json'  => [
         'id' =>352666689,
         'budget' =>50
       ]
     ]);
     return '<pre>'.print_r(json_decode($response->getBody()),  true).'</pre>';
   }

   function deleteRequest($client, $object, $items)
   {
     $token = $this->getAccessToken();
     foreach ($items as $item) {
       $response  = $client->request('PUT', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
       [
         'headers'  => ['Authorization' =>'Bearer '.$token],
         'json'  => [
           'id' =>$item->id,
           'status' =>"DELETED"
         ]
       ]);
     }
     return '<pre>'.print_r(json_decode($response->getBody()),  true).'</pre>';
   }

   function postRequest($client, $object, $items, $id)
   {
     $token = $this->getAccessToken();
     $this->saveObjectItem($client, $object, $id);
     switch($object)
     {
       case 'campaign':
        foreach($items as $item)
          {
            if($this->itemExist($client, $item->campaignName, $object, $id, 'campaignName'))
            {
              $response  = $client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
              [
                'headers'  => ['Authorization' =>'Bearer '.$token],
                'json'  => [
                      "status" =>$item->status,
                      "campaignName" =>  $item->campaignName,
                      "budget" =>$item->budget,
                      "language" => $item->language,
                      "budgetType" =>$item->budgetType,
                      "advertiserId" => $id,
                      "channel" =>$item->channel,
                      "objective" => $item->objective,
                      "isPartnerNetwork" => $item->isPartnerNetwork,
                      "advancedGeoPos" => $item->advancedGeoPos,
                      "advancedGeoNeg" =>$item->advancedGeoNeg
                ]
               ]);
            }
         }
         break;

     case 'adgroup':
      $bidSet  =  new \stdClass;
      $bid  =  new \stdClass;
      $this->saveObjectItem($client, 'campaign', '1494748');
      //TODO
      $this->saveObjectItem($client, 'campaign', $id);
      foreach($items as $item)
        {
           $itemBids = $item->bidSet->bids[0];
           $bid->priceType =  $itemBids->priceType;
           $bid->value  =  $itemBids->value;
           $bid->channel  =  $itemBids->channel;
           $bidSet->bids  =  [ $bid ];
           $campaignId = $this->getId('campaign', 'campaignName', $item->campaignId, $id);

           if(!empty($campaignId)&& $this->itemExist($client, $item->adGroupName, $object, $id, 'adGroupName')){
             $response  = $client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
             [
               'headers'  => ['Authorization' =>'Bearer '.$token],
               'json'  => [
                     "status" =>$item->status,
                     "campaignId" =>$campaignId,
                     "bidSet" => $bidSet,
                     "advertiserId" => $id,
                     "adGroupName" =>  $item->adGroupName,
                     "startDateStr" => $item->startDateStr,
                     "endDateStr" => $item->endDateStr,
                     "ecpaGoal" =>$item->ecpaGoal,
                     "biddingStrategy" =>$item->biddingStrategy
               ]
             ]);
           }
         }
         break;

     case 'ad':
         $this->saveObjectItem($client, 'campaign', '1494748');
         //TODO
         $this->saveObjectItem($client, 'campaign', $id);
         $this->saveObjectItem($client, 'adgroup', '1494748');
         //TODO
         $this->saveObjectItem($client, 'adgroup', $id);
        foreach($items as $item)
        {
          $item=$items[0];
          $campaignId = $this->getId('campaign', 'campaignName', $item->campaignId, $id);
          $adGroupId = $this->getId('adgroup', 'adGroupName', $item->adGroupId, $id);
          if(!empty($adGroupId) && !empty($campaignId) && $this->itemExist($client, $item->title, $object, $id, 'title'))
          {
            $response  = $client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
            [
              'headers'  => ['Authorization' =>'Bearer '.$token],
              'json'  => [
                    "title" =>$item->title,
                    "landingUrl" =>  $item->landingUrl,
                    "imageUrl" =>$item->imageUrl,
                    "displayUrl" => $item->displayUrl,
                    "sponsoredBy" =>$item->sponsoredBy,
                    "adGroupId" =>$adGroupId,
                    "campaignId" =>$campaignId,
                    "advertiserId" => $id,
                    "description" => $item->description,
                    "status" => $item->status,
                    "imageUrlHQ" => $item->imageUrlHQ,
                    "contentUrl" =>$item->contentUrl
              ]
             ]);
          }
       }
       break;
     case 'keyword':
      $bidSet  =  new \stdClass;
      $bid  =  new \stdClass;
      $this->saveObjectItem($client, 'adgroup', '1494748');
      //TODO
      $this->saveObjectItem($client, 'adgroup', $id);
      foreach($items as $item)
        {
          $itemBids = $item->bidSet->bids[0];
          $bid->priceType =  $itemBids->priceType;
          $bid->value  =  $itemBids->value;
          $bid->channel  =  $itemBids->channel;
          $bidSet->bids  =  [ $bid ];
          $parentId = $this->getId('adgroup', 'adGroupName', $item->parentId, $id);
          if(empty($parentId)){
            echo "Make sure all Ad groups are synced!";
            break;
          }
         $response  = $client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
         [
           'headers'  => ['Authorization' =>'Bearer '.$token],
           'json'  => [
                 "advertiserId" => $id,
                 "parentType" =>  'ADGROUP',
                 "parentId" =>$parentId ,
                 "value" => $item->value,
                 "matchType" =>$item->matchType,
                 "exclude" =>$item->exclude,
                 "status" =>$item->status,
                 "bidSet" => $bidSet
           ]
          ]);
       }
       break;
     }
    //  return '<pre>'.print_r(json_decode($response->getBody()),  true).'</pre>';
   }

   public function saveObjectItem($client, $object, $id)
   {
     $this->ObjectItem[$object][$id]  =  $this->getRequest($client,  $object, '?advertiserId='.$id);
   }

   public function getObjectItem($object, $id)
   {
     return $this->ObjectItem[$object][$id];
   }

   public function getId($object, $name, $itemId, $id)
   {
     $Id = "";
     $sourceItem = $this->getObjectItem($object, '1494748');
     //TODO
     $item = $this->getObjectItem($object, $id);

     $itemName = array_filter($sourceItem, function($sourceItem)use($itemId){
       return $sourceItem->id == $itemId;
     });
     if(!empty($itemName)) $itemName  =  end($itemName)->$name;

     $Id = array_filter($item, function($item)use($itemName, $name){
       return $item->$name == $itemName;
     });
    if(!empty($Id)) $Id  =  end($Id)->id;
    return $Id;
   }

   public function itemExist($client, $itemName , $object, $id, $name)
   {
     $item = $this->getObjectItem($object, $id);

     if(empty($item)) return true;

     $itemExist=array_filter($item,function($item)use($itemName,$name){
       return $item->$name===$itemName;
     });
     return empty($itemExist);
   }
}
?>
