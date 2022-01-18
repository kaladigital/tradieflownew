<?php

namespace App\Helpers;

class WiseHelper
{
    private $is_live_mode = true;
// sandbox
//    public $personal_profile_id = '16327675';
//    public $business_profile_id = '16327676';

//live
    public $personal_profile_id = '4731668';
    public $business_profile_id = '4731685';

    /**Sandbox*/
    public $sandbox_profile_details_url = 'https://api.sandbox.transferwise.tech/v1/profiles';
    public $sandbox_create_quote_url = 'https://api.sandbox.transferwise.tech/v2/quotes';
    public $sandbox_recipient_create_url = 'https://api.sandbox.transferwise.tech/v1/accounts';
    public $sandbox_transfer_create_url = 'https://api.sandbox.transferwise.tech/v1/transfers';
    public $sandbox_fund_transfer_url = 'https://api.sandbox.transferwise.tech/v3/profiles';

    /**Live*/
    public $live_profile_details_url = 'https://api.transferwise.com/v1/profiles';
    public $live_create_quote_url = 'https://api.transferwise.com/v2/quotes';
    public $live_recipient_create_url = 'https://api.transferwise.com/v1/accounts';
    public $live_transfer_create_url = 'https://api.transferwise.com/v1/transfers';
    public $live_fund_transfer_url = 'https://api.transferwise.com/v3/profiles';

    protected function makeAPICall($url, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer '.env('WISE_API_KEY')
        ];

        if ($data) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch));
        curl_close($ch);
        return $res;
    }

    public function getProfileID()
    {
        return $this->makeAPICall($this->is_live_mode ? $this->live_profile_details_url : $this->sandbox_profile_details_url);
    }

    public function createQuote($data)
    {
        $url = $this->is_live_mode ? $this->live_create_quote_url : $this->sandbox_create_quote_url;
        return $this->makeAPICall($url, $data);
    }

    public function createRecipient($data)
    {
        $url = $this->is_live_mode ? $this->live_recipient_create_url : $this->sandbox_recipient_create_url;
        return $this->makeAPICall($url, $data);
    }

    public function createTransfer($data)
    {
        $url = $this->is_live_mode ? $this->live_transfer_create_url : $this->sandbox_transfer_create_url;
        return $this->makeAPICall($url, $data);
    }

    public function fundTransfer($transfer_id, $data)
    {
        $url = $this->is_live_mode ? $this->live_fund_transfer_url : $this->sandbox_fund_transfer_url;
        $url .= '/'.$this->business_profile_id.'/transfers/'.$transfer_id.'/payments';
        return $this->makeAPICall($url, $data);
    }
}
