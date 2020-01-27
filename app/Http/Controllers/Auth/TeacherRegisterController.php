<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\ImageService;
use App\Providers\RouteServiceProvider;
use App\Teacher;
use App\User;
use DB;
use Dotenv\Exception\ValidationException;
use http\Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Teacher Register Controller
    |--------------------------------------------------------------------------
    |
    | Контроллер для регистрации преподавателя.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $imageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageService $imageService)
    {
        $this->middleware('guest');

        $this->imageService = $imageService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'patronymic' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'min:10', 'max:100'],
            'full_description' => ['required', 'string', 'min:50', 'max:65530'],
            'photo' => ['required', 'image', 'dimensions:min_width=200,min_height=200,ratio=1/1', 'min_resolve'],
            'login' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]+$/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data массив формируемый из POST запроса
     * @return \App\User вновь созданный пользователь
     */
    protected function create(array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'user_type_id' => 2,
                'rights_id' => 1,
                'login' => $data['login'],
                'password' => Hash::make($data['password']),
            ]);

            $image = $data['photo'];
            // Получаем координаты точки и размеры для обрезки изображения
            $cropCoordX = (integer)$data['photo_x'];
            $cropCoordY = (integer)$data['photo_y'];
            $cropWidth = (integer)$data['photo_width'];
            $cropHeight = (integer)$data['photo_height'];
            // Обрезаем изображение и получаем его имя
            $cropPhotoName = $this->imageService
                ->handleUploadedImage($image, $cropCoordX, $cropCoordY, $cropWidth, $cropHeight);

            $user->teacher()->create([
                'name' => $data['name'],
                'patronymic' => $data['patronymic'],
                'surname' => $data['surname'],
                'short_description' => $data['short_description'],
                'full_description' => $data['full_description'],
                'photo' => $cropPhotoName
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $user;
    }

    /**
     * Показ формы регистрации.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('admin.teachers.create');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('status', 'Пользователь успешно зарегистрирован');
    }

    /**
     * Перенаправление при успешной регистрации
     *
     * @return string
     */
    protected function redirectTo()
    {
        return route('teacher-register');
    }
}
