<?php

namespace App\Hall;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\PDO\DB;

class HallDTO implements HallITF
{
    use DTOTrait;
    const string tableName = 'halls';

    public int    $id;
    public string $name;
    public int    $suggestId;


    public static function newInstance(string $name, int $suggestId) :static
    {
        $hall = new static();
        $hall->name = $name;
        $hall->suggestId = $suggestId;
        return $hall;
    }

    public static function byName(string $name): static|false
    {
        $sql = "SELECT * FROM halls WHERE name = :name";
        $params = compact('name');
        return DB::qwe($sql, $params)
            ->fetchObject(static::class) ?? false;
    }

    public static function bySuggestId(int $suggestId): static|false
    {
        $sql = "SELECT * FROM halls WHERE suggestId = :suggestId";
        $params = compact('suggestId');
        return DB::qwe($sql, $params)
            ->fetchObject(static::class) ?? false;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSuggestId(): string
    {
        return $this->suggestId;
    }

    protected function beforePut(): void
    {
        (new HallValidator($this))->validate();
    }

    protected function afterPut(): void
    {
        if(!isset($this->id)) {
            $id = DB::lastId();
            $hall = static::byId($id);
            $this->bindSelf($hall);
            return;
        }
        $hall = static::bySuggestId($this->suggestId);
        $this->bindSelf($hall);
    }

}