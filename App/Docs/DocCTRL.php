<?php

namespace App\Docs;

use Symphograph\Bicycle\Api\Action\ApiAction;
use Symphograph\Bicycle\Files\FileDoc;
use Symphograph\Bicycle\Files\UploadedDoc;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\HTTP\Request;

class DocCTRL
{

    #[NoReturn] public static function add(): void
    {
        User::auth([15]);
        Request::checkEmpty(['title', 'folderId', 'atDate']);

        $tmpFile = UploadedDoc::getFile();
        $folder = DocFolder::byId($_POST['folderId']);

        $FileDoc = FileDoc::byUploaded($tmpFile);
        $doc = Doc::newInstance($folder->id, $_POST['title'], $_POST['atDate']);
        $doc->file = $FileDoc;
        $tmpFile->saveAs($FileDoc->getFullPath());
        $doc->putToDB();
        $doc->makePublic();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function del(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        $doc = Doc::byId($_POST['id'])
            ?: throw new AppErr("{$_POST['id']} does not exists", 'Запись не найдена');
        $doc->del();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function get()
    {
    }

    public static function list(): void
    {
        $folderList = [
            [
                'id'    => 1,
                'title' => 'Учредительные документы',
                'docs'  => [
                    ['fileName' => 'ustav_2018_09_17_with_egrul.pdf', 'title' => 'Устав учреждения от 17.09.2018'],
                    ['fileName' => 'ustav_edit_2019-01-16.pdf', 'title' => 'Изменения в Устав от 16.01.2019'],
                    ['fileName' => 'ustav_edit_2020-04-14.pdf', 'title' => 'Изменения в Устав от 14.04.2020'],
                    ['fileName' => 'ustav_edit_2023-04-10.pdf', 'title' => 'Изменения в Устав от 10.04.2023'],
                    ['fileName' => 'Certificate_of_registration_2023-04-10.pdf', 'title' => 'Свидетельство о постановке на учет от 10.04.2023'],
                    ['fileName' => 'extractFromEGRN.pdf', 'title' => 'Выписка из ЕГРН'],
                    ['fileName' => 'ofdocs/inn.pdf', 'title' => 'ИНН/ОГРН: 6501109377/1026500550086'],
                    ['fileName' => '', 'title' => 'КПП: 650101001'],
                    ['fileName' => '', 'title' => 'ОКПО: 55655001']
                ]
            ],
            [
                'id'    => 2,
                'title' => 'ФХД',
                'docs'  => [
                    ['fileName' => 'Plan_FHD_2018.pdf', 'title' => 'План ФХД 2018'],
                    ['fileName' => 'Plan_FHD_2019.pdf', 'title' => 'План ФХД 2019'],
                    ['fileName' => 'Plan_FHD_2020.pdf', 'title' => 'План ФХД 2020'],
                    ['fileName' => 'Plan_FHD_2021.pdf', 'title' => 'План ФХД 2021'],
                    ['fileName' => 'Plan_FHD_2022.pdf', 'title' => 'План ФХД 2022'],
                    ['fileName' => 'Performance_reportFHD.pdf', 'title' => 'Отчет об исполнении ФХД'],
                    ['fileName' => 'Performance_reportFHD1.pdf', 'title' => 'Отчет об исполнении ФХД1'],
                    ['fileName' => 'Performance_reportFHD2.pdf', 'title' => 'Отчет об исполнении ФХД2'],
                    ['fileName' => 'salary.pdf', 'title' => 'Заработная плата руководителей']
                ]
            ],
            [
                'id'    => 3,
                'title' => 'Муниципальное задание',
                'docs'  => [
                    ['fileName' => 'MUP_Task_2018.pdf', 'title' => 'Муниципальное задание 2018'],
                    ['fileName' => 'MUP_Task_2019.pdf', 'title' => 'Муниципальное задание 2019'],
                    ['fileName' => 'MUP_Task_2020.pdf', 'title' => 'Муниципальное задание 2020'],
                    ['fileName' => 'MUP_Task_2021.pdf', 'title' => 'Муниципальное задание 2021'],
                    ['fileName' => 'MUP_Task_2021edit.pdf', 'title' => 'Муниципальное задание 2021 изменения'],
                    ['fileName' => 'MUP_Task_2022.pdf', 'title' => 'Муниципальное задание 2022'],
                    ['fileName' => 'MUP_Task_2022edit.pdf', 'title' => 'Муниципальное задание 2022 изменения'],
                    ['fileName' => 'MUP_Task_2023.pdf', 'title' => 'Муниципальное задание 2023'],
                    ['fileName' => 'MUP_Task_2023edit.pdf', 'title' => 'Муниципальное задание 2023 изменения'],
                    ['fileName' => 'report_MTask_2018.doc', 'title' => 'Отчеты по выполнению Муниципального задания 2018'],
                    ['fileName' => 'report_MTask_2019.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 2019'],
                    ['fileName' => 'report_MTask_2020.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 2020'],
                    ['fileName' => 'report_MTask_2021.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 2021'],
                    ['fileName' => 'report_MTask_2022.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 2022'],
                    ['fileName' => 'report_MTask_2023_q2.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 2кв 2023'],
                    ['fileName' => 'report_MTask_2023_q3.pdf', 'title' => 'Отчеты по выполнению Муниципального задания 3кв 2023']
                ]
            ],
            [
                'id'    => 4,
                'title' => 'Антикоррупционная деятельность',
                'docs'  => [
                    ['fileName' => 'CorruptPlan_2023-2024.doc', 'title' => 'План мероприятий Муниципального бюджетного учреждения «Южно-Сахалинский симфонический оркестр» по противодействию коррупции на 2023-2024 годы'],
                    ['fileName' => 'AboutConflictDiscords.pdf', 'title' => 'Положение «О конфликте интересов в Муниципальном бюджетном учреждении «Южно-Сахалинский симфонический оркестр»'],
                    ['fileName' => 'AboutUncorruptPolicy.pdf', 'title' => 'Положение «Об антикоррупционной политике в Муниципальном бюджетном учреждении «Южно-Сахалинский симфонический оркестр»'],
                    ['fileName' => 'BusinessGiftExchangeRules.pdf', 'title' => 'Правила обмена деловыми подарками и знаками делового гостеприимства в Муниципальном бюджетном учреждении «Южно-Сахалинский симфонический оркестр»'],
                    ['fileName' => 'ProfessionalCodeOfEthics.pdf', 'title' => 'Кодекс профессиональной этики и служебного поведения работников Муниципального бюджетного учреждения «Южно-Сахалинский симфонический оркестр»'],
                    ['fileName' => 'Order38-20.pdf', 'title' => 'Приказ №38/20 от 14.12.2020'],
                    ['fileName' => 'Order39-20.pdf', 'title' => 'Приказ №39/20 от 14.12.2020'],
                    ['fileName' => 'corruptUSSO2023.pdf', 'title' => 'Отчет за 2023']
                ]
            ],
            [
                'id'    => 5,
                'title' => 'Специальная оценка условий труда',
                'docs'  => [
                    ['fileName' => 'AssessmentOfWorkingConditions.pdf', 'title' => 'Оценка условий труда ЮССО']
                ]
            ],
            [
                'id'    => 6,
                'title' => 'Локальные нормативные акты',
                'docs'  => [
                    ['fileName' => 'EnergyEfficiencyProgram_2022-2024.pdf', 'title' => 'Программа энергоэффективности ЮССО 2022-2024']
                ]
            ]
        ];

    }

    public static function moveToFolder(): void
    {
        User::auth([15]);
        Request::checkEmpty(['docId', 'folderId']);

        $doc = FileDoc::byId($_POST['docId'])
            ?: throw new AppErr("{$_POST['docId']} does not exists", 'Запись не найдена');

        $doc->moveToFolder($_POST['folderId']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function clearTrash(): void
    {
        User::auth([15]);
        $trash = DocList::trash();
        $trash->del();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function setAsTrash(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        $doc = Doc::byId($_POST['id'])
            ?: throw new AppErr("{$_POST['id']} does not exists", 'Документ не найден');
        $doc->setAsTrash();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function trashList(): void
    {
        User::auth([15]);
        $List = DocList::trash();

        Response::data(['list' => $List->list]);
    }

    #[NoReturn] public static function resFromTrash(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        $doc = Doc::byId($_POST['id']);
        $doc->resFromTrash();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }


}