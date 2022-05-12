<?php

class AnoncePaje extends Anonce
{
    public string $verLink = '';
    public string $date = '';
    public string $time = '';

    /**
     * @throws ImagickException
     */
    public function __construct(int $evid = 0)
    {
        if(!$evid || !parent::byId($evid)) {
            $this->error = 'Анонс не найден';
            return false;
        }

        return true;
    }

    public function getHtml(): string
    {
        $host = 'https://' . $_SERVER['SERVER_NAME'] . '/';
        ob_start();
        ?>
        <div class="eventboxl">
            <div class="eventbox">
                <img src="<?php echo $this->Poster->verLink ?>" width="100%" alt="изображение">
            </div>
            <div class="eventboxin">
                <div class="text">
                    <div class="eventcol"></div>
                    <p><b><?php echo self::getProgNameClean() ?></b></p>
                    <br>
                    <p><b><?php echo $this->fDate() . ' ' . $this->fTime() ?></b></p>
                    <?php echo $this->description ?>
                    <?php
                    if(strtotime($this->datetime) > strtotime('2020-03-10')) {
                        ?>
                        <br><br>
                        <small>Уважаемые посетители, убедительная просьба соблюдать
                            меры безопасности в связи с распространением коронавирусной инфекции!</small>
                        <?php
                    }
                    ?>


                    <br><br>
                    Справки по тел:<br>
                    <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div>
                    <br>
                    <div class="tel"><a href="tel:+79624163689">+7-962-416-36-89</a></div>
                    <br>
                </div>
                <div class="share-buttons">
                    <div class="fbb">
                        <!-- Put this script tag to the <head> of your page -->
                        <script type="text/javascript" src="https://vk.com/js/api/share.js?95"
                                charset="windows-1251"></script>

                        <!-- Put this script tag to the place, where the Share button will be -->
                        <script type="text/javascript">
                            document.write(VK.Share.button(false, {type: "round", text: "Поделиться"}));
                        </script>
                    </div>
                    <div class="fbb">
                        <div class="fb-share-button"
                             data-href="<?php echo $host . 'event.php?evid=' . $this->ev_id ?>"
                             data-layout="button_count">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @throws ImagickException
     */
    public function reCachePoster(int $size, string $file): bool
    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/'.$file;
        if(!file_exists($file) or is_dir($file))
            return false;
        $image = new Imagick($file);
        $image->setImageFormat("jpeg");
        $image->stripimage();
        $image->setImageResolution(72, 72);
        $image->resampleImage(72, 72, \Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($size, 0, 0, 1);

        $newFile = 'img/posters/' . $size . '/' . pathinfo($file, PATHINFO_FILENAME) . '.jpg';
        try {
            $image->writeImage($_SERVER['DOCUMENT_ROOT'].'/'.$newFile);
        } catch (ImagickException $e) {
            return false;
        }

        $this->Poster = new Img($newFile);
        return $this->Poster->exist;

    }



    public static function apiValidation(): array|bool
    {
        if(empty($_POST)) {
            return false;
        }
        $id = $_POST['id'] ?? 0;
        $id = intval($id);
        if(!$id)
            return false;

        return ['id' => $id];
    }

    public static function getJson(int $id): bool|Anonce
    {
        $Anonce = new AnoncePaje($id);
        if($Anonce->error) return $Anonce;
        $Anonce->verLink = 'https://'. $_SERVER['SERVER_NAME'] .'/'.$Anonce->Poster->verLink;
        $Anonce->prog_name = strip_tags($Anonce->prog_name);
        $Anonce->getTopImgUrl();
        $Anonce->date = date('Y-m-d', strtotime($Anonce->datetime));
        $Anonce->time = date('H:i', strtotime($Anonce->datetime));
        //$data = json_encode(['data' => $Anonce]);
        return $Anonce;
    }

    public static function byAPI()
    {
        if(empty($_POST['anonce'])){
           // return false;
        }
        $Anonce = new Anonce();
        return $Anonce;
    }
}