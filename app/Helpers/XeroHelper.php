<?php
namespace App\Helpers;


class XeroHelper
{
    public static function getXeroInstance($user_xero_account)
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => env('XERO_CLIENT_ID'),
            'clientSecret'            => env('XERO_CLIENT_SECRET'),
            'redirectUri'             => env('APP_URL').'/settings/xero/account',
            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
        ]);

        $new_access_token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $user_xero_account->refresh_token
        ]);

        $user_xero_account->access_token = $new_access_token->getToken();
        $user_xero_account->refresh_token = $new_access_token->getRefreshToken();
        $user_xero_account->update();

        $config = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken((string)$new_access_token);
        return new \XeroAPI\XeroPHP\Api\AccountingApi(
            new \GuzzleHttp\Client(),
            $config
        );
//        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
//            'clientId'                => env('XERO_CLIENT_ID'),
//            'clientSecret'            => env('XERO_CLIENT_SECRET'),
//            'redirectUri'             => env('APP_URL').'/settings/xero/account',
//            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
//            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
//            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
//        ]);
//
//        $new_access_token = $provider->getAccessToken('refresh_token', [
//            'refresh_token' => $refresh_token
//        ]);
//
//        $config = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken((string)$new_access_token);
//        return new \XeroAPI\XeroPHP\Api\AccountingApi(
//            new \GuzzleHttp\Client(),
//            $config
//        );
    }
}
