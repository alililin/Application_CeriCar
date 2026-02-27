<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Internaute;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read Internaute|null $user 
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            // On vérifie si l'user existe ET si le mot de passe est bon
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Pseudo ou mot de passe incorrect.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            // On connecte l'utilisateur pour 30 jours si "Se souvenir" est coché
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Trouve l'utilisateur par son pseudo via le modèle Internaute
     */
    public function getUser()
    {
        if ($this->_user === false) {
            // On utilise Internaute au lieu de User
            $this->_user = Internaute::findByUsername($this->username);
        }

        return $this->_user;
    }
}