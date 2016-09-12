<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Modules\YahooOAuth2;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;



Route::get('/', function () {

  define("CONSUMER_KEY","dj0yJmk9RnNCcjJyclVCaklQJmQ9WVdrOVJuVlZaWE5xTXpZbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD0zZg--");
  define("CONSUMER_SECRET","20b6e12b3ce07dc8080a92bb3f5c1e4a04585cee");
  $redirect_uri="http://".$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];//Or your other redirect URL - must match the callback domain
  $gemini_api_endpoint="https://api.admanager.yahoo.com/v1/rest";
  $oauth2client=new YahooOAuth2();
  if (isset($_GET['code'])){
  	$code=$_GET['code'];
  }
  else {
  	$code=0;
  }
  if($code){
  	 #oAuth 3-legged authorization is successful, fetch access token
  	 $token=$oauth2client->get_access_token(CONSUMER_KEY,CONSUMER_SECRET,$redirect_uri,$code);
  	 #Access token is available. Do API calls.
  	 $headers= array('Authorization: Bearer '.$token,'Accept: application/json','Content-Type: application/json');
  	 #Fetch Advertiser Name and Advertiser ID
  	 $url=$gemini_api_endpoint."/advertiser/";
  	 $resp=$oauth2client->fetch($url,$postdata="",$auth="",$headers);
  	 $jsonResponse = json_decode( $resp);
  	 $advertiserName = $jsonResponse->response[0]->advertiserName;
  	 $advertiserId = $jsonResponse->response[0]->id;
  	//  return sprintf('<pre>%s</pre>', print_r($oauth2client->fetch($url,$postdata,$auth,$headers), true));
    echo $token;
  }
  else {
      # no valid access token available, go to authorization server
      header("HTTP/1.1 302 Found");
      header("Location: " . $oauth2client->getAuthorizationURL(CONSUMER_KEY,$redirect_uri));
      exit;
  }
});
Route::get('/test', function () {

    $token="f8SOKo2Zs12fbxwN._KLoqtM5huFdUswoXKZfaWkFQk1s5OTDXxQkFn.BdyhcwLP.bVydYRzLdRpzk1lecq6NCcxZlrF_G98yQEXEBM9neFxNfAwmwtzh6P1jPf1TvKLjYCkt0YzjoHPWoJSBpdPCqiD3NZxFEJOoLEUXXjXzERvw8aT1Cu3EtApxUcmYYsnT5hRM6CgxZKgsyUT7uOkuw8qsxJ3ZhyGot_dtmSsfweEEQjKebZvX4LLMJm3kDVltmP6fcN4Ia.4uxizfFl0TVSAFpSKR9AiOKmGsYMRGLP7gRiqBCKB7CahrRzNht92AG.xDGbc8JkYfKELZpiVZ4PyNVzIQvwg521UJAV6pZiyPUhRNc9bSXce821KI6PWE050cU057QyPhSlSzOi92dVJuGc16vfAwdU1xXj.AtcRo0WC_80kTCDBL2BtyLic3S6KXW0gmvTvJOx.6su_bX5gjzHVlNLRIaqXmX.FVlGJ8CSrzHytLND5txe0yYGkwdZYiEb8JszACduJnBKxtT0qdEomYYecpxWo0Gb6.VUXwn3MiV1oRn4Ap2CxsTwucXPZH62u0Ayl9sdxcZMWBQDCyIQjN90mGHEw3ooIVTpQHVLb3IPusmMQMBPrHuaeVIbTam4MeyVDBpMt0M5nwj6iVzCG2.GsbRDEZ8CsQ_3_aF.cwJMpwdVjWPBjhwdgqgK10DkiS250XZ_DgsLha1krmFhJ4QDHQlP4vsiM5XjnwONzllNcf1YLhSiQnRCvviBLRRFamBIlfQhajixMmF_gIsAnEiatjMpkiJ3eX4Fs99_Ud3bdrNbURvtHwfrhKokSg2nWsBjPwsYB7RII1T_nXKdfUM__DvIWnG4-";
    $client = new Client();

   return postRequest($token,$client);
});

 function getRequest($token,$client)
  {
    $response =$client->request('GET','https://api.gemini.yahoo.com/v2/rest/campaign/352666689',
    [
      'headers' => ['Authorization'=>'Bearer '.$token]
    ]);
     return '<pre>'.print_r(json_decode($response->getBody()), true).'</pre>';
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

  function postRequest($token,$client)
  {
    $response =$client->request('POST','https://api.gemini.yahoo.com/v2/rest/campaign',
    [
      'headers' => ['Authorization'=>'Bearer '.$token],
      'json' => [
        "status"=>"PAUSED",
        "campaignName"=>"NativeAdsCampaign",
        "budget"=> 30,
        "language"=> "en",
        "budgetType"=> "LIFETIME",
        "advertiserId"=> 87292,
        "channel"=>"NATIVE",
        "objective"=> "PROMOTE_BRAND",
        "isPartnerNetwork"=> "TRUE",
        "advancedGeoPos"=> "DEFAULT",
        "advancedGeoNeg"=> "DEFAULT"
      ]
    ]);
    return '<pre>'.print_r(json_decode($response->getBody()), true).'</pre>';
  }


    // $response = new Request('GET','https://api.gemini.yahoo.com/v2/rest/campaign/352666758',['Authorization'=>'Bearer o9tT.6qZs10ivZJEnhAVCQ8gYnuWsxy0gayJ5IRdnkQpO7IUzoALJEKt0UquZXn4bl6nPMpDt9gTYu5VMjRINhJY8TuSu.oepuWCOEWgltmhlnsZvcXuK2is6.eMms1j8ZHQ9DpeyP3LkCPXEMZgVBJL97dq_QWfsg0waZfG7c4rxbPjBIiJ4pZunJB_CbneA4InofLT32In28lDaI.CYTOIPxoptYADdIKnN0VS.UlNR8MzSA3bMM.HJv21PNdL.51gTxMYvD.iojMzHwiFmtFgdQQ7M3JySVMDtNHHQ4ubUvCmz8j4sJ6MhNaVKX0jVsiaPgWSJl58mfcVKx_MEeadX67hYriKa56H8su194X2Ws7rhvjj4BQNYsgt5PWyxRKwyMn6ArgI3sGvgAtsheQ_3ph.3PAxiVskTch4FZPtloCVSSMsMqt8.KhdgQkzjnoKDDNoXM1.3rl49beJiU4wzAzrP6kgVEmXH8ygCQoz4nMcg.ujkLAVGknDGyuqjudzoPARfnHJbmLj0nvfq3O5rjjYfBwC7_kC3E4iEscwVeKzlif9_YXfTlntOSwP3ENiAqWVYWcK70HxyaaBLs1llQfR4hsKXNh9C.fMCqzFM0EK2HPESt2vODpemz2uXt2eux_2ZlpXeEKoKNgFrToYpBtn.ZhI6hTATHGmB1uPxdDqc60n8D4DKeiaupZltv.Z.ovHNl4Xd0WcpQB0cyov19UqHTHzjkwCLITwzSYppg7y96UROMUZMhIEeKZLq5oFefWuYxmXnEdr7qPGj3cIdskGz6on9PSdBC0fylg4Ik9YYT69xwIUS.etF6Dvr2EwHTF1eh.hZIcTdQ6YZBzRg4o1vgjnkwOtHe8-']);
    //$response = $client->requestAsync('GET','https://api.gemini.yahoo.com/v2/rest/campaign/352666758',['Authorization'=>'Bearer xxg9WyyZs126zhTphUBlNFX1_k4MUqZq1SjsOCTlOgaCIb5_sBCte8WPHmOL6XsBLODUfUWKQLAhMd3V6I41iRZMenSTVNqck3pXq2CyzQcQUSQEVh9y7LnyuNSRGsw.m1_67vTbWQqCYalAaSI8k6MF8J6qKjRvG0AcSJX6wc3FE5AZ._0Ttust.h1TZAhJvGknFEZNAFr4aGbcEOuYLTLr68_ZUYlCfPFY0rY19o8I.pFt2GK3YWcjBCKoqFaiePmEFzUQDzplg5Jb8M2SdhhtG9Y__T3JDKh8eI9hUQVEBX_QKmbFxQoxw_ULkVluggMooOhs81pJxby9oTtRD1sbdrndyYUDRwPAo40U.S98UZd7RdPresr24VQCZdxtiYR4AKzwUDP4Qju2q0E0TvBA0d.ZoTiDknz0PG7COLjKaF9WzURVo2xi3Orn6PFcEJ5Q02UbA7wrQUiXLWgvGYkJOklpyMqX83Sp5C22A2Qv57RRbPJDgg9qX7bQCRUo2vDpbTuFu5WzsxO30mnGKX.zC7Gvqo3u8rMvK24bTsxTu9zyD..TYCoP749GpIv9MyP5zg0SS1uYvxzwZ.HBM0PxL0G94E05scMrHX4vNu3h5_LewQmfHhjs3R0a0DJzMglSGK2IoLF6rjF2zNoDlFv9OyQqA7AtvRm5QH2OJ04p2lUZpBa.gcfwidYc2splsdgrKjZ1xnJNoqj2tiAl6Q23b8rG772WtFTZntcV9IbhVaMiaX.opJvvrv1DGjjjHwkHVDgb8T3esG8Vcwi8zJEB95i_34cdlWsAAntgRRdC48qEWuth0K1XLku8rtcJsN4M7nbp5nJCXyxxpPRqpaMCrb_y_S4M8Bb2']);
    // return print_r($response->getBody()->getRequestTarget(),true);
    // return '<pre>'.print_r(get_class_methods($response),true).'</pre>';
