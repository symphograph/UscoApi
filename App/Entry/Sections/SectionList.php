<?php

namespace App\Entry\Sections;

class SectionList
{
    /**
     * @var SectionITF[]
     */
    private array $list = [];

    public static function byRawContent(string $rawContent): self
    {

        $SectionList = new self();

        $rawContent = str_replace('<br>',PHP_EOL, $rawContent);

        $rows = explode(PHP_EOL, $rawContent);
        $rows = array_map('trim', $rows);


        foreach ($rows as $row) {
            $SectionList->list[] = match (true) {
                str_starts_with($row, '![](') => new Img($row),
                str_starts_with($row, '![video](') => new Video($row),
                default => new Text($row)
            };
        }
        $SectionList->implodeTextSections();
        return $SectionList;
    }

    private function implodeTextSections(): void
    {
        $textArr = [];
        $list = [];
        foreach ($this->list as $row){
            if($row->getType() === 'text'){
                $textArr[] = $row->getContent();
                continue;
            }

            if(!empty($textArr)){
                $textSectionContent = implode(PHP_EOL, $textArr);
                $textArr = [];
                $list[] = new Text($textSectionContent);
            }

            $list[] = $row;
        }
        if(!empty($textArr)){
            $textSectionContent = implode(PHP_EOL, $textArr);
            $list[] = new Text($textSectionContent);
        }
        $this->list = $list;
    }

    public function getList(): array
    {
        return $this->list;
    }
}