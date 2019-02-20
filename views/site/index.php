<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Морской бой';
?>
<div class="site-index">

    <div class="jumbotron">
        <?php
        if(Yii::$app->user->isGuest){
            ?><h1>Добро пожаловать!</h1>
            <p class="lead">В этом приложении ты можешь сыграть в морской бой! Только не забудь зарегистрироваться! Уже регистрировался? Войди в аккаунт!</p>
            <?= Html::a('Регистрация', ['/site/registration'], ['class' => 'btn btn-lg btn-success']);?>
            <?= Html::a('Вход в аккаунт', ['/site/login'], ['class' => 'btn btn-lg btn-success']);?>
        <?php
        }else{
            ?><h1>Сыграй, не скучай!</h1><?php
            $identity = Yii::$app->user->identity;
            ?><p class="lead"><?php echo $identity->firstName;?>! Скорее прими участие в своей игре!</p>
            <?= Html::a('Список игр', ['/game'], ['class' => 'btn btn-lg btn-success']);?>
        <?php
        }
        ?>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Правила</h2>

                <p>Правила игры в морской бой очень просты. Пройди по ссылке, чтобы освежить свою
                    память и принять участие в захватываюзей игре!</p>

                <p><a class="btn btn-default" href='/index.php?r=site/rules'>Правила &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Личный кабинет</h2>

                <p>
                    На сайте присутствует возможность входа в личный кабинет для просмотра
                    информации профиля и для ее изменения.
                </p>

                <p><a class="btn btn-default" href="/index.php?r=site/account">Личный кабинет &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Стек технологий</h2>

                <p>Перейдя по ссылке ниже, на странице будет отображен список применённых технологий в данном приложении.</p>

                <p><a class="btn btn-default" href="/index.php?r=site/about">Стек технологий &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
