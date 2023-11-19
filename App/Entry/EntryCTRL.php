<?php

namespace App\Entry;

use App\Entry\List\EntryList;
use App\Img\Entry\EntrySketch;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\Helpers;

class EntryCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        $Entry = Entry::create() or
        throw new AppErr('addNewEntry err', 'Не удалось добавить новость');

        Response::data($Entry);
    }

    public static function get(): void
    {
        $id = intval($_POST['id'] ?? 0) or
        throw new ValidationErr('id');

        $Entry = Entry::byId($id) or
        throw new AppErr("Entry $id is empty", 'Новость не найдена');
        $Entry->initData();

        Response::data($Entry);
    }

    public static function list(): void
    {
        $year = intval($_POST['year'] ?? 0);
        $category = $_POST['category'] ?? 'all';
        if($category === 'all') {
            $list = EntryList::byYear($year);
        } else {
            $list = EntryList::byCategory($year, $category);
        }

        Response::data($list->getList());
    }

    public static function toplist(): void
    {
        $list = EntryList::top();
        Response::data($list->getList());
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


        $categList = CategList::byBind($newEntry['categs']);
        $Entry->categs = $categList->getList();

        $Entry->refLink = $newEntry['refLink'] ?? '';
        $Entry->refName = $newEntry['refName'] ?? '';

        $Entry->putToDB();

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $entryId = $_POST['entryId'] ?? throw new ValidationErr();
        Entry::delById($entryId);
        $Sketch = new EntrySketch($entryId);
        $Sketch->delFiles();
        $photoFolder = Entry::imgFolder . '/' . $entryId;
        $photoFolder = FileHelper::fullPath($photoFolder);
        FileHelper::delDir($photoFolder);
        Response::success();
    }

    public static function hide(): void
    {
        User::auth([1, 2, 4]);

        $entryId = intval($_POST['entryId']) or throw new ValidationErr();
        $Entry = EntryDTO::byId($entryId);
        $Entry->isShow = false;
        $Entry->putToDB();

        Response::success();
    }

    public static function show(): void
    {
        User::auth([1, 2, 4]);

        $entryId = intval($_POST['entryId']) or throw new ValidationErr();
        $Entry = EntryDTO::byId($entryId);
        $Entry->isShow = true;
        $Entry->putToDB();

        Response::success();
    }


}