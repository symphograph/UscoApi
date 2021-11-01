<meta charset="utf-8">
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip)
    die();
$an = new AnonceCard();
$an->byId(118);
printr($an);