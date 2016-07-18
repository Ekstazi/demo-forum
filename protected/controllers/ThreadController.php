<?php

namespace app\controllers;


use app\components\Controller;
use app\components\NotFoundException;
use app\components\Request;
use app\models\Thread;

class ThreadController extends Controller
{
    public function actionCreate(Request $request)
    {
        $model = $this->getRepository('thread')->create();
        if ($model->load($request->post()) && $model->save()) {
            $this->redirect('site/index');
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(Request $request)
    {
        /** @var Thread $model */
        $model = $this->getRepository('thread')->findByPk($request->get('thread'));
        if(!$model->canUpdate()) {
            throw new NotFoundException();
        }
        if ($model->load($request->post()) && $model->save()) {
            $this->redirect('site/index');
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(Request $request)
    {
        /** @var Thread $model */
        $model = $this->getRepository('thread')->findByPk($request->get('thread'));
        if(!$model->canDelete()) {
            throw  new NotFoundException();
        }
        $model->delete();
        $this->redirect('site/index');
    }

    public function actionView(Request $request)
    {
        /** @var Thread $model */
        $model = $this->getRepository('thread')->findByPk($request->get('thread'));
        if(!$model) {
            throw  new NotFoundException();
        }
        
        return $this->render('view', [
            'thread' => $model,
            'messages' => $model->getMessages()
        ]);
    }
}