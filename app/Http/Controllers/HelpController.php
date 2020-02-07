<?php

namespace App\Http\Controllers;
use App\ImageService;
use App\User;
use App\Page;
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
     * Проверка существования логина учителя
     *
     * @param Request $request
     * @return string Статус проверки
     */
    public function loginVerification(Request $request, $id = null)
    {
        /*
         * $id необязательный параметр,
         * он не будет null при проверка логина при редактировании
         */
        $login = $request->login;

        /*
           Эта проверка нужна, если мы редактируем логин пользователя.
           Если логин текущего редактируемого пользователя совпадет с логином
           в поле ввода, значит логин не хотят изменить. Проверка пройдена успешно
        */
        if ($id and $this->LoginMatch(User::where('id', '=', $id), $login)) {
            return "true";
        }

        $user = User::where("login", "=", $login)->first();
        if (empty($user)) {
            return "true";
        }

        return "false";
    }

    /**
     * Проверка совпадения логина пользователя с переданным логином
     *
     * @param $user
     * @param $login
     * @return bool
     */
    public function LoginMatch($user, $login) {
        return $user->value('login') === $login;
    }

    /**
     * Проверка существования заголовка страницы
     *
     * @param Request $request
     * @return string Статус проверки
     */
    public function titleVerification(Request $request, $id = null)
    {

        /*
         * $id необязательный параметр,
         * он не будет null при проверка заголовка при редактировании
         */
        $title = $request->title;

        /*
           Эта проверка нужна, если мы редактируем заголовок страницы.
           Если заголовок текущей редактируемой страницы совпадет с заголовком
           в поле ввода, значит заголовок не хотят изменить. Проверка пройдена успешно
        */
        if ($id and $this->titleMatch(Page::where('id', '=', $id), $title)) {
            return "true";
        }

        $page = Page::where("title", "=", $title)->first();
        if (empty($page)) {
            return "true";
        }

        return "false";
    }

    /**
     * Проверка совпадения заголовка страницы с переданным заголовком
     *
     * @param $user
     * @param $page
     * @return bool
     */
    public function titleMatch($page, $title) {
        return $page->value('title') === $title;
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
