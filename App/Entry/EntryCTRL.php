<?php

namespace App\Entry;

use Symphograph\Bicycle\Api\Action\ApiAction;
use App\Entry\List\EntryList;
use App\Img\Entry\EntrySketch;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\Helpers;
use Symphograph\Bicycle\HTTP\Request;

class EntryCTRL
{
    public static function add(): void
    {
        User::auth([13]);

        $Entry = Entry::create() or
        throw new AppErr('addNewEntry err', 'Не удалось добавить новость');

        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Entry);
    }

    public static function get(): void
    {
        Request::checkEmpty(['id']);

        $Entry = Entry::byId($_POST['id'])
            ?: throw new NoContentErr();
        $Entry->initData();

        Response::data($Entry);
    }

    #[NoReturn] public static function list(): void
    {
        Request::checkEmpty(['year']);
        $year = intval($_POST['year'] ?? 0);
        $category = $_POST['category'] ?? 'all';
        if($category === 'all') {
            $list = EntryList::byYear($year);
        } else {
            $list = EntryList::byCategory($year, $category);
        }

        Response::data($list->getList());
    }

    #[NoReturn] public static function toplist(): void
    {
        $list = EntryList::top();
        Response::data($list->getList());
    }

    public static function update(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entry']);

        $newEntry = $_POST['entry'];

        $id = intval($newEntry['id'] ?? 0) or
        throw new ValidationErr('id');

        $Entry = Entry::byId($id) or
        throw new AppErr('Entry::byId err');

        $Entry->title = $newEntry['title'] or
        throw new ValidationErr('title', 'Пустой заголовок');

        $Entry->announceId = $newEntry['announceId'] ?? null;

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
        $Entry->isExternal = $newEntry['isExternal'];

        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function del(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId']);

        Entry::delById($_POST['entryId']);
        $Sketch = new EntrySketch($_POST['entryId']);
        $Sketch->delFiles();

        $photoFolder = Entry::imgFolder . '/' . $_POST['entryId'];
        $photoFolder = FileHelper::fullPath($photoFolder, true);
        FileHelper::delDir($photoFolder);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function hide(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId']);

        $Entry = EntryDTO::byId($_POST['entryId']);
        $Entry->isShow = false;
        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function show(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId']);

        $Entry = EntryDTO::byId($_POST['entryId']);
        $Entry->isShow = true;
        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function updateMarkdown(): void
    {
        User::auth([13]);
        Request::checkEmpty(['id']);
        Request::checkSet(['markdown']);

        $Entry = Entry::byId($_POST['id']);
        $Entry->markdown = $_POST['markdown'];
        $Entry->initData();
        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Entry->parsedMD);
    }


}