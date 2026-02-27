<?php
namespace app\models;

use yii\db\ActiveRecord;

class Marquevehicule extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.marquevehicule';
    }

    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idmarquev' => 'id']);
    }
}
