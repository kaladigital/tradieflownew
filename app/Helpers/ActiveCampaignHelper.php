<?php
namespace App\Helpers;

class ActiveCampaignHelper
{
    private $active_campaign_api_url;
    private $active_campaign_api_token;

    /**Tags*/
    public $free_trial_tag_id = '308';
    public $purchase_tag_id = '310';
    public $expired_tag_id = '312';

    /**Stages*/
    public $email_subscriber_stage_id = '109';
    public $free_trial_stage_id = '110';
    public $paying_subscriber_stage_id = '113';

    /**Pipeline*/
    public $pipeline_id = '25';

    /**Default Owner*/
    public $owner_id = '14';

    public function __construct(){
        $this->active_campaign_api_url = env('ACTIVE_CAMPAIGN_API_URL');
        $this->active_campaign_api_token = env('ACTIVE_CAMPAIGN_API_KEY');
    }

    protected function makeAPICall($url, $data = [], $delete_call = false, $update_call = false)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $this->active_campaign_api_url.$url);

        if ($data) {
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data));
        }

        if ($delete_call) {
            curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        if ($update_call) {
            curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Api-Token: '.$this->active_campaign_api_token
        ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch));
        curl_close($ch);
        return $res;
    }

    public function getContactByEmail($email)
    {
        $response = $this->makeAPICall('/api/3/contacts?email='.urlencode($email));
        return count($response->contacts) ? $response->contacts['0']->id : null;
    }

    public function addContact($data)
    {
        $response = $this->makeAPICall('/api/3/contacts',$data);
        return (isset($response->contact->id)) ? $response->contact->id : null;
    }

    public function getStages($offset, $limit)
    {
        $params = [];
        if ($offset) {
            $params[] = 'offset='.$offset;
        }

        if ($limit) {
            $params[] = 'limit='.$limit;
        }

        $response = $this->makeAPICall('/api/3/dealStages'.($params ? '?'.implode('&',$params) : ''));
        var_dump($response);die;
    }

    public function addDeal($data)
    {
        $response = $this->makeAPICall('/api/3/deals',$data);
        return (isset($response->deal->id)) ? $response->deal->id : null;
    }

    public function pipeLineDetails($id)
    {
        $response = $this->makeAPICall('/api/3/dealGroups/'.$id);
        return $response;
    }

    public function pipeLineUsers($id)
    {
        $response = $this->makeAPICall('/api/3/dealGroups/'.$id.'/dealGroupUsers');
        return $response;
    }

    public function getContactDetails($id)
    {
        $response = $this->makeAPICall('/api/3/contacts/'.$id);
        return $response;
    }

    public function getContactTags($id)
    {
        $response = $this->makeAPICall('/api/3/contacts/'.$id.'/contactTags');
        return $response->contactTags;
    }

    public function getTags($offset, $limit)
    {
        $params = [];
        if ($offset) {
            $params[] = 'offset='.$offset;
        }

        if ($limit) {
            $params[] = 'limit='.$limit;
        }

        $response = $this->makeAPICall('/api/3/tags'.($params ? '?'.implode('&',$params) : ''));
        return $response;
    }

    public function addContactTag($contact_id, $tag_id)
    {
        $data = [
            'contactTag' => [
                'contact' => $contact_id,
                'tag' => $tag_id
            ]
        ];
        $response = $this->makeAPICall('/api/3/contactTags',$data);
        return $response;
    }

    public function removeContactTag($id)
    {
        $response = $this->makeAPICall('/api/3/contactTags/'.$id,[],true);
        return $response;
    }

    public function removeContactTags($contact_id, $tags)
    {
        $contact_tags = $this->getContactTags($contact_id);
        if ($contact_tags) {
            foreach ($contact_tags as $item) {
                if (in_array($item->tag, $tags)) {
                    $this->removeContactTag($item->id);
                }
            }
        }

        return true;
    }

    public function getDealDetails($id)
    {
        $response = $this->makeAPICall('/api/3/deals/'.$id);
        return $response;
    }

    public function updateDeal($id, $data)
    {
        $response = $this->makeAPICall('/api/3/deals/'.$id, $data, false, true);
        return $response;
    }
}
