<?php

class StaffGroup
{
    public int         $group_id;
    public string|null $group_name;
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

    public static function byId(int $group_id) : StaffGroup|bool
    {
        $qwe = qwe2("
            SELECT * FROM `groups` 
            WHERE group_id = :id",
                   ['id' => $group_id]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,"StaffGroup")[0];
    }

    public static function getCollection() : array|bool
    {
        $qwe = qwe2("
            SELECT * FROM `groups` 
            WHERE group_id < 1000
            AND priority"
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $groups = $qwe->fetchAll(PDO::FETCH_CLASS,"StaffGroup");
        $arr = [];

        foreach ($groups as $group){
            $group = StaffGroup::byQ($group);
            $group->initPlayers();
            $arr[$group->priority] = $group;
        }
        return self::getUngrouped($arr);
    }

    public static function getUngrouped(array $groups): array|bool
    {
        $complited = $groups2 = [];

        foreach ($groups as $group){
            $group = StaffGroup::byQ($group);
            $complited = array_merge($complited,array_column($group->Players, 'pers_id'));
            $groups2[$group->priority] = $group;
        }
        if(!count($complited)){
            return [];
        }
        sort($complited);
        $complited = implode(',',$complited);
        $qwe = qwe2("SELECT 
        id as pers_id,
       name,
       last_name,
       '2000-01-01' as start,
       '2037-12-31' as stop
       FROM personal WHERE id not in ($complited)");
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"StaffPlace");
        $arr = [];

        foreach ($qwe as $q){
            $arr[] = StaffPlace::byQ($q);
        }
        //printr($groups2);
        $groups2[17]->Players = $arr;
        return $groups2;
    }

    public static function byQ(StaffGroup $group)  : StaffGroup|bool
    {
        return $group;
    }

    public static function byArray(array|object $group) : StaffGroup
    {
        $group = (object) $group;
        $StaffGroup = new StaffGroup();
        $StaffGroup->group_id = $group->group_id;
        $StaffGroup->group_name = $group->group_name;
        $StaffGroup->Players = [];

        foreach ($group->Players as $chair => $player){
            $newPlaceId = StaffPlace::getPlaceIdByChair($StaffGroup->group_id, $chair);
            $Player = StaffPlace::byArray($player);
            $Player->place_id = $newPlaceId;
            $StaffGroup->Players[$chair] = $Player;
        }
        return $StaffGroup;
    }

    private function initPlayers(string $date = '')
    {
        $this->Players = StaffPlace::getCollection($this->group_id,$date);
    }

    public function editPlacesOrder()
    {
        foreach ($this->Players as $player){
            $player = StaffPlace::byQ($player);
            $player->start = date('Y-m-d',time());
            $player->updatePlace();
        }
    }

    public function setUngroupedList(string $date = ''){
        foreach ($this->Players as $player){
            $player = StaffPlace::byQ($player);
            $player->setUngrouped($date);
        }
    }
}