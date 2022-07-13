<?php

use JetBrains\PhpStorm\Pure;

class Email
{
    public const recipients = [
        'sakh-orch.ru'=>'mbu-gko@yandex.ru',
        'api.sakh-orch.ru'=>'mbu-gko@yandex.ru',
        'test.sakh-orch.ru'=>'roman.chubich@gmail.com',
        'tapi.sakh-orch.ru'=>'roman.chubich@gmail.com'
    ];

    public string $email   = '';
    public string $name    = '';
    public string $msg     = '';
    public string $to      = '';
    public string $subject = '';
    public bool   $isValid = false;
    public string $error   = '';
    public string $html  = '';


    public static function htmlMsg(string $name, string $email, string $msg): string
    {
        $msg = str_replace("\n", '<br>', $msg);
        return <<<HTML
            <div style="color: black; font-size: 16px">
                Получено сообщение из формы обратной связи на сайте sakh-orch.ru<br><br>
                Имя: $name <br>
                Email: $email <br>
                Текст сообщения:<br>
                <div style="
                font-size: 14px;
                min-width: 20em;
                min-height: 10em;
                border: 2px solid rgba(215,181,137,1.00); 
                border-radius: 0.5em;
                background-color: #eaddcb;
                padding: 1em;
                ">
                    $msg 
                </div><br>
                <span style="color: red; font-size: 14px">
                Нет никаких гарантий того, что имя или email соответствуют действительности.
                <span><br>
            </div>
        HTML;
    }

    public static function GetNextFreeFeedMailId(): int
    {
        $qwe = qwe("
            SELECT max(`msg_id`) as `max`
            FROM `feed_mails`
            ");
        if(!$qwe or $qwe->rowCount() != 1)
            return 1;

        $q = $qwe->fetchObject();

        return $q->max + 1;
    }

    public static function headers(string $email)
    {
        return [
            'Content-type' => 'text/html',
            'charset'=> 'utf-8',
            'From' => 'feedback@sakh-orch.ru',
            'Reply-To' => $email,
            'X-Mailer' => 'PHP/' . phpversion()
        ];
    }

    #[Pure] public static function byPost(): Email
    {
        $Email = new Email();

        $email = $_POST['email'] ?? '';
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $Email->error = 'email';
            return $Email;
        }
        $Email->email = $email;

        $name = $_POST['name'] ?? '';
        if(empty($name)){
            $Email->error = 'name';
            return $Email;
        }
        $Email->name = filter_var($name, FILTER_SANITIZE_STRING);

        $msg = $_POST['msg'] ?? '';
        if(empty($msg)){
            $Email->error = 'msg';
            return $Email;
        }
        $Email->msg = filter_var($msg, FILTER_SANITIZE_STRING);

        $Email->isValid = true;


        return $Email;
    }

    public function putToDb(): bool
    {


        $qwe = qwe("INSERT INTO `feed_mails` 
            (`msg_id`, `email`, `name`, `msg`,`msg_time`, `ip`,`agent`,`msg_key`) 
            VALUES 
            (:msg_id, :email, :name, :msg, now(), :ip, :agent, :msg_key)",
           [
               'msg_id'  => self::GetNextFreeFeedMailId(),
               'email'   => $this->email,
               'name'    => $this->name,
               'msg'     => $this->msg,
               'ip'      => $_SERVER['REMOTE_ADDR'],
               'agent'   => $_SERVER['HTTP_USER_AGENT'],
               'msg_key' => random_str(12)
           ]
        );
        if(!$qwe){
            $this->error = 'insertErr';
            return false;
        }
        return true;
    }

    public function send(): bool
    {
        $to      = self::recipients[$_SERVER['SERVER_NAME']];
        $subject = 'Сообщение от: '.$this->name;
        $html = self::htmlMsg($this->name,$this->email, $this->msg);
        $headers = self::headers($this->email);

        if(!mail($to, $subject, $html,$headers)){
            $this->error = 'sendErr';
            return false;
        }
        return true;
    }
}