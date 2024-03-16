<?php

namespace App\Entry\Sections;

use App\Files\FileIMG;

class Img extends Section
{
    public readonly string $type;
    public string          $md5;
    public string          $ext;
    public int             $fileId;

    public function __construct(protected string $input = '')
    {
        parent::__construct($input);
        $this->type = 'img';
        $this->extractValue();
        $this->initMD5();
        $this->initExt();
        $this->initId();
    }

    private function initMD5(): void
    {
        $this->md5 = pathinfo($this->content, PATHINFO_FILENAME);
    }

    private function initExt(): void
    {
        $this->ext = pathinfo($this->content, PATHINFO_EXTENSION);
    }

    private function initId(): void
    {
        $FileIMG = FileIMG::byMD5($this->md5);
        if(!$FileIMG) return;
        $this->fileId = $FileIMG->id;
    }
}