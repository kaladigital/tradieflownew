<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Sitemap;

class SitemapGeneratorEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now_obj = Carbon::now();
        $app_url = env('APP_URL');
        Sitemap::create()
            ->add(Url::create($app_url.'/')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1))
            ->add(Url::create($app_url.'/#about')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/#features')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/#pricing')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/#industries')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/#contact')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/free-trial')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->add(Url::create($app_url.'/free-demo')
                ->setLastModificationDate($now_obj)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))
            ->writeToFile(public_path('sitemap.xml'));
    }
}
