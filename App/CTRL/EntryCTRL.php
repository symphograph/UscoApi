<?php

namespace App\CTRL;

use App\Entry;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Helpers;

class EntryCTRL extends Entry
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        $Entry = Entry::addNewEntry() or
        throw new AppErr('addNewEntry err', 'Не удалось добавить новость');

        Response::data($Entry);
    }

    public static function get(): void
    {
        $id = intval($_POST['id'] ?? 0) or
        throw new ValidationErr('id');

        $Entry = Entry::byId($id) or
        throw new AppErr("Entry $id is empty", 'Новость не найдена');

        Response::data($Entry);
    }

    public static function list(): void
    {
        $category = ($_POST['category'] ?? false) or
        throw new ValidationErr('category', 'Категория не найдена');

        if (!$year = intval($_POST['year'] ?? 0)) {
            $year = intval(date('Y', time()));
        }

        $limit = intval($_POST['limit'] ?? 1000);

        $filters = [
            'usco'    => [0, 1, 1, 0],
            'euterpe' => [0, 0, 1, 0],
            'other'   => [0, 0, 0, 1],
            'all'     => [0, 1, 1, 1]
        ];


        if(!array_key_exists($_POST['category'], $filters)){
            throw new ValidationErr('category', 'Категория не найдена');
        }

        $Item = Entry::getCollection($year, $filters[$category], $limit) or
        throw new AppErr('Entry::getCollection is empty', 'Нет новостей');

        Response::data($Item);
    }

    public static function update(): void
    {
        User::auth([1, 2, 4]);


        $newEntry = $_POST['entry'] or
        throw new ValidationErr('entry');

        $id = intval($newEntry['id'] ?? 0) or
        throw new ValidationErr('id');

        $Entry = Entry::byId($id) or
        throw new AppErr('Entry::byId err');

        $Entry->title = $newEntry['title'] or
        throw new ValidationErr('title', 'Пустой заголовок');

        if(!Helpers::isDate($newEntry['date'] ?? '')){
            throw new ValidationErr('isDate err', 'Не вижу дату');
        }
        $Entry->date = $newEntry['date'];

        $Entry->descr = $newEntry['descr'] or
        throw new ValidationErr('descr err', 'Пустое описание');

        $Entry->markdown = $newEntry['markdown'] or
        throw new ValidationErr('markdown err', 'Пустой текст');

        $Entry->categs = $newEntry['categs'] or
        throw new ValidationErr('categs err', 'Ошибка определения категорий');

        $Entry->refLink = $newEntry['refLink'] ?? '';
        $Entry->refName = $newEntry['refName'] ?? '';

        $Entry->putToDB();

        Response::success();
    }


}