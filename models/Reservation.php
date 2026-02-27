<?php

namespace app\models;

use yii\db\ActiveRecord;

class Reservation extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    public function getVoyageInfos() //récupere l'objet voyage avec id 
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    public function getVoyageurInfos() // on recupere l'objet voyageur avec id qui correspond a linstance de classe internaute
    {
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }

       public static function getReservationsByVoyageId($id) //récupére les reservation correspond a id voyage
    {
        
        return self::findALL(['voyage' => $id]);
    } 
    public function getPrixTotalCalcul()
{
    // On vérifie que la relation voyage existe pour éviter un crash
    if ($this->voyageInfos) {
       
        return $this->nbplaceresa * $this->voyageInfos->tarif;
    }
    return 0;
}
}
