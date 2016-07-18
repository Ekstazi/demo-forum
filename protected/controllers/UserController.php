<?php

namespace app\controllers;


use app\components\App;
use app\components\Controller;
use app\components\NotFoundException;
use app\components\Request;
use app\models\LoginForm;
use app\models\User;
use app\repositories\UserRepository;

class UserController extends Controller
{
    public function actionLogin(Request $request)
    {
        $app = App::instance();
        $model = new LoginForm();
        if ($model->load($request->post()) && $model->validate()) {
            $app->getUser()->authenticate($model->userModel, $model->rememberMe);
            $this->redirect($app->getUrlManager()->defaultRoute);
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionRegister(Request $request)
    {
        /** @var User $model */
        $model = $this->getRepository('user')->create();
        if ($model->load($request->post()) && $model->save()) {
            $content = $this->renderPartial('mail', ['model' => $model]);
            App::instance()->getMailer()->sendEmail(
                $model->email,
                'Активация аккаунта на сайте ' . $request->getServerName(),
                $content
            );
            $this->redirect('confirm');
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionConfirm(Request $request)
    {
        return $this->render('confirm');
    }

    public function actionActivate(Request $request)
    {
        /** @var UserRepository $repo */
        $repo = $this->getRepository('user');
        /** @var User $user */
        $user = $repo->findByConfirmKey($request->get('hash', ''));
        if (!$user || $user->active) {
            throw new NotFoundException(404);
        }

        $user->active = 1;
        $user->save();
        App::instance()->getUser()->authenticate($user, true);
        $this->redirect(App::instance()->getUrlManager()->defaultRoute);
    }

    public function actionLogout(Request $request)
    {
        App::instance()->getUser()->logout();
        $this->redirect('site/index');
    }


}