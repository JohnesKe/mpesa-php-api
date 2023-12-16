<?php

namespace JohnesKe\MpesaPhpApi\Commands;

use Illuminate\Console\Command;

class RegisterC2BUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpesa-php-api:register-c2b-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers C2B URLs to the Mpesa C2B API';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$confirmation = config('mpesa-php-api.c2b_url.confirmation');
        //$validation = config('mpesa-php-api.c2b_url.validation');

        //MpesaPhpApi::register_c2b_urls($confirmation, $validation);
        //$this->info('URLs registered successfully');
    }
}