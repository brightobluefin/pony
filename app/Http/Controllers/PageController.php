<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use GuzzleHttp\Client;

use App\Http\Requests;
use App\Http\Modules\Connecters;

class PageController extends Controller
{
    //
    public function home(Connecters $cn){
        $cn->checkTokenExpiry();
        $client = new Client();
        $object='advertiser';
        $id='';
        return view('default', [
          'requests' => $cn->getRequest($client,$object,$id),
          'title' => 'Home Page'
        ]);
    }
    public function sync($id,$object,Connecters $cn){
      $client = new Client();
      $parameter="?advertiserId=1494748";
      $items=$cn->getRequest($client,$object,$parameter);

      for ($i=0;$i<count($items);$i++)
      {
        $values['status']=$items[$i]->status;
        $values['campaignName']=$items[$i]->campaignName;
        $values['budget']=$items[$i]->budget;
        $values['language']=$items[$i]->language;
        $values['budgetType']=$items[$i]->budgetType;
        $values['advertiserId']=$id;
        $values['channel']=$items[$i]->channel;
        $values['objective']=$items[$i]->objective;
        $values['isPartnerNetwork']=$items[$i]->isPartnerNetwork;
        $values['advancedGeoPos']=$items[$i]->advancedGeoPos;
        $values['advancedGeoNeg']=$items[$i]->advancedGeoNeg;
        $cn->postRequest($client,$object,$values);
      }
      return $items=$cn->getRequest($client,$object,"?advertiserId=1494860");
    }
    public function objects($id,Connecters $cn){
      return view('objects',[
        'title' => 'Objects',
        'id'=>$id
      ]);
    }
    public function new(Connecters $cn) {
      $tokens=$cn->getToken('new');
      $cn->saveToken($tokens);

      return view('token',[
        "title"=> 'New Token Created',
        "expiry"=>$cn->getExpiry()
      ]);
    }
    public function update(Connecters $cn)
    {
      $tokens=$cn->updateToken();
      $cn->saveToken($tokens);

      return view('token',[
        "title"=> 'Token updated',
        "expiry"=>$cn->getExpiry()
      ]);
    }
    public function test(Connecters $cn) {
      $cn->checkTokenExpiry();
      $client = new Client();
      return $cn->getRequest($client,$object='campaign',$id='');
    }

       // $response = new Request('GET','https://api.gemini.yahoo.com/v2/rest/campaign/352666758',['Authorization'=>'Bearer o9tT.6qZs10ivZJEnhAVCQ8gYnuWsxy0gayJ5IRdnkQpO7IUzoALJEKt0UquZXn4bl6nPMpDt9gTYu5VMjRINhJY8TuSu.oepuWCOEWgltmhlnsZvcXuK2is6.eMms1j8ZHQ9DpeyP3LkCPXEMZgVBJL97dq_QWfsg0waZfG7c4rxbPjBIiJ4pZunJB_CbneA4InofLT32In28lDaI.CYTOIPxoptYADdIKnN0VS.UlNR8MzSA3bMM.HJv21PNdL.51gTxMYvD.iojMzHwiFmtFgdQQ7M3JySVMDtNHHQ4ubUvCmz8j4sJ6MhNaVKX0jVsiaPgWSJl58mfcVKx_MEeadX67hYriKa56H8su194X2Ws7rhvjj4BQNYsgt5PWyxRKwyMn6ArgI3sGvgAtsheQ_3ph.3PAxiVskTch4FZPtloCVSSMsMqt8.KhdgQkzjnoKDDNoXM1.3rl49beJiU4wzAzrP6kgVEmXH8ygCQoz4nMcg.ujkLAVGknDGyuqjudzoPARfnHJbmLj0nvfq3O5rjjYfBwC7_kC3E4iEscwVeKzlif9_YXfTlntOSwP3ENiAqWVYWcK70HxyaaBLs1llQfR4hsKXNh9C.fMCqzFM0EK2HPESt2vODpemz2uXt2eux_2ZlpXeEKoKNgFrToYpBtn.ZhI6hTATHGmB1uPxdDqc60n8D4DKeiaupZltv.Z.ovHNl4Xd0WcpQB0cyov19UqHTHzjkwCLITwzSYppg7y96UROMUZMhIEeKZLq5oFefWuYxmXnEdr7qPGj3cIdskGz6on9PSdBC0fylg4Ik9YYT69xwIUS.etF6Dvr2EwHTF1eh.hZIcTdQ6YZBzRg4o1vgjnkwOtHe8-']);
       //$response = $client->requestAsync('GET','https://api.gemini.yahoo.com/v2/rest/campaign/352666758',['Authorization'=>'Bearer xxg9WyyZs126zhTphUBlNFX1_k4MUqZq1SjsOCTlOgaCIb5_sBCte8WPHmOL6XsBLODUfUWKQLAhMd3V6I41iRZMenSTVNqck3pXq2CyzQcQUSQEVh9y7LnyuNSRGsw.m1_67vTbWQqCYalAaSI8k6MF8J6qKjRvG0AcSJX6wc3FE5AZ._0Ttust.h1TZAhJvGknFEZNAFr4aGbcEOuYLTLr68_ZUYlCfPFY0rY19o8I.pFt2GK3YWcjBCKoqFaiePmEFzUQDzplg5Jb8M2SdhhtG9Y__T3JDKh8eI9hUQVEBX_QKmbFxQoxw_ULkVluggMooOhs81pJxby9oTtRD1sbdrndyYUDRwPAo40U.S98UZd7RdPresr24VQCZdxtiYR4AKzwUDP4Qju2q0E0TvBA0d.ZoTiDknz0PG7COLjKaF9WzURVo2xi3Orn6PFcEJ5Q02UbA7wrQUiXLWgvGYkJOklpyMqX83Sp5C22A2Qv57RRbPJDgg9qX7bQCRUo2vDpbTuFu5WzsxO30mnGKX.zC7Gvqo3u8rMvK24bTsxTu9zyD..TYCoP749GpIv9MyP5zg0SS1uYvxzwZ.HBM0PxL0G94E05scMrHX4vNu3h5_LewQmfHhjs3R0a0DJzMglSGK2IoLF6rjF2zNoDlFv9OyQqA7AtvRm5QH2OJ04p2lUZpBa.gcfwidYc2splsdgrKjZ1xnJNoqj2tiAl6Q23b8rG772WtFTZntcV9IbhVaMiaX.opJvvrv1DGjjjHwkHVDgb8T3esG8Vcwi8zJEB95i_34cdlWsAAntgRRdC48qEWuth0K1XLku8rtcJsN4M7nbp5nJCXyxxpPRqpaMCrb_y_S4M8Bb2']);
       // return print_r($response->getBody()->getRequestTarget(),true);
       // return '<pre>'.print_r(get_class_methods($response),true).'</pre>';
}
