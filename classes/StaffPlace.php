<?php

class StaffPlace
{
    public int|null    $pers_id;
    public int|null    $place_id;
    public int|null    $chair;
    public string|null $name;
    public string|null $last_name;
    public array|null $labels;
    public string|null $start;
    public string|null $stop;
    public string|null $ava;

    public static function getCollection(int $group_id, string $date = ''): bool|array
    {
        if($group_id == 200){
            return [];
        }
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $qwe = qwe2("
            SELECT pers_place.pers_id,
            pers_place.place_id,
            places.chair,
            personal.`name`,
            personal.last_name,
            pers_place.start,
            pers_place.stop
            FROM pers_place
            INNER JOIN places on pers_place.place_id = places.place_id
                AND places.group_id = :group_id
                AND :date BETWEEN start AND stop
            INNER JOIN reglist_ls ON reglist_ls.pers_id = pers_place.pers_id
                AND :date2 BETWEEN accept AND dismiss
            INNER JOIN `groups` g on places.group_id = g.group_id
            INNER JOIN personal ON personal.id = pers_place.pers_id
            ORDER BY chair",
                    ['group_id' => $group_id, 'date' => $date, 'date2' => $date]
        );
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"StaffPlace");
        $arr = [];

        foreach ($qwe as $q){
            $arr[] = self::byQ($q);
        }
        return $arr;
    }

    public static function byQ(StaffPlace $q) : StaffPlace
    {
        $q->ava = $q->initAva();
        $q->labels = $q->getLabels();
        return $q;
    }

    private function initAva() : string
    {
        $path = 'img/avatars/';
        $ava = $path . 'small/ava_' . $this->pers_id . '_min.png';
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$ava)){
            $ava = $path.'init_ava.png';
        }

        return $ava;
    }

    public static function byArray(array|object $place) : StaffPlace
    {
        $place = (object) $place;
        $StaffPlace = new StaffPlace;
        $StaffPlace->pers_id = $place->pers_id;
        $StaffPlace->name = $place->name;
        $StaffPlace->last_name = $place->last_name;
        return $StaffPlace;
    }

    public static function getPlaceIdByChair(int $group_id, int $chair) : bool|int
    {
        $chair += 1;
        $qwe = qwe2("
            SELECT * FROM places 
            WHERE chair = :chair
            AND group_id = :group_id",
                    ['chair' => $chair,'group_id' => $group_id]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $q = $qwe->fetchObject();
        if(!$q->place_id){
            return false;
        }
        return $q->place_id;
    }

    public function updatePlace(string $start = '', string $stop = '')
    {
        if(empty($start)){
            $start = date('Y-m-d', time());
        }
        if(empty($stop)){
            $stop = '2037-12-31';
        }

        $qwe = qwe2("
            REPLACE INTO pers_place 
            (pers_id, start, stop, place_id) 
                VALUES 
            (:pers_id, :start, :stop, :place_id)",
                    [
                        'pers_id' => $this->pers_id,
                         'start'   => $start,
                         'stop'    => $stop,
                         'place_id' => $this->place_id
                    ]
        );
        StaffPlace::dateFixerList();
    }

    public static function dateFixerList()
    {
        $qwe = qwe2("SELECT * FROM pers_place order by start desc");
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $list = $qwe->fetchAll();
        foreach ($list as $l){
            $l = (object) $l;
            self::dateFixer($l->pers_id,$l->start);
        }
    }

    private static function dateFixer(int $pers_id, string $start)
    {
        $qwe = qwe2("
            SELECT * FROM pers_place 
            WHERE pers_id = :pers_id 
            AND start < :start
            ORDER BY start DESC 
            LIMIT 1",
                    ['pers_id' => $pers_id, 'start' => $start]
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
                WHERE pers_id = :pers_id AND start = :start2",
                 ['start' => $start, 'pers_id' => $pers_id, 'start2' => $prewStart]
            );
            self::dateFixer($pers_id,$prewStart);
        }
        return true;
    }

    public function setUngrouped($date = '')
    {
        if(empty($date)){
            $date = date('Y-m-d',time() - 60*60*24);
        }
        $qwe = qwe2("SELECT * FROM pers_place WHERE pers_id = :pers_id
            ORDER BY start DESC LIMIT 1",['pers_id'=>$this->pers_id]);
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
            WHERE pers_id = :pers_id 
              AND start = :start",
        ['stop'=>$date, 'pers_id'=>$this->pers_id,'start' => $start]
        );
    }

    private function getLabels() : array
    {
        $qwe = qwe2("
            SELECT job_name FROM pers_job 
            INNER JOIN jobs ON pers_job.job_id = jobs.id 
            AND pers_job.pers_id = :pers_id
            AND jobs.site_visible
            ORDER BY site_visible",
            ['pers_id' => $this->pers_id]
        );
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        $qwe = $qwe->fetchAll();
        return $qwe;
    }

}