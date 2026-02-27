<?php
namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    // Champs existants
    public $nom;
    public $prenom;
    public $pseudo;
    public $email;
    public $password;
    public $permis; 
    public $photo;  
    
    // NOUVEAU CHAMP : Pour choisir le rôle
    public $type_compte; // valeurs: 'voyageur' ou 'conducteur'

    public function rules()
    {
        return [
            // 1. Champs toujours obligatoires (J'ai enlevé 'permis' d'ici)
            [['nom', 'prenom', 'pseudo', 'email', 'password', 'type_compte'], 'required', 'message' => 'Ce champ est requis.'],
            
            // 2. Règle conditionnelle pour le PERMIS
            // Il est requis UNIQUEMENT si type_compte == 'conducteur'
            ['permis', 'required', 'when' => function ($model) {
                return $model->type_compte == 'conducteur';
            }, 'whenClient' => "function (attribute, value) {
                return $('input[name=\"SignupForm[type_compte]\"]:checked').val() == 'conducteur';
            }", 'message' => 'Le permis est obligatoire pour un conducteur.'],

            // Formats
            ['email', 'email'],
            ['permis', 'number', 'message' => 'Le numéro de permis doit être un chiffre.'],
            
            ['photo', 'string'],
            ['photo', 'default', 'value' => 'default.jpg'],

            // Unicité
            ['pseudo', 'unique', 'targetClass' => '\app\models\Internaute', 'message' => 'Ce pseudo est pris.'],
            ['email', 'unique', 'targetClass' => '\app\models\Internaute', 'targetAttribute' => 'mail', 'message' => 'Cet email est utilisé.'],
            
            ['password', 'string', 'min' => 4],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Internaute();
        $user->nom = $this->nom;
        $user->prenom = $this->prenom;
        $user->pseudo = $this->pseudo;
        $user->mail = $this->email;
        $user->photo = $this->photo;
        
        // On ne sauvegarde le permis que si c'est un conducteur
        if ($this->type_compte == 'conducteur') {
            $user->permis = $this->permis;
        } else {
            $user->permis = null; // Pas de permis pour un voyageur
        }
        
        $user->pass = sha1($this->password);
        
        return $user->save() ? $user : null;
    }
}