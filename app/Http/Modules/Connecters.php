<?php

namespace App\Http\Modules;

use Bluefin\YahooGemini\GeminiAuthManager;
use GuzzleHttp\Client;



class Connecters
{
  protected $consumerKey;
  protected $consumerSecret;
  protected $ObjectItem;
  protected $client;

  public function __construct(){

    $this->consumerKey = env('CONSUMER_KEY');
    $this->consumerSecret =  env('CONSUMER_SECRET');
    $this->gm = new GeminiAuthManager;
    $this->client = new Client;
  }

  public function getRequest( $object, $parameter)
   {
    //  echo 'https://api.gemini.yahoo.com/v2/rest/'.$object.'/'.$parameter;
     $index = 500;
     $itemsCount = $this->getResponse( $object, 'count'.$parameter);
     $result = $this->getResponse( $object, $parameter);

     while ($index<$itemsCount)
     {
       $result = array_merge($result, $this->getResponse( $object, $parameter,'&si='.$index));
       $index+=500;
     }
     return $result;
   }

  private function getResponse( $object, $parameter, $index='')
    {
      $token = $this->gm->getAccessToken();
      $response  = $this->client->request('GET', 'https://api.gemini.yahoo.com/v2/rest/'.$object.'/'.$parameter.$index,
      [
        'headers'  => ['Authorization' =>'Bearer '.$token]
      ]);
      return json_decode($response->getBody())->response;
    }

   public function putRequest($object,$parameter)
   {
     $token = $this->gm->getAccessToken();
     $response  = $this->client->request('PUT', 'https://api.gemini.yahoo.com/v2/rest/campaign',
     [
       'headers'  => ['Authorization' =>'Bearer '.$token],
       'json'  => [
         'id' =>352666689,
         'budget' =>50
       ]
     ]);
     return '<pre>'.print_r(json_decode($response->getBody()),  true).'</pre>';
   }

   function deleteRequest( $object, $items)
   {
     $token = $this->gm->getAccessToken();
     foreach ($items as $item) {
       $response  = $this->client->request('PUT', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
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

   function postRequest( $object, $items, $id)
   {
     $token = $this->gm->getAccessToken();
     $this->saveObjectItem( $object, $id);
     switch($object)
     {
       case 'campaign':
        foreach($items as $item)
          {
            if($this->itemExist( $item->campaignName, $object, $id, 'campaignName'))
            {
              $response  = $this->client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
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
      $this->saveObjectItem( 'campaign', '1494748');
      //TODO
      $this->saveObjectItem( 'campaign', $id);
      foreach($items as $item)
        {
           $itemBids = $item->bidSet->bids[0];
           $bid->priceType =  $itemBids->priceType;
           $bid->value  =  $itemBids->value;
           $bid->channel  =  $itemBids->channel;
           $bidSet->bids  =  [ $bid ];
           $campaignId = $this->getObject('campaign', 'campaignName', $item->campaignId, $id)->id;

           if(!empty($campaignId)&& $this->itemExist( $item->adGroupName, $object, $id, 'adGroupName')){
             $response  = $this->client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
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
         $this->saveObjectItem( 'adgroup', '1494748');
         //TODO
         $this->saveObjectItem( 'adgroup', $id);
        foreach($items as $item)
        {
        $item=$items[0];
          $obj=$this->getObject('adgroup', 'adGroupName', $item->adGroupId, $id);
          $campaignId = $obj->campaignId;
          $adGroupId = $obj->id;
          if(!empty($adGroupId) && !empty($campaignId) && $this->itemExist( $item->title, $object, $id, 'title'))
          {
            $response  = $this->client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
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
      $this->saveObjectItem( 'adgroup', '1494748');
      //TODO
      $this->saveObjectItem( 'adgroup', $id);
      foreach($items as $item)
        {
          $itemBids = $item->bidSet->bids[0];
          $bid->priceType =  $itemBids->priceType;
          $bid->value  =  $itemBids->value;
          $bid->channel  =  $itemBids->channel;
          $bidSet->bids  =  [ $bid ];
          $parentId = $this->getObject('adgroup', 'adGroupName', $item->parentId, $id)->id;
          if(!empty($parentId) && $this->itemExist( $item->value, $object, $id, 'value'))
          {
            $response  = $this->client->request('POST', 'https://api.gemini.yahoo.com/v2/rest/'.$object,
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
       }
       break;
     }
    //  return '<pre>'.print_r(json_decode($response->getBody()),  true).'</pre>';
   }

   public function saveObjectItem( $object, $id)
   {
     $this->ObjectItem[$object][$id]  =  $this->getRequest(  $object, '?advertiserId='.$id);
   }

   public function getObjectItem($object, $id)
   {
     return $this->ObjectItem[$object][$id];
   }

   public function getObject($object, $name, $itemId, $id)
   {
     $obj = "";
     $sourceItem = $this->getObjectItem($object, '1494748');
     //TODO
     $item = $this->getObjectItem($object, $id);

     $itemName = array_filter($sourceItem, function($sourceItem)use($itemId){
       return $sourceItem->id == $itemId;
     });
     if(!empty($itemName)) $itemName  =  end($itemName)->$name;

     $obj = array_filter($item, function($item)use($itemName, $name){
       return $item->$name == $itemName;
     });
    if(!empty($obj)) return end($obj);
   }

   public function itemExist( $itemName , $object, $id, $name)
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
