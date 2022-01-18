<?php

namespace App\Console\Commands;

use App\Helpers\ActiveCampaignHelper;
use App\Helpers\Constant;
use App\Models\ActiveCampaignQueue;
use App\Models\UserActiveCampaignContact;
use App\Models\UserActiveCampaignDeal;
use App\Models\UserReferralMonthQueue;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class ActiveCampaignQueueEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active_campaign_queue_process_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ActiveCampaign Tags';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        ActiveCampaignQueue::with('User')
            ->where('type','=','tradieflow')
            ->where('status','=','pending')
            ->chunk(1000,function($items) {
                foreach ($items as $item) {

                    if ($item->action != 'email_subscriber' && !$item->User) {
                        $item->delete();
                        continue;
                    }

                    $user_active_campaign = UserActiveCampaignContact::where('user_id','=',$item->user_id)->first();

                    /**Process Tag Assign*/
                    try{
                        $active_campaign_helper = new ActiveCampaignHelper();

                        /**Get Contact*/
                        $active_campaign_contact_id = $active_campaign_helper->getContactByEmail($item->email);
                        if (!$active_campaign_contact_id) {
                            $name_obj = $item->User ? explode(' ',$item->User->name) : ['Email', 'Subscriber'];
                            $data = [
                                'contact' => [
                                    'email' => $item->email,
                                    'firstName' => $name_obj['0'],
                                    'lastName' => isset($name_obj['1']) ? $name_obj['1'] : ''
                                ]
                            ];

                            $active_campaign_contact_id = $active_campaign_helper->addContact($data);
                            if (!$active_campaign_contact_id) {
                                $item->delete();
                                continue;
                            }
                        }

                        /**Create New Contact*/
                        if (!$user_active_campaign) {
                            $user_active_campaign = new UserActiveCampaignContact();
                            $user_active_campaign->user_id = $item->user_id;
                        }

                        /**Update Contact ID*/
                        $user_active_campaign->active_campaign_contact_id = $active_campaign_contact_id;
                        $user_active_campaign->save();

                        $add_new_deal = false;
                        $deal_new_stage = null;

                        /**Process Actions*/
                        switch ($item->action) {
                            case 'email_subscriber':
                                $add_new_deal = true;
                                $deal_new_stage = $active_campaign_helper->email_subscriber_stage_id;
                            break;
                            case 'trial_tag':
                                /**Add Tags*/
                                $active_campaign_helper->addContactTag($active_campaign_contact_id, $active_campaign_helper->free_trial_tag_id);
                                $active_campaign_helper->removeContactTags($active_campaign_contact_id, [$active_campaign_helper->purchase_tag_id, $active_campaign_helper->expired_tag_id]);

                                $add_new_deal = true;
                                $deal_new_stage = $active_campaign_helper->free_trial_stage_id;
                            break;
                            case 'purchase_tag':
                                $active_campaign_helper->addContactTag($active_campaign_contact_id, $active_campaign_helper->purchase_tag_id);
                                $active_campaign_helper->removeContactTags($active_campaign_contact_id, [$active_campaign_helper->free_trial_tag_id, $active_campaign_helper->expired_tag_id]);

                                $add_new_deal = true;
                                $deal_new_stage = $active_campaign_helper->paying_subscriber_stage_id;
                            break;
                            case 'expired_tag':
                                $active_campaign_helper->addContactTag($active_campaign_contact_id, $active_campaign_helper->expired_tag_id);
                                $active_campaign_helper->removeContactTags($active_campaign_contact_id, [$active_campaign_helper->free_trial_tag_id, $active_campaign_helper->purchase_tag_id]);
                            break;
                        }

                        /**Add Stages*/
                        if ($deal_new_stage) {
                            /**Move Deal*/
                            $add_new_deal = true;
                            if ($user_active_campaign->tradie_flow_deal_id) {
                                $get_deal = $active_campaign_helper->getDealDetails($user_active_campaign->tradie_flow_deal_id);
                                if (isset($get_deal->deal->id)) {
                                    /**Update Deal*/
                                    if ($item->action !== 'email_subscriber') {
                                        $data = [
                                            'deal' => [
                                                'contact' => $active_campaign_contact_id,
                                                'description' => $get_deal->deal->description,
                                                'currency' => 'aud',
                                                'group' => $get_deal->deal->group,
                                                'owner' => $get_deal->deal->owner,
                                                'stage' => $deal_new_stage,
                                                'title' => $get_deal->deal->title,
                                                'value' => $get_deal->deal->value
                                            ]
                                        ];

                                        $active_campaign_helper->updateDeal($user_active_campaign->tradie_flow_deal_id, $data);
                                    }

                                    $add_new_deal = false;
                                }
                            }

                            /**Add New Deal*/
                            if ($add_new_deal) {
                                $data = [
                                    'deal' => [
                                        'contact' => $active_campaign_contact_id,
                                        'description' => 'new deal',
                                        'currency' => 'aud',
                                        'group' => $active_campaign_helper->pipeline_id,
                                        'owner' => $active_campaign_helper->owner_id,
                                        'stage' => $deal_new_stage,
                                        'title' => $item->User->name,
                                        'value' => '0'
                                    ]
                                ];

                                $deal_id = $active_campaign_helper->addDeal($data);
                                if ($deal_id) {
                                    $user_active_campaign->tradie_flow_deal_id = $deal_id;
                                    $user_active_campaign->update();
                                }
                            }
                        }
                    }
                    catch (\Exception $e) {

                    }

                    $item->delete();
                }
            });
    }
}
