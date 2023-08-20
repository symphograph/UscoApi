<?php
namespace App;

use PDO;

class StaffGroup
{
    public int         $groupId;
    public string|null $groupName;
    public int|null    $p_group;
    public int|null    $deep;
    public int|null    $boss;
    public int|null    $priority;
    public string|null $group_sname;
    public string|null $btn_name;
    public array|null  $Players;

    public function __set(string $name, $value): void
    {
    }

    public static function checkClass($Object) : self
    {
        return $Object;
    }

    public static function byId(int $groupId) : StaffGroup|bool
    {
        $qwe = qwe2("
            SELECT * FROM `groups` 
            WHERE groupId = :id",
                   ['id' => $groupId]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,"StaffGroup")[0];
    }

    public static function getCollection(string $date) : array|bool
    {
        $qwe = qwe2("
            SELECT * FROM `groups` 
            WHERE groupId < 1000
            AND priority"
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $groups = $qwe->fetchAll(PDO::FETCH_CLASS, self::class);
        $arr = [];

        foreach ($groups as $group){
            $group = self::checkClass($group);
            $group->initPlayers($date);
            $arr[$group->priority] = $group;
        }
        return self::getUngrouped($arr,$date);
    }

    public static function getUngrouped(array $groups, string $date = ''): array
    {
        $complited = $groups2 = [];

        foreach ($groups as $group){
            $group = self::checkClass($group);
            $complited = array_merge($complited,array_column($group->Players, 'persId'));
            $groups2[$group->priority] = $group;
        }
        if(!count($complited)){
            return [];
        }
        sort($complited);
        $complited = implode(',',$complited);

        return StaffPlace::getUngrouped($complited, $groups2, $date);

    }

    public static function byArray(array|object $group) : StaffGroup
    {
        $group = (object) $group;
        $StaffGroup = new StaffGroup();
        $StaffGroup->groupId = $group->groupId;
        $StaffGroup->groupName = $group->groupName;
        $StaffGroup->Players = [];

        foreach ($group->Players as $chair => $player){
            $newPlaceId = StaffPlace::getPlaceIdByChair($StaffGroup->groupId, $chair);
            $Player = StaffPlace::byArray($player);
            $Player->placeId = $newPlaceId;
            $StaffGroup->Players[$chair] = $Player;
        }
        return $StaffGroup;
    }

    private function initPlayers(string $date = '')
    {
        $this->Players = StaffPlace::getCollection($this->groupId,$date);
    }

    public function editPlacesOrder(string $date)
    {
        foreach ($this->Players as $player){
            $player = StaffPlace::byQ($player);
            $player->start = $date;
            $player->updatePlace($date);
        }
    }

    public function setUngroupedList(string $date = ''){
        foreach ($this->Players as $player){
            $player = StaffPlace::byQ($player);
            $player->setUngrouped($date);
        }
    }
}