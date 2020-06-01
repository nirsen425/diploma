<?php

namespace App\Helpers;
use App\User;

class Helper
{
    /**
     * Вовзращает учебный год
     *
     * @return false|int|string
     */
    public static function getSchoolYear()
    {
        return date('n') < 7 ? date('Y') - 1 : date('Y');
    }

    /**
     * Проверка пользователя на студента
     *
     * @param User $user
     * @return bool
     */
    public static function isStudent(User $user)
    {
        return $user->user_type_id == 1;
    }

    /**
     * Проверка пользователя на преподавателя
     *
     * @param User $user
     * @return bool
     */
    public static function isTeacher(User $user)
    {
        return $user->user_type_id == 2;
    }
}
