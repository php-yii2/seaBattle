<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

class BehaviorsController extends Controller {
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['registration', 'login'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => false,
                        'controllers' => ['game'],
                        'actions' => ['index'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['game'],
                        'actions' => ['create', 'start', 'fill-field', 'index', 'view'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['logout', 'account'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['index', 'about', 'rules', 'error'],
                    ],
                ]
            ],

        ];
    }
}