<?php

namespace App\Entry\Sections;

class SectionList
{
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
        return $SectionList;
    }

    public function getList(): array
    {
        return $this->list;
    }
}