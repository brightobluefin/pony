<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Bluefin\YahooGemini\GeminiAuthManager;
use Bluefin\YahooGemini\Gemini;
use Artisan;

class PageController extends Controller
{
    public function home(){
      $gmAuth = new GeminiAuthManager();
      $gmAuth->checkTokens();
      $gm = new Gemini($gmAuth->getAccessToken());

        return view('default', [
          'requests' => $gm->getData('advertiser'),
          'title' => 'Home Page'
        ]);
    }
    public function sync($advertiserId,$object){
        $sourceId = '1499756';
        $gm = new Gemini();
        $gm->sync($object, $sourceId, $advertiserId);
//      Artisan::call('command:sync', [
//        'object' => $object,
//        'sourceId' => $sourceId,
//        'advertiserId' => $advertiserId
//      ]);

        return "Sync completed";
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
    public function test($object='campaign',$parameter = '1494860') {
        $gm = new Gemini();

        $items = $gm->getData($object, $parameter);

      return '<pre>'.print_r($items, true).'</pre>';
    }
    public function apiTest($object='',$param = '')
    {
        //$us = urlencode('United States');
        // $param = '/?type=country';
        $uri ='https://api.gemini.yahoo.com/v2/rest/reports/custom';
        $gm = new Gemini();
        $reports = $gm->makeReportRequest($object, $uri);
//      $items = $gm->getApiResponse($object, $uri);

        return '<pre>'.print_r($reports, true).'</pre>';
    }
    public function status($token, $id = '1494860')
    {
        $uri ='https://api.gemini.yahoo.com/v2/rest/reports/custom/' . $token . '?advertiserId=' . $id;
        $gm = new Gemini();
        $reports = $gm->getReports('advertiser', $uri);
        return '<pre>'.print_r($reports, true).'</pre>';

    }
    public function delete($id = '1494860', $object = 'keyword')
    {
      $gm = new Gemini();
      return $gm->delete($object, $id);
    }
}
