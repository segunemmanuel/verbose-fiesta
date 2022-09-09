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

    /**
     * Obtains the list of products cform the API
     */
    public function getProducts()
    {
        return $this->makeRequest('GET','/products');
    }


    /**
     * Retrieve the user information from the API
     * @return stdClass
     */
    public function getUserInformation()
    {
        return $this->makeRequest('GET', 'users/me');
    }



    /**
     * Obtains the list of products cform the API
     */
    public function getCategories()
    {
        return $this->makeRequest('GET','categories');
    }

    /**
     * Get by id
     * @param integer $id
     * @return stdClass
     */
    public function getProduct($id)
    {
        return $this->makeRequest('GET', "products/{$id}");
    }

    /**
     * Get product category
     * @param integer $id
     * @return stdClass
     */
    public function getCategoryProduct($id)
    {
        return $this->makeRequest('GET', "categories/{$id}/products");
    }


}
