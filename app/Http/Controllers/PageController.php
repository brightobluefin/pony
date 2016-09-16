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
      $cn->checkTokenExpiry();
      $client = new Client();
      $parameter="?advertiserId=1494748";
      //TODO
      $items=$cn->getRequest($client,$object,$parameter);
      $cn->postRequest($client,$object,$items,$id);
      return "Sync completed";
    }
    public function delete($id,$object,Connecters $cn){
      $cn->checkTokenExpiry();
      $client = new Client();
      $parameter="?advertiserId=".$id;
      $items=$cn->getRequest($client,$object,$parameter);
      return $cn->deleteRequest($client,$object,$items);
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
    public function test(Connecters $cn,$object='campaign',$id=1494748) {
      $cn->checkTokenExpiry();
      $client = new Client();
      $parameter="?advertiserId=".$id;
      // $parameter=$id;
      if($object=='keyword')
      {
        $parameter="?parentId=".$id."&parentType=ADGROUP";
      }
      $items=$cn->getRequest($client,$object,$parameter);
      return '<pre>'.print_r($items, true).'</pre>';
    }
}
