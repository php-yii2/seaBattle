<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login is the model, connected with the login form.
 */
class Login extends Model
{
    /**
     * Entered password
     * @var string $password
     */
    public $password;
    /**
     * Entered email
     * @var string $email
     */
    public $email;
    /**
     * Found user from db
     * @var mixed $_user type bool=false if user isn't found, type user if user is found
     */
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'email'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @return array names of the fields
     */
    public function attributeLabels() {
        return [
            'email' => 'Почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * Validates the password.
     * This method serves as our validation for password.
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный адрес электронной почты либо пароль.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }

    /**
     * Finds user by email
     * If a user is found this function returns user, otherwise null
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }


}
