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
        Request::checkEmpty(['entryId']);

        $Entry = Entry::byId($_POST['entryId'])
            ?: throw new NoContentErr();
        $Entry->initData();

        Response::data($Entry);
    }

    #[NoReturn] public static function listByYear(): void
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

    #[NoReturn] public static function listTop(): void
    {
        $topList = EntryList::top()->getList();
        Response::data($topList);
    }

    public static function update(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entry']);

        $data = $_POST['entry'];

        $Entry = Entry::byId($data['id']);

        $Entry->title = $data['title'] or
        throw new ValidationErr('Title is empty', 'Пустой заголовок');

        $Entry->announceId = $data['announceId'] ?? null;

        if(!Helpers::isDate($data['date'] ?? '')){
            throw new ValidationErr('isDate err', 'Недопустимый формат даты');
        }
        $Entry->date = $data['date'];

        $Entry->descr = $data['descr'] or
        throw new ValidationErr('descr err', 'Пустое описание');

        $Entry->markdown = $data['markdown'] or
        throw new ValidationErr('markdown err', 'Пустой текст');


        $categList = CategList::byBind($data['categs']);
        $Entry->categs = $categList->getList();

        $Entry->refLink = $data['refLink'] ?? '';
        $Entry->refName = $data['refName'] ?? '';
        $Entry->isExternal = $data['isExternal'];

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
        Request::checkEmpty(['entryId']);
        Request::checkSet(['markdown']);

        $Entry = Entry::byId($_POST['entryId']);
        $Entry->markdown = $_POST['markdown'];
        $Entry->initData();
        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Entry->parsedMD);
    }


}