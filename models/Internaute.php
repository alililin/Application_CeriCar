<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface; 

class Internaute extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'fredouil.internaute';
    }

 
    public function getReservations() 
    {
        return $this->hasMany(Reservation::class, ['voyageur' => 'id']);
    }

    public function getVoyagesProposes()
    {
        return $this->hasMany(Voyage::class, ['conducteur' => 'id']);
    }

  
    public static function getUserByIdentifiant($pseudo)
    {
        return self::findOne(['pseudo' => $pseudo]);
    }
    
   

    // Trouve un utilisateur par son Pseudo pour le Login.
     
    public static function findByUsername($pseudo)
    {
        return self::getUserByIdentifiant($pseudo);
    }

    /**
     * Trouve une identité via son ID (Utilisé pour maintenir la session ouverte)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    //dans l'interface
     
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Renvoie l'ID de l'utilisateur courant
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Clef d'auth 
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validation clef d'auth 
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    // --- GESTION DU MOT DE PASSE CRYPTÉ ---

    /**
     * je vérifie le mot de passe hashé
     * 
     */
    public function validatePassword($password)
    {
        return $this->pass === sha1($password);
    }
}