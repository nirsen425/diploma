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
        /**
         * Проверка на то, что обрезанное изображение минимум 200x200
         */
        Validator::extend('min_resolve', function ($attribute, $value, $parameters, $validator) {
            $imageSize = getimagesize($value);
            $imageWidth = $imageSize[0];
            $imageHeight = $imageSize[1];
            $x = (integer)$_POST['photo_x'];
            $y = (integer)$_POST['photo_y'];
            $cropWidth = (integer)$_POST['photo_width'];
            $cropHeight = (integer)$_POST['photo_height'];
            if ($imageWidth - $x >= 200 and $imageHeight - $y >= 200 and $cropWidth >= 200 and $cropHeight >= 200) {
                return true;
            }
            return false;
        });

        /**
         * Проверка на равенство сторон обрезанного изображения
         */
        Validator::extend('crop_image_square', function ($attribute, $value, $parameters, $validator) {
            $cropWidth = (integer)$_POST['photo_width'];
            $cropHeight = (integer)$_POST['photo_height'];

            return abs($cropWidth === $cropHeight) <= 1;
        });
    }
}
