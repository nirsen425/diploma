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

    public function getReportPractice()
    {
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(10);

        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Name');
        $properties->setCompany('Company Name');
        $properties->setTitle('My title');
        $properties->setDescription('My description');
        $properties->setCategory('My category');
        $properties->setLastModifiedBy('My name');
        $properties->setCreated(mktime(0, 0, 0, 3, 12, 2014));
        $properties->setModified(mktime(0, 0, 0, 3, 14, 2014));
        $properties->setSubject('My subject');
        $properties->setKeywords('my, key, word');

        // Массив стилей для раздела
        $sectionStyle = array(
            'marginLeft' => 600,
            'marginRight' => 600,
            'colsNum' => 1,
            'pageNumberingStart' => 1,
        );
        // Создание секции
        $section = $phpWord->addSection($sectionStyle);

        $text = "Студенты проходящие практику";
        // 1 параметр - текст, 2 параметр - массив стилей для текста, 3 параметр - массив настроек для абзаца
        $section->addText(htmlspecialchars($text), array('size' => 14, 'bold' => true));

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 50
        );

        $teachers = $this->teacher->all();


        $table = $section->addTable($tableStyle);

        $table->addRow();
        $table->addCell()->addText('Маянц Майа Львовна');
        $table->addCell()->addText('Маянц Майа Львовна');
        $table->addCell()->addText('Маянц Майа Львовна');
        $table->addCell()->addText('Маянц Майа Львовна');
        $table->addCell()->addText('Маянц Майа Львовна');
        $table->addCell()->addText('Маянц Майа Львовна');

//        $counter = 0;
//        foreach($teachers as $teacher) {
//            $counter++;
//            $table->addCell()->addText($teacher->getFullName());
//        }
//        $teacherCount = $teachers->count();
//        for($i = 0; $i < $teacherCount; $i++) {
//            $table->addRow();
//        }

        $table->addRow();
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');

        $table->addRow();
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');
        $table->addCell()->addText('Конюхов Алексей Сергеевич');

        $file = 'practice.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        // 2 параметр, типо writer'а
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    public function getReportDiploma()
    {

    }
}
