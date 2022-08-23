<?php

namespace App\Traits;

use GuzzleHttp\Client;
trait ConsumeExternalServices{

 /**
  * Send requesy to anyu external service
  *@return \stdClass|string
  */

// resolve api client to be consumed
  public function makeRequest($method, $requestUrl,$queryParams=[],$formParams=[],$headers=[])
  {
     $client= new Client([
        'base_uri'=>$this->baseUri,

     ]);
    if(method_exists($this,'resolveAuthorization'))
    {
    $this->resolveAuthorization($queryParams,$formParams,$headers);

    }
    $response=$client->request($method,$requestUrl,[
    'query'=>$queryParams,
    'formParams'=>$formParams,
    'headers'=>$headers,
    ]);

    $response=$response->getBody()->getContents();

    if(method_exists($this,'decodeResponse'))
    {
    $this->decodeResponse($response);
    }
    return $response;

  }

}
