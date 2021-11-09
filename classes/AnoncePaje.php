<?php

class AnoncePaje extends Anonce
{
    public Img $Poster;

    /**
     * @throws ImagickException
     */
    public function __construct(int $evid = 0)
    {
        if(!$evid || !parent::byId($evid)){
            return false;
        }

        self::getPosterUrl(self::imgSizeOptimal());
        return true;
    }

    public function getHtml() : string
    {
        $host = 'https://'.$_SERVER['SERVER_NAME'].'/';
        ob_start();
        ?>
        <div class="eventboxl">
            <div class="eventbox">
                <img src="<?php echo $this->Poster->verLink?>" width="100%" alt="изображение">
            </div>
            <div class="eventboxin">
                <div class="text">
                    <div class="eventcol"></div>
                    <p><b><?php echo self::getProgNameClean() ?></b></p>
                    <br>
                    <p><b><?php echo $this->fDate().' '.$this->fTime()?></b></p>
                    <?php echo $this->description?>
                    <?php
                    if (strtotime($this->datetime) > strtotime('2020-03-10')){
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
                        <script type="text/javascript" src="https://vk.com/js/api/share.js?95" charset="windows-1251"></script>

                        <!-- Put this script tag to the place, where the Share button will be -->
                        <script type="text/javascript">
                            document.write(VK.Share.button(false,{type: "round", text: "Поделиться"}));
                        </script>
                    </div>
                    <div class="fbb">
                        <div class="fb-share-button"
                             data-href="<?php echo $host.'event.php?evid='.$this->ev_id?>"
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
    public function getPosterUrl(int $size = 480) : bool
    {
        $file = 'img/posters/'.$size.'/'.$this->img;
        $this->Poster = new Img($file);
        if($this->Poster->exist){
            return true;
        }

        $file = 'img/posters/origins/'.$this->img;

        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$file)){
            $file = 'img/afisha/'.$this->img;
            if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$file)){
                return false;
            }
        }

        return $this->reCachePoster($size,$file);
    }

    /**
     * @throws ImagickException
     */
    public function reCachePoster(int $size, string $file): bool
    {
        $image = new Imagick($file);
        $image->setImageFormat ("jpeg");
        $image->stripimage();
        $image->setImageResolution(72,72);
        $image->resampleImage(72,72,\Imagick::FILTER_LANCZOS,1);
        $image->resizeImage($size,0,0,1);

        $newFile = 'img/posters/' . $size . '/' . pathinfo($file,PATHINFO_FILENAME) . '.jpg';
        try {
            $image->writeImage($newFile);
        } catch (ImagickException $e) {
            return false;
        }

        $this->Poster = new Img($newFile);
        return $this->Poster->exist;

    }

    private function imgSizeOptimal() : int
    {
        $agent = get_browser();
        if($agent->ismobiledevice){
            return 1080;
        }

        return 480;
    }
}