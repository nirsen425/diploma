<?php

namespace App\Http\Controllers;
use App\ImageService;
use App\Teacher;
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
    public function loginVerification(Request $request, Teacher $teacher)
    {
        $login = $request->login;

        /*
           Эта проверка нужна, если мы редактируем логин учителя, то есть если $teacher не null.
           Если логин текущего редактируемого учителя совпадет с логином
           в поле ввода, значит логин не хотят изменить. Проверка пройдена успешно
        */
        if ($teacher and $this->teacherLoginMatch($teacher, $login)) {
            return "true";
        }

        $user = User::where("login", "=", $login)->first();
        if (empty($user)) {
            return "true";
        }

        return "false";
    }

    /**
     * Проверка совпадения логина учителя с переданным логином
     *
     * @param $teacher
     * @param $login
     * @return bool
     */
    public function teacherLoginMatch($teacher, $login) {
        return $teacher->user()->value('login') === $login;
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
