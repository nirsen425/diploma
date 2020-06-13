<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Application;
use App\Student;
use App\Teacher;
use App\Group;
use App\GroupStory;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

class AdminReportController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;
    protected $group;
    protected $groupStory;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     * @param Group $group
     * @param GroupStory $groupStory
     */
    public function __construct(Application $application, Teacher $teacher, Student $student, Group $group, GroupStory $groupStory)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
        $this->group = $group;
        $this->groupStory = $groupStory;
    }

    //  Конвертация миллиметров в твипы
    function m2t($millimeters)
    {
        return floor($millimeters*56.7);
    }

    //  Конвертация ПТ в твипы
    function pt2t($pt)
    {
        return floor($pt*20);
    }

    /**
     * Создание отчета по практике (по группе)
     * @param $year
     * @param $groupStoryId
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getReportPracticeGroup($year, $groupStoryId)
    {
        /**
         * Связь и получение списка студентов и преподавателей для списка отчета
         */
        $groupStory = $this->groupStory->where('id', '=', $groupStoryId)->first();
        $groupName = $groupStory->name;
        $students = $groupStory->where('year_history', '=', $year)->first()->students()->orderBy('surname')->get();
        foreach ($students as $student) {
            $applications[] = $student->applications()->where('year', '=', $year)->first();
        }
        foreach ($applications as $application) {
            $teachers[] = $application->teacher()->first();
        }

        /**
         * Объект и его параметры
         */
        //  Создание объекта
        $phpWord = new PhpWord();

        //  Шрифт и его размер по умолчанию
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        //  Параметры документа
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Маянц Майя Львовна');
        $properties->setCompany('Кафедра ИТиАП');
        $properties->setSubject('Практика');

        /**
         * Создание секции
         */
        //  Массив стилей
        $sectionStyle = array(
            'marginLeft' => $this->m2t(25),
            'marginRight' => $this->m2t(15),
            'marginTop' => $this->m2t(20),
            'marginBottom' => $this->m2t(20),
            'colsNum' => 1,
            'pageNumberingStart' => 1,
        );

        // Создание
        $section = $phpWord->addSection($sectionStyle);

        /**
         * Создание заголовка
         */
        //  Массив стилей текста
        $headerTextStyle = array(
            'size' => 16,
            'bold' => true
        );

        //  Массив стилей абзаца
        #   spacing - межстрочный интервал
        #   При одинарном -> spacing = 0 твип
        #   За каждый 0,1 интервал -> spacing += 24 твип
        #   Итого при 1,5 интервале -> spacing = 1 + 0,5 = (0 + 0.1*24) = 120 твип
        $headerDocParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(0)
        );
        $headerGroupParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(8)
        );

        //  Текст заголовка
        $headerDoc = "Список студентов и руководителей на практику $year";
        $headerGroup = "группа $groupName";

        //  Добавление текста заголовка
        #   1 параметр - текст
        #   2 параметр - массив стилей текста
        #   3 параметр - массив настроек абзаца
        $section->addText(
            $headerDoc, $headerTextStyle, $headerDocParagraphStyle
        );
        $section->addText(
            $headerGroup, $headerTextStyle, $headerGroupParagraphStyle
        );

        /**
         * Создание таблицы
         */
        //  Массив стилей таблицы
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize' => $this->pt2t(0.5),
            'align' => 'center',
            'cellMarginRight' => $this->m2t(1.9),
            'cellMarginLeft' => $this->m2t(1.9)
        );

        //  Создание таблицы
        $table = $section->addTable($tableStyle);

        // Массив стилей ячейки таблицы
        $cellWidth = $this->m2t(90);
        $cellStyle = array(
            'valign' => 'center'
        );

        //  Массив стилей текста заголовка таблицы
        $headerTableTextStyle = array(
            'size' => 14,
            'bold' => true
        );

        //  Массив стилей текста таблицы
        $tableTextStyle = array(
            'size' => 14
        );

        //  Массив стилей абзаца таблицы
        $tableParagraphStyle = array(
            'align' => 'center',
            'spacing' => 36,
            'spaceAfter' => $this->pt2t(0)
        );

        // Заголовок таблицы
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            "ФИО студента", $headerTableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            "ФИО руководителя", $headerTableTextStyle, $tableParagraphStyle
        );

        //  Контент таблицы
        $studentsCount = $students->count();
        for($i = 0; $i < $studentsCount; $i++)
        {
            $table->addRow();
            $table->addCell($cellWidth, $cellStyle)->addText(
                $students[$i]->getFullName(), $tableTextStyle, $tableParagraphStyle
            );
            $table->addCell($cellWidth, $cellStyle)->addText(
                $teachers[$i]->getFullName(), $tableTextStyle, $tableParagraphStyle
            );
        }

        /**
         * Сохранение и передача файла пользователю на скачивание
         */
        //  Имя файла
        $file = "Список студентов и руководителей на практику ($groupName).docx";

        //  Набор заголовков
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        //  Формирование документа
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    /**
     * Создание отчета по практике (по руководителю)
     */
    public function getReportPracticeTeacher($year, $teacherId)
    {
        /**
         * Связь и получение списка студентов и их групп для списка отчета
         */
        $teacher = $this->teacher->where('id', '=', $teacherId)->first();
        $teacherFullName = $teacher->getFullName();
        $practiceApplications = $teacher->applications()->where([['year', '=', $year], ['status_id', '=', 2], ['type_id', '=', 1]])->get();
        foreach ($practiceApplications as $practiceApplication)
        {
            $students[] = $practiceApplication->student()->first();
        }
        // Сортировка по фамилии
        $students = collect($students);
        $students = $students->sortBy('surname');
        $students = $students->values()->all();

        /**
         * Объект и его параметры
         */
        //  Создание объекта
        $phpWord = new PhpWord();

        //  Шрифт и его размер по умолчанию
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        //  Параметры документа
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Маянц Майя Львовна');
        $properties->setCompany('Кафедра ИТиАП');
        $properties->setSubject('Практика');

        /**
         * Создание секции
         */
        //  Массив стилей
        $sectionStyle = array(
            'marginLeft' => $this->m2t(25),
            'marginRight' => $this->m2t(15),
            'marginTop' => $this->m2t(20),
            'marginBottom' => $this->m2t(20),
            'colsNum' => 1,
            'pageNumberingStart' => 1,
        );

        // Создание
        $section = $phpWord->addSection($sectionStyle);

        /**
         * Создание заголовка
         */
        //  Массив стилей текста
        $headerTextStyle = array(
            'size' => 16,
            'bold' => true
        );

        //  Массив стилей абзаца
        #   spacing - межстрочный интервал
        #   При одинарном -> spacing = 0 твип
        #   За каждый 0,1 интервал -> spacing += 24 твип
        #   Итого при 1,5 интервале -> spacing = 1 + 0,5 = (0 + 0.1*24) = 120 твип
        $headerDocParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(0)
        );
        $headerGroupParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(8)
        );

        //  Текст заголовка
        $headerDoc = "Список студентов на практику $year";
        $headerGroup = "руководитель $teacherFullName";

        //  Добавление текста заголовка
        #   1 параметр - текст
        #   2 параметр - массив стилей текста
        #   3 параметр - массив настроек абзаца
        $section->addText(
            $headerDoc, $headerTextStyle, $headerDocParagraphStyle
        );
        $section->addText(
            $headerGroup, $headerTextStyle, $headerGroupParagraphStyle
        );

        /**
         * Создание таблицы
         */
        //  Массив стилей таблицы
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize' => $this->pt2t(0.5),
            'align' => 'center',
            'cellMarginRight' => $this->m2t(1.9),
            'cellMarginLeft' => $this->m2t(1.9)
        );

        //  Создание таблицы
        $table = $section->addTable($tableStyle);

        // Массив стилей ячейки таблицы
        $cellWidth = $this->m2t(90);
        $cellStyle = array(
            'valign' => 'center'
        );

        //  Массив стилей текста заголовка таблицы
        $headerTableTextStyle = array(
            'size' => 14,
            'bold' => true
        );

        //  Массив стилей текста таблицы
        $tableTextStyle = array(
            'size' => 14
        );

        //  Массив стилей абзаца таблицы
        $tableParagraphStyle = array(
            'align' => 'center',
            'spacing' => 36,
            'spaceAfter' => $this->pt2t(0)
        );

        // Заголовок таблицы
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            'ФИО студента', $headerTableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Группа', $headerTableTextStyle, $tableParagraphStyle
        );

        //  Контент таблицы
        $studentsCount = count($students);
        for($i = 0; $i < $studentsCount; $i++)
        {
            $table->addRow();
            $table->addCell($cellWidth, $cellStyle)->addText(
                $students[$i]->getFullName(), $tableTextStyle, $tableParagraphStyle
            );
            $table->addCell($cellWidth, $cellStyle)->addText(
                $students[$i]->groupStories()->where('year_history', '=', $year)->first()->name, $tableTextStyle, $tableParagraphStyle
            );
        }

        /**
         * Сохранение и передача файла пользователю на скачивание
         */
        //  Имя файла
        $file = "Список студентов на практику $year ($teacherFullName).docx";

        //  Набор заголовков
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        //  Формирование документа
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    public function getReportLoginStudent(Group $group)
    {
        $students = $group->students()->get();
        $groupStoryName = $group->name;

        /**
         * Объект и его параметры
         */
        //  Создание объекта
        $phpWord = new PhpWord();

        //  Шрифт и его размер по умолчанию
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        //  Параметры документа
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Маянц Майя Львовна');
        $properties->setCompany('Кафедра ИТиАП');
        $properties->setSubject('Практика');

        /**
         * Создание секции
         */
        //  Массив стилей
        $sectionStyle = array(
            'marginLeft' => $this->m2t(25),
            'marginRight' => $this->m2t(15),
            'marginTop' => $this->m2t(20),
            'marginBottom' => $this->m2t(20),
            'colsNum' => 1,
            'pageNumberingStart' => 1,
        );

        // Создание
        $section = $phpWord->addSection($sectionStyle);

        /**
         * Создание заголовка
         */
        //  Массив стилей текста
        $headerTextStyle = array(
            'size' => 16,
            'bold' => true
        );
        $headerPasswordStyle = array(
            'size' => 14,
        );

        //  Массив стилей абзаца
        #   spacing - межстрочный интервал
        #   При одинарном -> spacing = 0 твип
        #   За каждый 0,1 интервал -> spacing += 24 твип
        #   Итого при 1,5 интервале -> spacing = 1 + 0,5 = (0 + 0.1*24) = 120 твип
        $headerDocParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(0)
        );
        $headerGroupParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(8)
        );

        //  Текст заголовка
        $headerDoc = "Список логинов студентов";
        $headerGroup = "группа " . $groupStoryName;
        $headerPassword = '(для всех пароль по умолчанию: "password")';

        //  Добавление текста заголовка
        #   1 параметр - текст
        #   2 параметр - массив стилей текста
        #   3 параметр - массив настроек абзаца
        $section->addText(
            $headerDoc, $headerTextStyle, $headerDocParagraphStyle
        );
        $section->addText(
            $headerGroup, $headerTextStyle, $headerGroupParagraphStyle
        );
        $section->addText(
            $headerPassword, $headerPasswordStyle, $headerGroupParagraphStyle
        );

        /**
         * Создание таблицы
         */
        //  Массив стилей таблицы
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize' => $this->pt2t(0.5),
            'align' => 'center',
            'cellMarginRight' => $this->m2t(1.9),
            'cellMarginLeft' => $this->m2t(1.9)
        );

        //  Создание таблицы
        $table = $section->addTable($tableStyle);

        // Массив стилей ячейки таблицы
        $cellWidth = $this->m2t(90);
        $cellStyle = array(
            'valign' => 'center'
        );

        //  Массив стилей текста заголовка таблицы
        $headerTableTextStyle = array(
            'size' => 14,
            'bold' => true
        );

        //  Массив стилей текста таблицы
        $tableTextStyle = array(
            'size' => 14
        );

        //  Массив стилей абзаца таблицы
        $tableParagraphStyle = array(
            'align' => 'center',
            'spacing' => 36,
            'spaceAfter' => $this->pt2t(0)
        );

        // Заголовок таблицы
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            "ФИО студента", $headerTableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            "Логин", $headerTableTextStyle, $tableParagraphStyle
        );

        //  Контент таблицы
        $studentsCount = $students->count();
        for($i = 0; $i < $studentsCount; $i++)
        {
            $table->addRow();
            $table->addCell($cellWidth, $cellStyle)->addText(
                $students[$i]->getFullName(), $tableTextStyle, $tableParagraphStyle
            );
            $table->addCell($cellWidth, $cellStyle)->addText(
                $students[$i]->user()->first()->login, $tableTextStyle, $tableParagraphStyle
            );
        }

        /**
         * Сохранение и передача файла пользователю на скачивание
         */
        //  Имя файла
        $file = "Список логинов студентов ($groupStoryName).docx";

        //  Набор заголовков
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        //  Формирование документа
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    public function getReportLoginTeacher()
    {
        $teachers = $this->teacher->all();

        /**
         * Объект и его параметры
         */
        //  Создание объекта
        $phpWord = new PhpWord();

        //  Шрифт и его размер по умолчанию
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);

        //  Параметры документа
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Маянц Майя Львовна');
        $properties->setCompany('Кафедра ИТиАП');
        $properties->setSubject('Практика');

        /**
         * Создание секции
         */
        //  Массив стилей
        $sectionStyle = array(
            'marginLeft' => $this->m2t(25),
            'marginRight' => $this->m2t(15),
            'marginTop' => $this->m2t(20),
            'marginBottom' => $this->m2t(20),
            'colsNum' => 1,
            'pageNumberingStart' => 1,
        );

        // Создание
        $section = $phpWord->addSection($sectionStyle);

        /**
         * Создание заголовка
         */
        //  Массив стилей текста
        $headerTextStyle = array(
            'size' => 16,
            'bold' => true
        );
        $headerPasswordStyle = array(
            'size' => 14,
        );

        //  Массив стилей абзаца
        #   spacing - межстрочный интервал
        #   При одинарном -> spacing = 0 твип
        #   За каждый 0,1 интервал -> spacing += 24 твип
        #   Итого при 1,5 интервале -> spacing = 1 + 0,5 = (0 + 0.1*24) = 120 твип
        $headerDocParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(0)
        );
        $headerPasswordParagraphStyle = array(
            'align' => 'center',
            'spacing' => 120,
            'spaceAfter' => $this->pt2t(8)
        );

        //  Текст заголовка
        $headerDoc = "Список логинов руководителей";
        $headerPassword = '(для всех пароль по умолчанию: "password")';

        //  Добавление текста заголовка
        #   1 параметр - текст
        #   2 параметр - массив стилей текста
        #   3 параметр - массив настроек абзаца
        $section->addText(
            $headerDoc, $headerTextStyle, $headerDocParagraphStyle
        );
        $section->addText(
            $headerPassword, $headerPasswordStyle, $headerPasswordParagraphStyle
        );

        /**
         * Создание таблицы
         */
        //  Массив стилей таблицы
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize' => $this->pt2t(0.5),
            'align' => 'center',
            'cellMarginRight' => $this->m2t(1.9),
            'cellMarginLeft' => $this->m2t(1.9)
        );

        //  Создание таблицы
        $table = $section->addTable($tableStyle);

        // Массив стилей ячейки таблицы
        $cellWidth = $this->m2t(90);
        $cellStyle = array(
            'valign' => 'center'
        );

        //  Массив стилей текста заголовка таблицы
        $headerTableTextStyle = array(
            'size' => 14,
            'bold' => true
        );

        //  Массив стилей текста таблицы
        $tableTextStyle = array(
            'size' => 14
        );

        //  Массив стилей абзаца таблицы
        $tableParagraphStyle = array(
            'align' => 'center',
            'spacing' => 36,
            'spaceAfter' => $this->pt2t(0)
        );

        // Заголовок таблицы
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            "ФИО руководителя", $headerTableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            "Логин", $headerTableTextStyle, $tableParagraphStyle
        );

        //  Контент таблицы
        $teachersCount = $teachers->count();
        for($i = 0; $i < $teachersCount; $i++)
        {
            $table->addRow();
            $table->addCell($cellWidth, $cellStyle)->addText(
                $teachers[$i]->getFullName(), $tableTextStyle, $tableParagraphStyle
            );
            $table->addCell($cellWidth, $cellStyle)->addText(
                $teachers[$i]->user()->first()->login, $tableTextStyle, $tableParagraphStyle
            );
        }

        /**
         * Сохранение и передача файла пользователю на скачивание
         */
        //  Имя файла
        $file = "Список логинов руководителей.docx";

        //  Набор заголовков
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        //  Формирование документа
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }
}
