<?php
namespace App\Traits;


trait InteractsWithMarketResponses
{
    /**
     * Decode the corresponding response;
     * @return \stdClass
     */
    public function decodeResponse($response)
    {
        $decoded_response= json_decode($response);
        return $decoded_response->data ?? $decoded_response;
    }


    /**
     * Resolve when the request faills
     * @return void
     */
    public function checkIfErrorResponse($response)
    {
        //If the response has errors throw an exception
        if(isset($response->error))
        {
            throw new \Exception("Something falied:{$response->error}");

        }
    }
}
