<?php

namespace App\Files;

enum FileStatus: string
{
    case Uploaded  = 'uploaded';
    case Process   = 'process';
    case Completed = 'completed';
    case Failed    = 'failed';
    case Trash     = 'trash';
    case Public    = 'public';
}

