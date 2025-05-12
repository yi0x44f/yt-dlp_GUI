<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call( function(){
    $files = Storage::disk('public')->files('video');
    foreach( $files as $file ){
        if ( str_ends_with($file, '.txt') ) continue;
        else{
            Storage::disk('public')->delete($file);
        }
    }
} )->everySixHours();