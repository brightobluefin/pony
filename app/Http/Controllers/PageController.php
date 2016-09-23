<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Jobs\Sync;
use Bluefin\YahooGemini\GeminiAuthManager;
use Bluefin\YahooGemini\Gemini;
use Artisan;

class PageController extends Controller
{
    //
    public function home(){
      $gmAuth = new GeminiAuthManager();
      $gmAuth->checkTokenExpiry();
      $gm = new Gemini($gmAuth->getAccessToken());

        return view('default', [
          'requests' => $gm->getData('advertiser'),
          'title' => 'Home Page'
        ]);
    }
    public function sync($advertiserId,$object){
        $sourceId = '1499756';

        $gmAuth = new GeminiAuthManager();
        $gmAuth->checkTokenExpiry();
        $gm = new Gemini($gmAuth->getAccessToken());
        return $gm->sync($object, $sourceId, $advertiserId);
//      Artisan::call('command:sync', [
//        'object' => $object,
//        'sourceId' => $sourceId,
//        'advertiserId' => $advertiserId
//      ]);

        return "Sync completed";
    }
    public function delete($advertiserId, $object){
      $gmAuth = new GeminiAuthManager;
      $gmAuth->checkTokenExpiry();
      $gm = new Gemini($gmAuth->getAccessToken());
      return $gm->delete($object, $advertiserId);

      // Artisan::call('command:delete', [
      //   'object' => $object,
      //   'advertiserId' => $advertiserId
      // ]);

      return "All items are deleted";
    }
    public function objects($id){
      return view('objects',[
        'title' => 'Objects',
        'id'=>$id
      ]);
    }
    public function newToken(GeminiAuthManager $gmAuth) {
      $tokens=$gmAuth->newAccessToken('new');
      $gmAuth->saveToken($tokens);

      return view('token',[
        "title"=> 'New Token Created',
        "expiry"=>$gmAuth->getExpiry()
      ]);
    }
    public function update(GeminiAuthManager $gmAuth)
    {
      $tokens = $gmAuth->updateAccessToken();
      $gmAuth->saveToken($tokens);

      return view('token',[
        "title"=> 'Token updated',
        "expiry"=>$gmAuth->getExpiry()
      ]);
    }
    public function test($object='campaign',$parameter = '1499756&type') {
        $gmAuth = new GeminiAuthManager();
        $gmAuth->checkTokenExpiry();
        $gm = new Gemini($gmAuth->getAccessToken());

        if($object != 'keyword') $parameter = "?advertiserId=".$parameter;

        $items = $gm->getData($object, $parameter);

      return '<pre>'.print_r($items, true).'</pre>';
    }
}
