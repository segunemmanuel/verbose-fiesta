<?php
namespace App\Services;

use App\Traits\ConsumeExternalServices;
use App\Traits\InteractsWithMarketResponses;

class MarketAuthService
{
    use ConsumeExternalServices, InteractsWithMarketResponses;
     /**
     * The URL to send the requests
     * @var string
     */
    protected $baseUri;

    /**
     * The client id to identify the client in ther API
     * @var string
     */
    protected $clientId;

    /**
     * The client secret to identify the client in ther API
     * @var string
     */
    protected $clientSecret;

    /**
     * The client id to identify the password client in ther API
     * @var string
     */
    protected $passwordClientId;
    /**
     * The client secret to identify the password client in ther API
     * @var string
     */
    protected $passwordClientSecret;
    /**
     * The URL to send the request
     * @var string
     */
    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
        $this->clientId = config('services.market.client_id');
        $this->clientSecret = config('services.market.client_secret');
        $this->passwordClientId = config('services.market.password_client_id');
        $this->passwordClientSecret = config('services.market.password_client_secret');
    }

    /**
     * obtain the access token for the client
     *
     */

    public function getClientCredentialsToken()
    {
        if($token = $this->existingValidToken()){
                return $token;
        }
        $formParams=[
            'grant_type'=>'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];
         $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData,'client_credentials');

        return $tokenData->access_token;
        // "{$tokenData->token_type} {$tokenData->access_token}";
    }





    /**
     * Generate the URL to obtain users authorization
     * @return string
     */
    public function resolveAuthorizationUrl()
    {
        $query=http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => route('authorization'),
            'response_type' => 'code',
            'scope' => 'purchase-product manage-products manage-account read-general',
        ]);

        return "{$this->baseUri}/oauth/authorize?{$query}";

    }



    /**
     * obtain access token from speifici code
     * @return stdClass
     */
    public function getCodeToken($code)
    {

        $formParams = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => route('authorization'),
            'code' => $code,
        ];

        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'authorization_code');

        return $tokenData;
    }




    /**
     * obtain access token from speifici code
     * @return stdClass
     */
    public function getPasswordToken($username,$password)
    {

        $formParams = [
            'grant_type' => 'password',
            'client_id' => $this->passwordClientId,
            'client_secret' => $this->passwordClientSecret,
            'username' => $username,
            'password' => $password,
            'scope' => 'purchase-product manage-products manage-account read-general',
        ];

        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'password');

        return $tokenData;
    }











    /**
     * Stores a valid token with some attributes
     * @return void
     */
    public function storeValidToken($tokenData,$grantType)
    {
        $tokenData->token_expires_at=now()->addSeconds($tokenData->expires_in-5);
        $tokenData->access_token="{$tokenData->token_type} {$tokenData->access_token}";
        $tokenData->grant_type=$grantType;
        session()->put(['current_token'=>$tokenData]);
    }

    /**
     * Verify if there is any valid token on session
     * @return string\boolean
     */
    public function existingValidToken()
    {
        if(session()->has('current_token'))
        {
            $tokenData=session()->get('current_token');
            if(now()->lt($tokenData->token_expires_at)){
                return $tokenData->access_token;
            }
        }
        return false;
    }

}
