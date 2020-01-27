<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('min_resolve', function ($attribute, $value, $parameters, $validator) {
            $imageSize = getimagesize($value);
            $imageWidth = $imageSize[0];
            $imageHeight = $imageSize[1];
            $x = $_POST['photo_x'];
            $y = $_POST['photo_y'];
            $cropWidth = $_POST['photo_width'];
            $cropHeight = $_POST['photo_height'];
            if ($imageWidth - $x >= 200 and $imageHeight - $y >= 200 and $cropWidth >= 200 and $cropHeight >= 200) {
                return true;
            }
            return false;
        });
    }
}
