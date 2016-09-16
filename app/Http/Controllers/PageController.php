<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Modules\Connecters;
use App\Jobs\Sync;
use Bluefin\YahooGemini\GeminiAuthManager;

class PageController extends Controller
{
    //
    public function home(Connecters $cn){
      $gm = new GeminiAuthManager();
      $gm->checkTokenExpiry();

        $object='advertiser';
        $id='';
        return view('default', [
          'requests' => $cn->getRequest($object,$id),
          'title' => 'Home Page'
        ]);
    }
    public function sync($id,$object,Connecters $cn){
      $gm = new GeminiAuthManager();
      $gm->checkTokenExpiry();

      $parameter="?advertiserId=1494748";
      //TODO
      if($object=='keyword')
      {
        $adGroup=$cn->getRequest('adgroup','?advertiserId=1494748');
        $items=[];
        foreach ($adGroup as $adGroups)
        {
          $items = array_merge($items,$cn->getRequest($object,"?parentId=".$adGroups->id."&parentType=ADGROUP"));
        }

      }
      else
      {
        $items = $cn->getRequest($object,$parameter);
      }
      return '<pre>'.print_r($items, true).'</pre>';
      $cn->postRequest($object,$items,$id);
      return "Sync completed";
    }
    public function delete($id,$object,Connecters $cn){
      $gm = new GeminiAuthManager();
      $gm->checkTokenExpiry();

      $parameter="?advertiserId=".$id;
      $items=$cn->getRequest($object,$parameter);
      $cn->deleteRequest($object,$items);
      return "All items are deleted";
    }
    public function objects($id,Connecters $cn){
      return view('objects',[
        'title' => 'Objects',
        'id'=>$id
      ]);
    }
    public function new(GeminiAuthManager $gm) {
      $tokens=$gm->newAccessToken('new');
      $gm->saveToken($tokens);

      return view('token',[
        "title"=> 'New Token Created',
        "expiry"=>$gm->getExpiry()
      ]);
    }
    public function update(GeminiAuthManager $gm)
    {
      $tokens=$gm->updateAccessToken();
      $gm->saveToken($tokens);

      return view('token',[
        "title"=> 'Token updated',
        "expiry"=>$gm->getExpiry()
      ]);
    }
    public function test(Connecters $cn,$object='campaign',$id=1494748) {
      $gm = new GeminiAuthManager();
      $gm->checkTokenExpiry();

      $parameter="?advertiserId=".$id;
      // $parameter=$id;
      if($object=='keyword')
      {
        $parameter="?parentId=".$id."&parentType=ADGROUP";
      }
      $items=$cn->getRequest($object,$parameter);
      return '<pre>'.print_r($items, true).'</pre>';
    }
}
