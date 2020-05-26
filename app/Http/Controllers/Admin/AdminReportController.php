<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Application;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

class AdminReportController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
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
     */
    public function getReportPracticeGroup()
    {
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
        $headerDoc = "Список студентов и руководителей на практику 2020";
        $headerGroup = "группы НМТ - 463929";

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
            'ФИО руководителя', $headerTableTextStyle, $tableParagraphStyle
        );


        /*
        $counter = 0;
        foreach($teachers as $teacher) {
            $counter++;
            $table->addCell()->addText($teacher->getFullName());
        }
        $teacherCount = $teachers->count();
        for($i = 0; $i < $teacherCount; $i++) {
            $table->addRow();
        }
        */


        //  Контент таблицы
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Конюхов Алексей Сергеевич', $tableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Шипачева Екатерина Николаевна', $tableTextStyle, $tableParagraphStyle
        );

        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Федотовских Юрий Алексеевич', $tableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Маянц Майя Львовна', $tableTextStyle, $tableParagraphStyle
        );

        /**
         * Сохранение и передача файла пользователю на скачивание
         */

        //  Имя файла
        $file = 'Список студентов и руководителей на практику (НМТ-463929).docx';

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
    public function getReportPracticeTeacher()
    {
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
        $headerDoc = "Список студентов на практику 2020";
        $headerGroup = "руководителя Маянц Майи Львовны";

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
        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Конюхов Алексей Сергеевич', $tableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            'НМТ - 463931', $tableTextStyle, $tableParagraphStyle
        );

        $table->addRow();
        $table->addCell($cellWidth, $cellStyle)->addText(
            'Федотовских Юрий Алексеевич', $tableTextStyle, $tableParagraphStyle
        );
        $table->addCell($cellWidth, $cellStyle)->addText(
            'НМТ - 463929', $tableTextStyle, $tableParagraphStyle
        );

//        $counter = 0;
//        foreach($teachers as $teacher) {
//            $counter++;
//            $table->addCell()->addText($teacher->getFullName());
//        }
//        $teacherCount = $teachers->count();
//        for($i = 0; $i < $teacherCount; $i++) {
//            $table->addRow();
//        }

        /**
         * Сохранение и передача файла пользователю на скачивание
         */

        //  Имя файла
        $file = 'Список студентов на практику 2020 (Маянц Майя Львовна).docx';

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

    public function getReportDiploma()
    {

    }
}
