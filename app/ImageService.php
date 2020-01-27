<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class ImageService extends Model
{
    /**
     * Сохранение обрезанного изображения
     *
     * @param $image Изображение
     * @param $x Координата точки x на изображении с которой начнется обрезка
     * @param $y Координата точки y на изображении с которой начнется обрезка
     * @param $width Ширина обрезанного изображения
     * @param $height Высота обрезанного изображения
     * @return string Имя обрезанного изображения
     */
    public function handleUploadedImage($image, $x, $y, $width, $height)
    {
        $path = $image->store("public/images");

        $fullPath = storage_path('app') . '/' . $path;

        $this->cropUploadedImage($fullPath, $x, $y, $width, $height);

        return $this->getNameUploadedImage($path);
    }

    /**
     * Обрезание изображения
     *
     * @param $fullPath Полный путь к изображению
     * @param $x Координата точки x на изображении с которой начнется обрезка
     * @param $y Координата точки y на изображении с которой начнется обрезка
     * @param $width Ширина обрезанного изображения
     * @param $height Высота обрезанного изображения
     */
    public function cropUploadedImage($fullPath, $x, $y, $width, $height)
    {
        $image = Image::make($fullPath);
        $image->crop($width, $height, $x, $y);
        $image->save($fullPath);
    }

    /**
     * Получает имя изображения
     *
     * @param $path Короткий путь к изображению
     * @return string Имя изображения
     */
    public function getNameUploadedImage($path)
    {
        $partsPath = preg_split("#[//\\\]#", $path);
        $imageName = array_pop($partsPath);

        return $imageName;
    }
}
