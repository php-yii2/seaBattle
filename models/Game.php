<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property int $left_gamer
 * @property int $right_gamer
 * @property string $attack_side
 *
 * @property User $leftGamer
 * @property User $rightGamer
 */
class Game extends \yii\db\ActiveRecord
{
    const SIDE_LEFT = 'left';
    const SIDE_RIGHT = 'right';

    /**
     * Масив, где ключи - стороны а значения экземпляры класса Field
     * @var $fields array
     */
    protected $fields;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['left_gamer', 'right_gamer'], 'required'],
            [['left_gamer', 'right_gamer'], 'default', 'value' => null],
            [['left_gamer', 'right_gamer'], 'integer'],
            [['attack_side'], 'default', 'value' => self::getFirstStepSide()],
            [['attack_side'], 'string', 'max' => 255],
            [['left_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['left_gamer' => 'id']],
            [['right_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['right_gamer' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'left_gamer' => 'Игрок слева',
            'right_gamer' => 'Игрок справа',
            'attack_side' => 'Атакующая сторона',
        ];
    }

    public static function getFirstStepSide()
    {
        return self::SIDE_LEFT;
    }

    /**
     * Отдает пользователя делающего текущий ход
     */
    public function getCurrentUser() : User
    {
        switch ($this->attack_side){
            case self::SIDE_RIGHT;
            return $this->rightGamer;
            case self::SIDE_LEFT;
            return $this->leftGamer;
        }
    }

    /**
     * @return Field - Shot field
     */
    public function getAttackedField()
    {
        return $this->getField($this->getAttackedSide());
    }

    /**
     * return string Name of attacked side
     */
    public function getAttackedSide()
    {
        switch ($this->attack_side){
            case self::SIDE_RIGHT;
                return self::SIDE_LEFT;
            case self::SIDE_LEFT;
                return self::SIDE_RIGHT;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeftGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'left_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRightGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'right_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitorList(){
        return User::find()
            ->where(['!=', 'id', Yii::$app->user->identity['id']])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT]);
    }

    public function getRightDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT]);
    }

    public function getSideDecks($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightDecks;
            case self::SIDE_LEFT;
                return $this->leftDecks;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT]);
    }

    public function getRightSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT]);
    }

    public function getSideSteps($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightSteps;
            case self::SIDE_LEFT;
                return $this->leftSteps;
        }
    }

    /**
     * Возвращает сторону текущего игрока
     * @return string
     */
    public function getPlayerSide()
    {
        $currentPlayerId = Yii::$app->user->identity['id'];
        $leftGamerId = $this->left_gamer;
        if ($currentPlayerId === $leftGamerId){
            $currentPlayerSide = Game::SIDE_LEFT;
        }else{
            $currentPlayerSide = Game::SIDE_RIGHT;
        }
        return $currentPlayerSide;
    }


    /**
     * Определяет, есть ли на поле текущего игрока корабли
     * @param string $playerSide Сторона, за которую играет пользователь
     * @return bool Возвращает true если, есть хотя бы один корабль, false, если нет ни 1 корабля
     */
    public function isSetFigure($playerSide)
    {
        $figure = Figure::findAll([
            'side'=> $playerSide,
            'game_id' => $this->id,
        ]);
        if (empty($figure)){
            return false;
        }else{
            return true;
        }
    }

    public function getUserToken($userId){
        $gamer = User::findOne([
            'id' => $userId,
        ]);
        return $gamer->token;
    }

    public function getField($side) : Field
    {

        if (($this->fields[$side] ?? null) instanceof Field) {
            return $this->fields[$side];
        }
        // если мы раньше не обращались к полю - его надо сформировать из палуб и прошлых выстрелов
        $this->fields[$side] = new Field(['decks' => $this->getSideDecks($side), 'steps' => $this->getSideSteps($side)]);
        return $this->fields[$side];
    }

    /**
     * Нуждается ли игра в заполении (с какой стороны)
     * @return string|false
     */
    public function isNeedToFillBySide()
    {
        if (!$this->leftDecks) {
            return self::SIDE_LEFT;
        }
        if (!$this->rightDecks){
            return self::SIDE_RIGHT;
        }
        return false;
    }

    public function getNext()
    {
        return ($this->attack_side == self::SIDE_LEFT) ? self::SIDE_RIGHT : self::SIDE_LEFT;
    }

    public function fillSide($data){
        $side = $this->isNeedToFillBySide();
        foreach ($data as $coordinates){
            $coordinates = array_values(array_flip($coordinates));
            $deck =  new Figure();
            $deck->coordinates = array_shift($coordinates);
            $deck->side = $side;
            $deck->game_id = $this->id;
            $deck->save();
        }
        $this->attack_side = $this->getNext();
        $this->save();
        $this->refresh();
    }

    /**
     * @param $data  - входящие даные
     */
    public function step($data){
        $first = array_keys($data);
        $coord = array_shift($first);
        $field = $this->getAttackedField();
        $newState = $field->getCell($coord)->applyRule(
            [
                Cell::EMPTY_STATE => Cell::MISS_STATE,
                Cell::DECK_STATE => Cell::HIT_STATE
            ]
        );

        //создание нового хода
        $step = new Step();
        $step->coordinates = $coord;
        $step->side = $this->getAttackedSide();
        $step->game_id = $this->id;
        $step->result = $newState;
        $step->save();

        // если не попал - ход отдается другому игроку
        if (in_array($newState, [Cell::MISS_STATE])) {
            $this->attack_side = $this->getNext();
            $this->save();
        }
    }
}
