<?php
use app\components\helpers\Url;

/** @var $this \app\components\View */
/** @var $model \app\models\User */
?>

    Вы зарегистрировались на сайте <?= Url::absoluteBase(); ?>

    Регистрационные данные:
    email: <?= $model->email; ?>

    password: <?= $model->getPassword(); ?>

    Чтобы продолжить вам необходимо активировать ваш аккаунт
    Для активации перейдите по ссылке:

    <?= Url::absoluteTo(['user/activate', 'hash' => $model->confirm_key]); ?>