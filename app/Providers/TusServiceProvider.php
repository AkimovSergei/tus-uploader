<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TusPhp\Tus\Server as TusServer;

class TusServiceProvider extends ServiceProvider
{
     /**
      * Register services.
      *
      * @return void
      */
     public function register()
     {
          $this->app->singleton('tus-server', function ($app) {
               $server = new TusServer(); // 'redis'

               $server
                    ->setApiPath('/tus')// tus server endpoint.
                    ->setUploadDir(storage_path('tus/uploads'))// uploads dir.
                    ->setMaxUploadSize(config('tus-file-uploader.max-upload-size', 100 * 1000 * 1000)); // Default: 100MB

               return $server;
          });
     }
}
