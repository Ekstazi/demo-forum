<?php

namespace app\controllers;


use app\components\Controller;
use app\components\NotFoundException;
use app\components\Request;
use app\models\Message;
use app\models\Thread;

class MessageController extends Controller
{
    public function actionCreate(Request $request)
    {
        /** @var Message $model */
        $model = $this->getRepository('message')->create();
        $model->thread_id = $request->get('thread');
        if ($model->load($request->post()) && $model->save()) {
            $this->redirect('thread/view', ['thread' => $model->thread_id]);
        }
        return $this->render('create', [
            'model' => $model,
            'thread' => $model->getThread(),
        ]);
    }

    public function actionUpdate(Request $request)
    {
        /** @var Message $model */
        $model = $this->getRepository('message')->findByPk($request->get('message'));
        if (!$model->canUpdate()) {
            throw new NotFoundException();
        }
        if ($model->load($request->post()) && $model->save()) {
            $this->redirect('thread/view', ['thread' => $model->thread_id]);
        }
        return $this->render('update', [
            'model' => $model,
            'thread' => $model->getThread(),
        ]);
    }

    public function actionDelete(Request $request)
    {
        /** @var Message $model */
        $model = $this->getRepository('message')->findByPk($request->get('message'));
        if (!$model->canDelete()) {
            throw  new NotFoundException();
        }
        $model->delete();
        $this->redirect('thread/view', ['thread' => $model->thread_id]);
    }
}