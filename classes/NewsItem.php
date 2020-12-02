<?php


class NewsItem
{
    public int $id;
    public string $tit = 'Заголовок новости';
    public string $descr = 'Краткое описание';
    public string $content = 'Текст новости';
    public string $img = 'img/news/default_news_img.svg';
    public string $ver = '892648eb37542d469424b3448268d452';
    public string $date = '01.01.1970';
    public int $show = 0;

    function InitById(int $new_id)
    {
        $qwe = qwe("
        SELECT * from `news`
        WHERE `new_id` = '$new_id'
        ");
        if(!$qwe or !$qwe->num_rows)
            return false;
        $q = mysqli_fetch_object($qwe);

        return self::InitByQwe($q) ;
    }

    function InitByQwe(object $q)
    {
        $this->id = $q->new_id;
        $this->tit = $q->new_tit ?? '';
        $this->descr = $q->descr ?? '';
        $this->content = $q->content ?? '';
        $this->img = $q->img ?? '';
        if(empty($this->img))
            $this->img = 'img/news/default_news_img.svg';
        $this->ver = '?ver=' . md5_file($_SERVER['DOCUMENT_ROOT'] . '/' . $this->img);
        $this->date = $q->date;
        $this->show = $q->show;


        return true;
    }

    function PrintItem()
    {

        ?>
        <br><hr><br>
        <div class="narea">
        <div class="nimg_block">
            <div>
                <a href="new.php?new_id=<?php echo $this->id;?>">
                    <img src="<?php echo $this->img, $this->ver?>" width="260px" alt="<?php echo $this->tit;?>"/>
                </a>
            </div>
        </div>


        <div class="tcol">
            <div class="ntitle">
                <a href="new.php?new_id=<?php echo $this->id;?>">
                    <b><?php echo $this->tit;?></b>
                </a>
            </div>
            <br>
            <a href="new.php?new_id=<?php echo $this->id;?>">
                <?php echo $this->descr;?>
            </a>
            <br><br>
            <?php
            $ndate = strtotime($this->date);
            $ndate = ru_date('',$ndate);
            ?>
            <span class="ndate"><?php echo $ndate;?></span>
            <br>
        </div>
        </div><?php
    }
}