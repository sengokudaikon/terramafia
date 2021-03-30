<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        $this->clearCache();

        return $app;
    }

    /**
     * Clears Laravel Cache.
     */
    protected function clearCache()
    {
        $commands = ['clear-compiled', 'cache:clear', 'view:clear', 'config:clear', 'route:clear'];

        foreach ($commands as $command) {
            Artisan::call($command);
        }
    }

    /**
     * {@inheritdoc}
     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        Mail::fake();
//        Storage::persistentFake(FileService::CLOUD_DISK_NAME, ['url' => '/']);
//        Storage::persistentFake(FileService::LOCAL_DISK_NAME, ['url' => '/']);
//        $this->withHeaders([
//            'Access-Control-Allow-Origin' => '*',
//            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE'
//        ]);
//    }
//
//    public function tearDown()
//    {
//        parent::tearDown();
//    }

}
