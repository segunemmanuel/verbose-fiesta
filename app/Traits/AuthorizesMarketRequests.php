<?php
namespace App\Traits;

use App\Services\MarketAuthService;

trait AuthorizesMarketRequests
{

    /**
     * @resolve authorization
     * @return [type] [description]
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $accessToken = $this->resolveAccessToken();
        $headers['Authorization'] = $accessToken;
    }



   /**
     * Resolve a valid access token to use
     * @return string
     */
    public function resolveAccessToken()
    {
        $authenticationService=resolve(MarketAuthService::class);
        // return 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiOTFlYmNkMzNlNjNlNjVjNjRkOWIwOTZjNDc3MzFhNTFjMDBkYWRmYjE4YmNhMzg4ZTliNDQ1NzJhZmNiYzEwMTdmYTQ0NmM4YzAzMjNhYjYiLCJpYXQiOiIxNjYxMjA2MDAxLjAwODA4MiIsIm5iZiI6IjE2NjEyMDYwMDEuMDA4MDg2IiwiZXhwIjoiMTY5Mjc0MjAwMS4wMDI1MjEiLCJzdWIiOiIxMjIyIiwic2NvcGVzIjpbInB1cmNoYXNlLXByb2R1Y3QiLCJtYW5hZ2UtcHJvZHVjdHMiLCJtYW5hZ2UtYWNjb3VudCIsInJlYWQtZ2VuZXJhbCJdfQ.ulZI_F35mZvqY1f4SvVA0j6Tj_jBVZ0oyIbWsMTcx-FN-0lFOo9l6BV1uKoYbzDj4iPIyKoqtCnWvszVjUYQ1o8sfhpjh-Du6WgSJSOg-zJY1cWtrzmT26IoZcse8l9euIfrqdjkMD1O8MLSO_lv5D0fDNakOYMNLiQLNE-4-S9UR6QyT3Z_gFgJ1fnMcBer6Rv_hPWFl_OMCy_aIgJXcBbm08FlJwA0Tc1sjppJmYC9HVWPx6HQXc2ailRqUkvwJ9G53tWMbUtxVxWkpKaQJ4NbxHh7fqDHFSddXjDwpyQkQLQl_0St4q8wE86feEYcd4V-sH7lf3ptUbqqdc7nZbrU_LxQuMUFWj7WrPJx8bM7UgQcsIPE5MUJT9UsYql8qzZJz6M0weBThXRG6mtrOr-ghmQyDCU-zJSZTsQevIN5H0ZDUBXnr19surpJvbGqE9tNjCGpD9tXcqqAIuDYvyShF56VtchW3f0_Oj2vNea3ZDHmhpK-44r4NfGK7XNG322aLYyWHn85FY-aT_gX3cqRoY2BuXxMMoOrzMH6W3E2oaeqst9vo6YwN5BE_a2DryhrLxgOdxpaML2DP_iiksEVstmFk5KAVRSRDzy7H06tXzn_ytZDxpLLdR_JaSSIZ3zFhLmy6RCmdJCM4ulGMSbJbiG4hocMAmloLEnwpE8';
    return $authenticationService->getClientCredentialsToken();
    }


}
