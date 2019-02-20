<?php
/**
 * This is the model for the view site/account
 */
namespace app\models;
use yii\base\Model;

class AccountForm extends Model
{
    public $firstName;
    public $lastName;
    public $email;
    function __construct($id=null){
        if (isset($id)){
            $user = User::findOne([
                'id' => $id,
            ]);
            $this->firstName = $user->firstName;
            $this->lastName = $user->lastName;
            $this->email = $user->email;
        }
    }
    public function attributeLabels() {
        return [
            'firstName' => 'Имя',
            'lastName' => 'Фамилия',
            'email' => 'Email',
        ];
    }
    public function rules(){
        return [
            [['firstName', 'lastName', 'email'], 'required', 'message' => 'Заполните поле'],
            ['email', 'email'],
        ];
    }
    public function changeAccountInfo($id){
        $user = User::findOne($id);
        $user->firstName = $this->firstName;
        $user->lastName = $this->lastName;
        $user->email = $this->email;
        if($user->update($runValidation = false)!==null){
            return true;
        }else{
            return false;
        }
    }
}