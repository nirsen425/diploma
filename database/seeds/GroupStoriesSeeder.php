<?php
use App\Group;
use Illuminate\Database\Seeder;

class GroupStoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $groups = Group::all();

        // аналог substr_replace с возможностью выбора кодировки(замены русских символов)
        function mb_substr_replace($string, $replace, $start, $limit, $encoding){
            # Ищем символ, который надо заменить
            $symbol = mb_substr($string, $start, $limit, $encoding);

            # Заменяем и возвращаем
            return
                str_replace($symbol, $replace, $string);
        }

        // Формируем историю для каждой группы на всех курсах
        foreach($groups as $group) {
            $groupYear = $group->year;
            $groupCourse  = $group->course_id;
            $groupName = $group->name;
            for($groupHistoryCourse = 1; $groupHistoryCourse <= $groupCourse; $groupHistoryCourse++) {
                $groupHistoryNameByCourse = mb_substr_replace($groupName, $groupHistoryCourse, 4, 1, 'utf-8');

                DB::table('group_stories')->insert([
                    'group_id' => $group->id,
                    'name' => $groupHistoryNameByCourse,
                    'year_history' => $groupYear - ($groupCourse - $groupHistoryCourse),
                    'course_id' => $groupHistoryCourse
                ]);
            }
        }
    }
}
