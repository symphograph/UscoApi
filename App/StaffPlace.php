<?php
namespace App;

use PDO;

class StaffPlace
{
    public int|null    $persId;
    public int|null    $placeId;
    public int|null    $chair;
    public string|null $name;
    public string|null $lastName;
    public array|null $labels;
    public string|null $start;
    public string|null $stop;
    public string|null $ava;

    /**
     * @return bool|array<self>
     */
    public static function getCollection(int $groupId, string $date = ''): bool|array
    {
        if($groupId == 200){
            return [];
        }
        if(empty($date)){
            $date = date('Y-m-d');
        }
        $qwe = qwe2("
            SELECT pers_place.persId,
            pers_place.placeId,
            places.chair,
            personal.`name`,
            personal.lastName,
            pers_place.start,
            pers_place.stop
            FROM pers_place
            INNER JOIN places on pers_place.placeId = places.placeId
                AND places.groupId = :groupId
                AND :date BETWEEN start AND stop
            INNER JOIN employs ON employs.persId = pers_place.persId
                AND :date2 BETWEEN accept AND dismiss
            INNER JOIN `groups` g on places.groupId = g.groupId
            INNER JOIN personal ON personal.id = pers_place.persId
            ORDER BY chair",
                    ['groupId' => $groupId, 'date' => $date, 'date2' => $date]
        );
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,self::class);
        $arr = [];

        foreach ($qwe as $q){
            $arr[] = self::byQ($q);
        }
        return $arr;
    }

    public static function byQ(self $q) : self
    {
        $q->ava = $q->initAva();
        $q->labels = $q->getLabels();
        return $q;
    }

    private function initAva() : string
    {
        $path = 'img/avatars/';
        $ava = $path . 'small/ava_' . $this->persId . '_min.png';
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$ava)){
            $ava = $path.'init_ava.png';
        }

        return $ava;
    }

    public static function byArray(array|object $place) : StaffPlace
    {
        $place = (object) $place;
        $StaffPlace = new StaffPlace;
        $StaffPlace->persId = $place->persId;
        $StaffPlace->name = $place->name;
        $StaffPlace->lastName = $place->lastName;
        return $StaffPlace;
    }

    public static function getPlaceIdByChair(int $groupId, int $chair) : bool|int
    {
        $chair += 1;
        $qwe = qwe2("
            SELECT * FROM places 
            WHERE chair = :chair
            AND groupId = :groupId",
                    ['chair' => $chair,'groupId' => $groupId]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $q = $qwe->fetchObject();
        if(!$q->placeId){
            return false;
        }
        return $q->placeId;
    }

    public function updatePlace(string $start = '', string $stop = '')
    {
        if(empty($start)){
            $start = date('Y-m-d');
        }
        if(empty($stop)){
            $stop = '2037-12-31';
        }

        $qwe = qwe2("
            REPLACE INTO pers_place 
            (persId, start, stop, placeId) 
                VALUES 
            (:persId, :start, :stop, :placeId)",
                    [
                        'persId' => $this->persId,
                         'start'   => $start,
                         'stop'    => $stop,
                         'placeId' => $this->placeId
                    ]
        );
        StaffPlace::dateFixerList();
    }

    public static function dateFixerList(): bool
    {
        $qwe = qwe2("SELECT * FROM pers_place order by start desc");
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $list = $qwe->fetchAll();
        foreach ($list as $l){
            $l = (object) $l;
            self::dateFixer($l->persId,$l->start);
        }
        return true;
    }

    private static function dateFixer(int $persId, string $start)
    {
        $qwe = qwe2("
            SELECT * FROM pers_place 
            WHERE persId = :persId 
            AND start < :start
            ORDER BY start DESC 
            LIMIT 1",
                    ['persId' => $persId, 'start' => $start]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $q = $qwe->fetchObject();
        $prewStart = $q->start;
        if(strtotime($q->stop) >=  strtotime($start)){
            //printr($q);
            qwe2("
                UPDATE pers_place 
                SET stop = DATE_SUB(:start, INTERVAL 1 DAY)
                WHERE persId = :persId AND start = :start2",
                 ['start' => $start, 'persId' => $persId, 'start2' => $prewStart]
            );
            self::dateFixer($persId,$prewStart);
        }
        return true;
    }

    public function setUngrouped($date = '')
    {
        if(empty($date)){
            $date = date('Y-m-d',time() - 60*60*24);
        }
        $qwe = qwe2("SELECT * FROM pers_place WHERE persId = :persId
            ORDER BY start DESC LIMIT 1",['persId'=>$this->persId]);
        if(!$qwe or !$qwe->rowCount()){
            return;
        }
        $q = $qwe->fetchObject();

        if(strtotime($q->stop) <= strtotime($date)){
            return;
        }
        $start = $q->start;
        qwe2("
            UPDATE pers_place 
            SET stop = :stop 
            WHERE persId = :persId 
              AND start = :start",
        ['stop'=>$date, 'persId'=>$this->persId,'start' => $start]
        );
    }

    private function getLabels() : array
    {
        $qwe = qwe2("
            SELECT powers.name FROM pers_power 
            INNER JOIN powers on pers_power.powerId = powers.id
            AND pers_power.persId = :persId
            AND powers.siteVisible
            ORDER BY siteVisible",
            ['persId' => $this->persId]
        );
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        $qwe = $qwe->fetchAll();
        return $qwe;
    }

    public static function getUngrouped(string $complited, array $groups, string $date = ''): array
    {
        if(empty($date)) $date = date('Y-m-d');
        $qwe = qwe2("SELECT 
            personal.id as persId,
            name,
            lastName,
            '2000-01-01' as start,
            '2037-12-31' as stop
            FROM personal 
                inner join employs e on personal.id = e.persId
                    and :date between e.accept and e.dismiss
                inner join jobs j on e.jobId = j.id
                    and j.groupId = 10
            WHERE personal.id not in ($complited)", ['date' => $date]
        );
        if(!$qwe or !$qwe->rowCount()){
            return $groups;
        }
        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,get_class());
        $arr = [];

        foreach ($qwe as $q){
            $arr[] = self::byQ($q);
        }
        $groups[17]->Players = $arr;
        return $groups;
    }

}