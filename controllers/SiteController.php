<?php

namespace app\controllers;

use app\models\AccountForm;
use Yii;
use app\models\Login;
use app\models\Registration;

class SiteController extends BehaviorsController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Login action.
     * @return mixed
     */
    public function actionLogin(){
        if(!Yii::$app->user->isGuest){
            return $this->redirect('/index.php?r=game/index');
        }
        $model = new Login();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->login();
            return $this->redirect('/');
        } else {
            // либо страница отображается первый раз, либо есть ошибка в данных
            return $this->render('login', ['model' => $model]);
        }
    }

    /**
     * Registration of the new user.
     * @return mixed
     */
    public function actionRegistration(){
        $model = new Registration();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($user = $model->reg()){
                if(Yii::$app->getUser()->login($user)){
                    return $this->redirect('/');
                }
            }else{
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации');
                return $this->refresh();
            }
        }
        return $this->render('registration', compact('model'));
    }

    /**
     * Action with personal account.
     * @return mixed
     */
    public function actionAccount()
    {
        $id = Yii::$app->user->identity['id'];
        $model = new AccountForm($id);
        $isChanged = false;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $isChanged = $model->changeAccountInfo($id);
        }
        return $this->render('account',[
            'model'=>$model,
            'isChanged'=>$isChanged,
        ]);
    }

    /**
     * Logout of the current user.
     * @return mixed
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect('/');
    }

    /**
     * Game rules page
     * @return mixed
     */
    public function actionRules(){
        return $this->render('rules');
    }
}
