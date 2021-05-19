<?php
if(!isset($ip)) exit();
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
$pajes =
    [


        ['details',
            ['ЮСКО',
                [
                'tsa.php'=>'Тигран Ахназарян',
                'staff.php'=>'Состав оркестра',
                'zag.php'=> 'Александр Зражаев',
                'history.php'=>'Историческая справка'
                ]
            ]
        ],
        'vacancies.php' => 'Вакансии',
        'complited.php'=>'Афиши',

        ['details',
            ['Медиа',
                [
                    'gallery.php'=>'Фото',
                    'video.php'=>'Видео'
                ]
            ]
        ],

        ['details',
            ['Новости',
                [
                    'news.php'=>'Новости',
                    'articles.php'=>'Статьи',
                    'outside_news.php'=>'Другие новости'
                ]
            ]
        ],

        'contacts.php'=>'Контакты',

        ['details',
            ['Документы',
                [
                    'main.php'=>'Основные сведения',
                    'documents.php'=>'Документы',
                    'corruptcomp.php' => 'Вместе против коррупции!'
                ]
            ]
        ]
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
CookWarning();
top_links();
	?>