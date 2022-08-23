<?php
namespace App\Services;

use App\Traits\AuthorizesMarketRequests;
use App\Traits\ConsumeExternalServices;
use App\Traits\InteractsWithMarketResponses;

class MarketService
{
    use ConsumeExternalServices, InteractsWithMarketResponses,
                                 AuthorizesMarketRequests;

    /**
     * The URL to send the request
     * @var string
     */
    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
    }


}
