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

    public function objects($id, $name){
      return view('objects',[
        'title' => 'Objects',
        'accountName' => $name,
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
    public function apiTest($object='',$param = '1494860')
    {
        //$us = urlencode('United States');
//         $param = '/?type=country';
        $gm = new Gemini();

        /** Reports */
        $uri = 'https://api.gemini.yahoo.com/v2/rest/reports/custom';
        $reports = $gm->makeReportRequest($object, $uri);
        return '<pre>'.print_r($reports, true).'</pre>';
        /** Report ends */

        /** Bulk */
//        $uri ='https://api.gemini.yahoo.com/v2/rest/bulk/download';
//      $items = $gm->getApiResponse($object, $uri);
//        $bulk = $gm->makeBulkDownloadRequest($object, $uri, $param);
//        return '<pre>'.print_r($bulk, true).'</pre>';
        /** Bulk ends */
    }
    public function status($token, $id = '1494860')
    {
        $gm = new Gemini();
        /** Reports */
//        $uri ='https://api.gemini.yahoo.com/v2/rest/bulk/status/?jobId=' . $token . '&advertiserId=' . $id;
//        $bulk = $gm->getBulkReports('advertiser', $uri);
//        return '<pre>'.print_r($bulk, true).'</pre>';
        /** Report ends */

        /** Bulk */
        $uri ='https://api.gemini.yahoo.com/v2/rest/reports/custom/' . $token . '?advertiserId=' . $id;
        $reports = $gm->getReports('advertiser', $uri);
        return '<pre>'.print_r($reports, true).'</pre>';
        /** Bulk ends */
    }
    public function downloadBulk($token){
        $uri ='https://api.gemini.yahoo.com/v2/rest/bulk/read/?resource=' . $token;
        $gm = new Gemini();
        $bulk = $gm->downloadBulk('advertiser', $uri);
        return $bulk;
    }
    public function delete($id = '1494860', $object = 'keyword')
    {
        $gm = new Gemini();
        $status = $gm->delete($object, $id);
        return empty($status)?'Nothing to delete':$status;
    }
}
