<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Internaute;
use app\models\Trajet;
use app\models\Typevehicule;
use app\models\Marquevehicule;
use app\models\Reservation;

class Voyage extends ActiveRecord    
{
  

    public static function tableName()
    {
        return 'fredouil.voyage';
    }
    public function rules()
    {
        return [
            // 1. Ces champs sont OBLIGATOIRES (sinon la BDD plante)
            [['conducteur', 'trajet', 'nbplacedispo', 'tarif', 'heuredepart', 'idtypev', 'idmarquev'], 'required'],

            // 2. On vérifie que ce sont bien des nombres entiers
            [['nbplacedispo', 'nbbagage', 'heuredepart', 'idtypev', 'idmarquev', 'trajet', 'conducteur'], 'integer'],

            // 3. Le tarif est un nombre à virgule (ex: 10.50)
            [['tarif'], 'number'],

            // 4. Les contraintes sont du texte
            [['contraintes'], 'string'],
        ];
    }
    public function getConducteurInfos() //récupére l'objet conducteur depuis la table  interaute
    {
        return $this->hasOne(Internaute::class, ['id' => 'conducteur']);
    }

    public function getTrajetInfos() // récupere l'objet trajet depuis la table trajet
    {
        return $this->hasOne(Trajet::class, ['id' => 'trajet']);
    }

    public function getTypeVehicule() //récupere le type de véhicule depuis la table typevehicule
    {
        return $this->hasOne(Typevehicule::class, ['id' => 'idtypev']);
    }

    public function getMarqueVehicule() //récupere l'objet marque de ue voiture depuis la table
    {
        return $this->hasOne(Marquevehicule::class, ['id' => 'idmarquev']);
    }

    public function getReservations() //récupere l'objet reservation depuis la table res
    {
        return $this->hasMany(Reservation::class, ['voyage' => 'id']);
    }

   public static function getVoyagesByTrajetId($trajetId) {
     
        return self::findAll(['trajet' => $trajetId]);
     }
  //methode
  /**
     * Calcule les places restantes sans modifier la capacité max du voyage
     */
    public function getPlacesRestantes()
    {
      
        // On fait la SOMME de la colonne 'nbplaceres' (car une réservation peut valoir plusieurs places).

        $totalReserve = $this->getReservations()->sum('nbplaceresa');

        // Si aucune réservation n'existe, le résultat est null, on le force à 0
        if (empty($totalReserve)) {
            $totalReserve = 0;
        }

        // 2. On fait le calcul dynamique :
        // Capacité Max (nbplacedispo) - Total déjà réservé
        // ATTENTION : J'utilise ici nbplacedispo comme tu me l'as indiqué
        return $this->nbplacedispo - $totalReserve;
    }

    public static function calculerHoraires($heureDepartDecimal, $distanceKm) 
    {
        // Règle : Durée en minutes = Distance en km
        $dureeHeures = $distanceKm / 60;
        $heureArriveeDecimal = $heureDepartDecimal + $dureeHeures;

        return [
            'arrivee_decimal' => $heureArriveeDecimal, 
            
            // Pour l'affichage (texte)
            'depart_formate' => sprintf("%02dh%02d", floor($heureDepartDecimal), round(($heureDepartDecimal - floor($heureDepartDecimal)) * 60)),
            
            'arrivee_formate' => sprintf("%02dh%02d", floor($heureArriveeDecimal) % 24, round(($heureArriveeDecimal - floor($heureArriveeDecimal)) * 60))
        ];
    }
    /**
     * Récupère tous les voyages proposés par un conducteur précis
     * avec les infos du trajet et des passagers pré-chargées.
     */
    public static function getVoyagesByConducteur($idConducteur)
    {
        return self::find()
            ->where(['conducteur' => $idConducteur])
            // On garde le 'with' ici pour que la vue ne rame pas
            ->with(['trajetInfos', 'reservations.voyageurInfos']) 
            ->orderBy(['heuredepart' => SORT_ASC])
            ->all();
    }
}