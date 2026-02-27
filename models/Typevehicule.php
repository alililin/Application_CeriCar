<?php
namespace app\models;

use yii\db\ActiveRecord;
use app\models\Voyage;


class Typevehicule extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.typevehicule';
    }
    
//on récupérer tous les onjets voyages utilisant ce type de véhicule.
    public function getVoyagesInfos()
    {
        return $this->hasMany(Voyage::class, ['idtypev' => 'id']);
    }
   
}
