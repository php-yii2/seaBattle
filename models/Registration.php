<?php
namespace app\models;
use yii\base\Model;

/**
 * Registration class registers the user, connected with the register form
 */

class Registration extends Model{

    /**
     * First name of registering user.
     * @var string $firstName
     */
    public $firstName;
    /**
     * Last name of registering user.
     * @var string $lastName
     */
    public $lastName;
    /**
     * Password of registering user.
     * @var string $password
     */
    public $password;
    /**
     * Email of registering user.
     * @var string $email
     */
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['firstName', 'lastName', 'password', 'email'], 'required', 'message' => 'Заполните поле'],
            ['email', 'email']
        ];
    }

    /**
     * @return array names of the fields
     */
    public function attributeLabels() {
        return [
            'firstName' => 'Имя',
            'lastName' => 'Фамилия',
            'password' => 'Пароль',
            'email' => 'Email',
        ];
    }

    /**
     * Registration process function
     * @return User|null if user is saved return User otherwise return null
     */
    public function reg(){
        $user = new User();
        $user->firstName = $this->firstName;
        $user->lastName = $this->lastName;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateToken();
        if ($user->save()){
            return $user;
        }else{
            return null;
        }
    }

}