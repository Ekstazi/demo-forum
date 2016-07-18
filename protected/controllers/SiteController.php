<?php

namespace app\controllers;

use app\components\App;
use app\components\Controller;
use app\components\Request;

class SiteController extends Controller
{
    public function actionIndex(Request $request)
    {
        $threads = $this->getRepository('thread')->findAll();
        return $this->render('index', [
            'threads' => $threads
        ]);
    }

    
    
    public function actionError()
    {
        return $this->render('error');
    }
}