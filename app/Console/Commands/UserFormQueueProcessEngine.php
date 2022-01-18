<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Models\UserDeveloperInvite;
use App\Models\UserForm;
use App\Models\UserFormPage;
use App\Models\UserFormPageForm;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Psr7\str;

class UserFormQueueProcessEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_form_queue_process_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queue form items';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        UserForm::with('User')
            ->where('status','=','pending')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    $item->status = 'processing';
                    $item->update();

                    /**Get main website reference*/
                    if ($item->is_manual_tracking) {
                        $user_page = new UserFormPage();
                        $user_page->user_form_id = $item->user_form_id;
                        $user_page->url = $item->website;
                        $user_page->has_crawl_scanned = '1';
                        $user_page->save();
                    }
                    else{
                        $origin_url_host = parse_url($item->website);
                        $origin_domain = str_replace('www.','',$origin_url_host['host']);
                        $main_links = $this->crawl_website_links($item->user_form_id, $item->website, $origin_domain);

                        /**Track one level*/
                        foreach ($main_links as $key => $site_item) {
                            $this->crawl_website_links($item->user_form_id, $site_item, $item->website);
                        }
                    }

                    /**Fix issue with example.com and example.com/ issue*/
                    $sites = UserFormPage::where('user_form_id','=',$item->user_form_id)
                        ->whereIn('url',[$item->website,$item->website.'/'])
                        ->get();

                    if (!$item->is_manual_tracking && $sites->count() > 1) {
                        foreach ($sites as $duplicate_item) {
                            if ($duplicate_item->url == $item->website.'/') {
                                $duplicate_item->delete();
                            }
                        }
                    }

                    $this->processFormLookup($item);
                    $user_form_pages = UserFormPage::where('user_form_id','=',$item->user_form_id)
                        ->get();

                    if ($user_form_pages->count()) {
                        $item->tracking_key = md5($item->user_id.'_form_'.$item->website.uniqid());
                        Helper::generateFormPageTrackingCode($item, $user_form_pages);

                        UserFormPageForm::select('user_form_page_form.*')
                            ->leftJoin('user_form_page','user_form_page.user_form_page_id','=','user_form_page_form.user_form_page_id')
                            ->leftJoin('user_form','user_form.user_form_id','=','user_form_page.user_form_id')
                            ->where('user_form.user_form_id','=',$item->user_form_id)
                            ->update([
                                'allow_track' => '1'
                            ]);

                        /**Cleanup*/
                        UserFormPage::where('user_form_id','=',$item->user_form_id)
                            ->where('has_crawl_scanned','=','0')
                            ->delete();

                        /**Create Notification*/
                        $title = 'Successful Form Tracking';
                        Helper::addUserNotification($item->user_id, $title, $description = '', 'settings/forms', 'form', 'success');

                        /**Email Developer*/
                        $developers = UserDeveloperInvite::where('user_id','=',$item->user_id)->take(10)->get();
                        foreach ($developers as $item) {
                            $item->email_sent = '1';
                            $item->update();
                            NotificationHelper::siteFormCompleted($item->code,$item->User->name,$item->email);
                        }
                    }
                    else{
                        UserFormPage::where('user_form_id','=',$item->user_form_id)->delete();
                        $item->delete();

                        /**Create Notification*/
                        $title = 'Ooops. Form Tracking Failed';
                        $description = 'We could not find any forms on your website. '.$item->url;
                        Helper::addUserNotification($item->user_id, $title, $description, 'settings/forms', 'form', 'fail');
                    }
                }
            });
    }

    protected function processFormLookup($user_form)
    {
        UserFormPage::where('user_form_id','=',$user_form->user_form_id)
            ->chunk(1000,function($items) {
                foreach ($items as $item) {
                    $dom = new \DOMDocument();
                    try{
                        libxml_use_internal_errors(true);
                        $dom->loadHTML(file_get_contents($item->url));
                        $forms = $dom->getElementsByTagName('form');
                        if ($forms->length) {
                            foreach ($forms as $key => $form_item) {
                                $form_id = $form_item->getAttribute('id');
                                $form_name = $form_item->getAttribute('name');
                                $form_classes = $form_item->getAttribute('class');
                                if ($form_id || $form_name || $form_classes) {
                                    $model = new UserFormPageForm();
                                    $model->user_form_page_id = $item->user_form_page_id;

                                    if ($form_id) {
                                        $model->form_name = $form_id;
                                        $model->form_type = 'id';
                                    }
                                    elseif($form_name) {
                                        $model->form_name = $form_name;
                                        $model->form_type = 'name';
                                    }
                                    elseif($form_classes){
                                        $model->form_name = $form_classes;
                                        $model->form_type = 'class';
                                    }
                                    else{
                                        $model->form_name = $key;
                                        $model->form_type = 'index';
                                    }

                                    $title = null;

                                    /**Search in upper levels*/
                                    if ($form_item->parentNode) {
                                        $title = $this->findTitleFromNode($form_item->parentNode);

                                        /**2nd level*/
                                        if (!$title && $form_item->parentNode->parentNode) {
                                            $title = $this->findTitleFromNode($form_item->parentNode->parentNode);

                                            /**3rd level*/
                                            if (!$title && $form_item->parentNode->parentNode->parentNode) {
                                                $title = $this->findTitleFromNode($form_item->parentNode->parentNode->parentNode);

                                                /**4th level*/
                                                if (!$title && $form_item->parentNode->parentNode->parentNode) {
                                                    $title = $this->findTitleFromNode($form_item->parentNode->parentNode->parentNode);

                                                    /**5th level*/
                                                    if (!$title && $form_item->parentNode->parentNode->parentNode) {
                                                        $title = $this->findTitleFromNode($form_item->parentNode->parentNode->parentNode);

                                                        /**6th level*/
                                                        if (!$title && $form_item->parentNode->parentNode->parentNode->parentNode) {
                                                            $title = $this->findTitleFromNode($form_item->parentNode->parentNode->parentNode->parentNode);
                                                        }

                                                        /**7th level*/
                                                        if (!$title && $form_item->parentNode->parentNode->parentNode->parentNode->parentNode) {
                                                            $title = $this->findTitleFromNode($form_item->parentNode->parentNode->parentNode->parentNode->parentNode);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        $title = $this->findTitleFromNode($form_item);
                                    }

                                    $model->display_name = $title ? $title : $model->form_name;
                                    $model->save();
                                }
                                else{
                                    $item->delete();
                                }
                            }
                        }
                        else{
                            $item->delete();
                        }
                    }
                    catch (\Exception $e) {
                        $item->delete();
                    }
                }
            });
    }

    protected function crawl_website_links($user_form_id, $url, $origin_domain)
    {
        $crawled_sites = [];
        $clean_url = str_replace('https://','',$url);
        $clean_url = str_replace('http://','',$clean_url);
        $clean_url = str_replace('www.','',$clean_url);
        $check_link = $has_page = UserFormPage::where('user_form_id','=',$user_form_id)
            ->whereIn('url',[
                $url,
                'http://'.$clean_url,
                'http://www.'.$clean_url,
                'https://'.$clean_url,
                'https://www.'.$clean_url
            ])
            ->first();

        if ($check_link) {
            if ($check_link->has_crawl_scanned) {
                return [];
            }
            else{
                $check_link->has_crawl_scanned = '1';
                $check_link->update();
            }
        }
        else{
            $model = new UserFormPage();
            $model->user_form_id = $user_form_id;
            $model->url = $url;
            $model->has_crawl_scanned = '1';
            $model->save();
        }

        $get_website_links = shell_exec('lynx -useragent "L_y_n_x/2.8.7dev9.1" -dump -listonly '.$url);

        if ($get_website_links) {
            $website_links = explode("\n",$get_website_links);
            foreach ($website_links as $link_item) {
                $link_item_obj = explode('. ',trim($link_item));
                // && !in_array($link_item_obj['1'],$all_links)

                if (isset($link_item_obj['1'])) {
                    $url_details = parse_url($link_item_obj['1']);
                    if (isset($url_details['host'])) {
                        $domain_name = str_replace('www.','',$url_details['host']);

                        if ($domain_name == $origin_domain) {
                            $crawled_sites[] = $link_item_obj['1'];
                            $all_links[] = $link_item_obj['1'];
                            $has_page = UserFormPage::where('user_form_id','=',$user_form_id)
                                ->where('url','=',$link_item_obj['1'])
                                ->count();

                            if (!$has_page) {
                                $model = new UserFormPage();
                                $model->user_form_id = $user_form_id;
                                $model->url = $link_item_obj['1'];
                                $model->save();
                            }
                        }
                    }
                }
            }
        }

        return $crawled_sites;
    }

    private function findTitleFromNode($node)
    {
        $get_h1 = $node->getElementsByTagName('h1');
        if ($get_h1->length && $get_h1['0']->textContent) {
            return trim($get_h1['0']->textContent);
        }

        $get_h2 = $node->getElementsByTagName('h2');
        if ($get_h2->length && $get_h2['0']->textContent) {
            return trim($get_h2['0']->textContent);
        }

        $get_h3 = $node->getElementsByTagName('h3');
        if ($get_h3->length && $get_h3['0']->textContent) {
            return trim($get_h3['0']->textContent);
        }

        $get_h4 = $node->getElementsByTagName('h4');
        if ($get_h4->length && $get_h4['0']->textContent) {
            return trim($get_h4['0']->textContent);
        }

        $get_h5 = $node->getElementsByTagName('h5');
        if ($get_h5->length && $get_h5['0']->textContent) {
            return trim($get_h5['0']->textContent);
        }

        $get_p = $node->getElementsByTagName('p');
        if ($get_p->length && $get_p->textContent) {
            return trim($get_p['0']->textContent);
        }

        return null;
    }
}
