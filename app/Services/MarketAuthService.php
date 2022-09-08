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
        $formParams=[
            'grant_type'=>'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];
         $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);
        return "{$tokenData->token_type} {$tokenData->access_token}";
    }

}
