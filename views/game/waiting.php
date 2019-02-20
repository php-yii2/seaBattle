<?php

/* @var $id app\models\Game */

$page = '?r=game/fill-field&id='.$id;
header("Refresh:1; url=$page");
$this->title = 'Ожидайте хода другого игрока';
?>

<div class="site-index">
    <div class="jumbotron">
        <h1>Ожидайте хода другого игрока</h1>
    </div>
</div>
