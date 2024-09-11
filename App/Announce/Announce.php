<?php

namespace App\Announce;

use App\Entry\Sections\SectionList;
use App\Hall\Hall;
use App\Img\Announce\AnnouncePoster;
use App\Img\Announce\AnnounceSketch;
use ReflectionException;
use Symphograph\Bicycle\DTO\ModelTrait;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Files\FileIMG;
use Symphograph\Bicycle\Helpers;
use Symphograph\Bicycle\JsonDecoder;
use Symphograph\Bicycle\PDO\DB;

class Announce extends AnnounceDTO
{
    use ModelTrait;

    public ?string  $youtubeId = '';
    public bool     $completed = false;
    public ?Hall $Hall;
    public ?array   $parsedMD  = [];
    public FileIMG  $sketch;
    public FileIMG  $poster;

    public static function create(): bool|self
    {
        $Announce = Announce::byId(1) or throw new AppErr('Default Announce is missed');
        $Announce->eventTime = date('Y-m-d H:i:s');
        unset($Announce->id);

        $Announce->putToDB();
        $Announce->id = DB::lastId();

        return $Announce;
    }

    public static function isCompleted(int $id): bool
    {
        $AnnounceDTO = AnnounceDTO::byId($id);
        return Helpers::isExpired($AnnounceDTO->eventTime);
    }

    /**
     * @param string $Json
     * @return self
     * @throws ReflectionException
     */
    public static function decodeFromJson(string $Json): self
    {

        /** @var self $Announce */
        $Announce = JsonDecoder::cloneFromAny(json_decode($Json), self::class);
        return $Announce;
    }

    public static function delete(int $id): void
    {
        // parent::delById($id);
        qwe("DELETE FROM announces WHERE id = :id AND id != 1", ['id' => $id]);
        $Poster = new AnnouncePoster($id);
        $Poster->delFiles();
        $Sketch = new AnnounceSketch($id);
        $Sketch->delFiles();
    }

    public function initData(): self
    {
        $this->initHall();
        $this->initDateTime();

        $this->completed = Helpers::isExpired($this->eventTime);

        $this->initVideo();

        if (empty($this->verString)) {
            $this->initNewVerString();
            $this->putToDB();
        }

        $this->initParsedMD();
        $this->initSketch();
        $this->initPoster();

        return $this;
    }

    private function initHall(): static
    {
        $this->Hall = Hall::byId($this->hallId)->initData();
        return $this;
    }

    private function initDateTime(): static
    {
        if (empty($this->eventTime)) {
            $this->eventTime = date('Y-m-d H:i:s', time() + 3600 * 24);
        }
        $this->eventTime = date('Y-m-d H:i', strtotime($this->eventTime));
        return $this;
    }

    private function initVideo(): void
    {
        $qwe = qwe("select youtubeId from video where announceId = :id", ['id' => $this->id]);
        if (!$qwe || !$qwe->rowCount()) {
            return;
        }
        $qwe = $qwe->fetchObject();
        $this->youtubeId = $qwe->youtubeId ?? '';
    }

    public function initNewVerString(): void
    {
        $this->verString = bin2hex(random_bytes(12));
    }

    private function initParsedMD(): void
    {
        $sectionList = SectionList::byRawContent($this->description);
        $this->parsedMD = $sectionList->getList();
    }

    private function initSketch(): void
    {
        if(empty($this->sketchId)) {
            return;
        }
        $sketch = FileIMG::byId($this->sketchId ?? 0);
        if ($sketch) {
            $this->sketch = $sketch;
        }
    }

    private function initPoster(): void
    {
        if(empty($this->posterId)) {
            return;
        }
        $poster = FileIMG::byId($this->posterId);
        if ($poster) {
            $this->poster = $poster;
        }
    }



}