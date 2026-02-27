<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Voyage;


 //Classe Trajet
 // Représente un trajet (départ, arrivée, distance)
 // Correspond à la table : fredouil.trajet
class Trajet extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    public function getVoyagesInfos()
    {
        return $this->hasMany(Voyage::class, ['trajet' => 'id']);
    }

     public static function getTrajetInfos($depart, $arrivee)
    {
        return self::findOne(['depart'  => trim($depart),'arrivee' => trim($arrivee),]);
    }
    public static function getTrajetsAuDepart($depart)
    {
        return self::findAll(['depart' => trim($depart)]);
    }
}
