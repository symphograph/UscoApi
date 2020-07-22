<?php
if(!isset($ip)) exit();
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
$pajes =
    [
        'tsa.php'=>'Тигран Ахназарян',
        ['details',
            ['Оркестр',
                [
                'tsa.php'=>'Тигран Ахназарян',
                'staff.php'=>'Состав оркестра',
                'main.php'=>'Основные сведения',
                'history.php'=>'История',
                'zag.php'=> 'Александр Зражаев'
                ]
            ]
        ],

        ['details',
            ['Афиша',
                [
                    'anonces.php'=>'Предстоящие',
                    'complited.php'=>'Прошедшие'
                ]
            ]
        ],

        ['details',
            ['Медиа',
                [
                    'gallery.php'=>'Фото',
                    'video.php'=>'Видео'
                ]
            ]
        ],

        ['details',
            ['Пресса',
                [
                    'news.php'=>'Новости',
                    'articles.php'=>'Статьи'
                ]
            ]
        ],

        'contacts.php'=>'Контакты',
        'documents.php'=>'Документы'
    ];
//printr($pajes);
function top_links()
{
    global $host, $pajes;
	?>
	<input type="checkbox" id="nav-toggle" autocomplete="off" hidden>
	<nav class="nav no-print">
        <label for="nav-toggle" class="nav-toggle" onclick></label>
        <h2 class="glav">
            <a href="<?php echo $host;?>index.php">Главная</a>
        </h2>
        <ul>
            <?php LiList($pajes);?>
        </ul>
    </nav>
<div class="mask-content"></div>
<?php
}
function LiList(array $pajes)
{
    global $host;
    foreach ($pajes as $pp => $pn)
    {

        if(isset($pn[0]) and $pn[0] == 'details')
        {

            ?>
            <li>
                <details><summary><?php echo $pn[1][0];?></summary>
                <ul>
                    <?php LiList($pn[1][1]);?>
                </ul>
            </details>
            </li>
            <?php
        }
        else
        {
            ?><li><a href="<?php echo $host.$pp?>"> <?php echo $pn?></a></li><?php
        }

    }
}
top_links();
	?>