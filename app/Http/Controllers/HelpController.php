<?php

namespace App\Http\Controllers;
use App\ImageService;
use App\User;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * HelpController constructor.
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
//        $this->middleware('admin');

        $this->imageService = $imageService;
    }

    /**
     * Проверка существования логина
     *
     * @param Request $request
     * @return string Статус проверки
     */
    public function loginVerification(Request $request)
    {
        $login = $request->login;
        $user = User::where("login", "=", $login)->first();
        if (empty($user)) {
            return "true";
        }

        return "false";
    }

    /**
     * Загрузка изображения
     *
     * @param Request $request
     * @return string Ответ для ajax запроса ckeditor
     */
    public function uploadImage(Request $request)
    {
        $path = $request->file("upload")->store("public/images");
        $imageName = $this->imageService->getNameUploadedImage($path);
        $imagePath = asset("storage/images/" . $imageName);

        return "{
            \"uploaded\": 1,
            \"fileName\": \"$imageName\",
            \"url\": \"$imagePath\"
        }";
    }
}
