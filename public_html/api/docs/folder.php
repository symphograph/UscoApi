<?php

use App\Docs\DocCTRL;
use App\Docs\FolderCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'add' => FolderCTRL::add(),
    'del' => FolderCTRL::del(),
    'setAsTrash' => FolderCTRL::setAsTrash(),
    'resFromTrash' => FolderCTRL::resFromTrash(),
    'rename' => FolderCTRL::rename(),
    'posUp' => FolderCTRL::posUp(),
    'posDown' => FolderCTRL::posDown(),
    'get' => FolderCTRL::get(),
    'list' => FolderCTRL::list(),
    default => throw new ApiErr()
};