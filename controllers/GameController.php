<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;


/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends BehaviorsController
{

    /**
     * Lists user's Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $currentUserId = Yii::$app->user->identity['id'];
        $dataProvider = new ActiveDataProvider([
            'query' => Game::find()
                ->where(['left_gamer' => $currentUserId])
                ->orWhere(['right_gamer' => $currentUserId])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Game();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('?r=game/index');
        }

        $competitorList = $model->getCompetitorList();
        $competitorList = ArrayHelper::map($competitorList,'id','firstName');
        $currentUserName = Yii::$app->user->identity['firstName'];
        $currentUserId = Yii::$app->user->identity['id'];
        $currentUser = [$currentUserId => $currentUserName];

        return $this->render('create', [
            'model' => $model,
            'competitorList' => $competitorList,
            'currentUser' =>$currentUser,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionStart($id)
    {
        $game = $this->findGame($id);
        // User token verification
        $userToken = Yii::$app->user->identity['token'];
        $leftGamerToken = $game->getUserToken($game->left_gamer);
        $rightGamerToken = $game->getUserToken($game->right_gamer);
        if ($userToken === $leftGamerToken or $userToken === $rightGamerToken){
            //Token verification passed successfully
            $playerSide = $game->getPlayerSide();
            if($game->attack_side!== $playerSide){
                return $this->render('waiting', [
                    'id' => $id,
                ]);
            }

            if ($game->isNeedToFillBySide()) {
                return $this->redirect('?r=game/fill-field&id='.$game->id);
            }

            $data = Yii::$app->request->post('step');
            if ($data) {
                $game->step($data);
            }

            // todo win check here

            if($game->attack_side!== $playerSide){
                return $this->render('waiting', [
                    'id' => $id,
                ]);
            }
            return $this->render('game', [
                'field' => $game->getAttackedField(),
                'user' => $game->getCurrentUser(),
                'id' => $game->id,
            ]);
        }else{
            //Token verification passed unsuccessfully
            throw new NotFoundHttpException('Вам нельзя заходить в эту игру');
        }
    }

    /**
     * Fills the fields based on game id.
     * @param $id.
     * @throws NotFoundHttpException if the game connot be found.
     * @throws NotFoundHttpException if the user tries to connect to the game in which he is not a member.
     * @return mixed.
     */
    public function actionFillField($id)
    {
        $data= Yii::$app->request->post('coordinates');

        $game = Game::findOne($id);
        if (!$game) {
            throw new NotFoundHttpException('игра не найдена');
        }

        $userToken = Yii::$app->user->identity['token'];
        $leftGamerToken = $game->getUserToken($game->left_gamer);
        $rightGamerToken = $game->getUserToken($game->right_gamer);
        if ($userToken === $leftGamerToken or $userToken === $rightGamerToken){
            $playerSide = $game->getPlayerSide();
            if($game->attack_side!== $playerSide){
                return $this->render('waiting', [
                    'id' => $id,
                ]);
            }
            $isSetFigure = $game->isSetFigure($playerSide);

            // нас редиректнули сюда значит нужно отобразить поле для заполнения, если нет ответа и поля не заполнены.
            if (!$data && $isSetFigure === false) {
                return $this->render('fill', [
                    'model' => $game,
                ]);
            }

            // если пришли данные нужно отдать их в игру
            if ($game->isNeedToFillBySide() && $data) {
                // todo validation of income data
                $game->fillSide($data);
            }

            // если еще надо заполнить что-то идем на второй круг
            if ($game->isNeedToFillBySide()) {
                return $this->redirect('?r=game/fill-field&id='.$game->id);
            }
            return $this->redirect('?r=game/start&id='.$game->id);
        }else{
            throw new NotFoundHttpException('Вам нельзя заходить в эту игру');
        }
    }


    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGame($id)
    {
        if (($game = Game::findOne($id)) !== null) {
            return $game;
        }
        throw new NotFoundHttpException('Игра не найдена');
    }
}
