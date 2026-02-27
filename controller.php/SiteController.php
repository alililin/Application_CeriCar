<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Internaute;
use app\models\Voyage;
use app\models\Reservation;
use app\models\Trajet;
use app\models\SignupForm;
use app\models\Typevehicule;
use app\models\Marquevehicule;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    
   public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

   public function actionHello($message = "hello World")
   {
         $model = new \app\models\Hello(); // on crée une instance du modèle
         $options = \yii\helpers\ArrayHelper::map($model->produits, 'id', 'produit'); //on change le tableau en tableau simple avec ARrayHelper
         return $this->render('mapage', [ // on charge mapage
        'message' => $message,
        'options' => $options, // on envoie le tableau à la vue
    ]); 
   }
// declaration de la fonction afin que le  controller recuper les utilsateur et les afficher//
   public function actionMyuser() {
	$user = MyUsers::findOne(1);
	return $this->render('myuser', [ 'model'=>$user,
	]);
   }

/* Etape 2 de projet ceriCar AMS WEB S5
public function actionTest()
{
    //on récupèrent les valeurs envoyées dans l’URL
    $pseudo = Yii::$app->request->get('pseudo');
    $depart = Yii::$app->request->get('depart');
    $arrivee = Yii::$app->request->get('arrivee');
    $idTrajet = Yii::$app->request->get('id_trajet');
    $idVoyage = Yii::$app->request->get('id_voyage');
    
    // pas d'erreur
    $user = null;
    $trajet = null;
    $voyagesTrajet = null;
    $reservationsVoyage = null;
    $voyagesProposes = null;
    $reservationsUser = null;

    // trouver un internaute par pseudo  elle retourne un objet Internaute
    if ($pseudo) {
        $user = Internaute::getUserByIdentifiant($pseudo);
    }

    //trouver un  trajet par (depart, arrivee) 
    if ($depart && $arrivee) {
        $trajet = Trajet::getTrajetInfos($depart, $arrivee);
    }

    //trouver des voyages d'un trajet Pn récupère tous les voyages qui utilisent ce trajet
    if ($idTrajet) {
        $voyagesTrajet = Voyage::getVoyagesByTrajetId($idTrajet);
    }

    // on récupère toutes les réservations liées
    if ($idVoyage) {
        $reservationsVoyage = Reservation::getReservationsByVoyageId($idVoyage);
    }

    return $this->render('test', [
        'pseudo' => $pseudo,
        //$user contient un objet Internaute trouvé par getUserByIdentifiant
        'user' => $user,
        //Permet de réafficher les valeurs dans les inputs les val entrzes dans le formulaire
        'depart' => $depart,
        'arrivee' => $arrivee,
        'trajet' => $trajet,
        //soit l’objet Trajet trouvé par Trajet::getTrajetInfos() soint null
        'idTrajet' => $idTrajet,
        'voyagesTrajet' => $voyagesTrajet,
        'idVoyage' => $idVoyage,
        'reservationsVoyage' => $reservationsVoyage,
        'voyagesProposes' => $voyagesProposes,
        'reservationsUser' => $reservationsUser,
    ]);
}
    */
   
       
/* Etape 3 et 4
    public function actionSearch()
    {      // On demande à Yii de nous donner les informations présentes dans l'URL (méthode GET) index.php?r=site/search 
            $request = Yii::$app->request;
            $villeDepart = $request->get('ville_depart');
            $villeArrivee = $request->get('ville_arrivee');
            $nbPersonnes = $request->get('nb_personnes', 1);
            $voyages = [];
            $trajetTrouve = null;
            $resultats = [];
            $message = "";
            $statusClass = "alert-info";

            if ($villeDepart && $villeArrivee) {
                $trajetTrouve = Trajet::getTrajetInfos($villeDepart, $villeArrivee);
                
                    
                if (!$trajetTrouve) {
                    $message = "ZUT,  Ce trajet n'existe pas";
                    $statusClass = "alert-danger";
                }else {
                    $voyages = Voyage::getVoyagesByTrajetId($trajetTrouve->id);
                    
                    
                    if (empty($voyages)) {
                        $message = "Le trajet existe mais aucun conducteur ne propose de voyage.";
                        $statusClass = "alert-warning";
                    } else {
                    
                        foreach ($voyages as $voyage) {
                            
                            if ($voyage->nbplacedispo < $nbPersonnes) 
                            // $message = "ZUT ! Aucun voyage proposer pour cette recherche.";
                            // $statusClass = "alert-danger";
                                continue;  // On n’affiche pas les véhicules qui la capacité < nombre de voyageur
                            
                            // 1. Calcul des places disponibles
                            $reservations = Reservation::getReservationsByVoyageId($voyage->id);

                            $placesPrises = 0;

                            foreach ($reservations as $reservation) {
                                $placesPrises += $reservation->nbplaceresa;
                            }
                            $placesDispo = $voyage->nbplacedispo - $placesPrises;
                            
                            // COMPLET si places disponible < nb demandées
                            $estComplet = ($placesDispo < $nbPersonnes);
                            //sert rien
                        
                            // Calcul du coût total
                            $coutTotal = $voyage->tarif * $trajetTrouve->distance * $nbPersonnes;

                            $heureDepart = $voyage->heuredepart;
                            $heureDepart .= 'h00';
                            
                        
                    //style visuel 
                    if ($estComplet) {
                        $borderClass = "border-danger";
                    } else {
                        $borderClass = "border-success";
                    }
                        

                            //un tableau propre
                            $resultats[] = [
                                'voyageObject' => $voyage, 
                                'placesDisponibles' => $placesDispo,
                                'estComplet' => $estComplet,
                                'coutTotal' => $coutTotal,
                                'heureDepart' => $heureDepart,
                                'borderClass' => $borderClass,
                                'distance' => $trajetTrouve->distance 
                            ];
                        }
                    $nbVoyagesPertinents = count($resultats);

                    if ($nbVoyagesPertinents == 0) {
                        // Cas où il y avait des voyages en base, mais aucun avec assez de capacité max
                        $message = "Oups ! Aucun véhicule ne peut accueillir $nbPersonnes personnes pour ce trajet.";
                        $statusClass = "alert-warning";
                    } else {
                        $message = "Succès ! $nbVoyagesPertinents voyage trouvés pour ce trajet.";
                        $statusClass = "alert-success";
                    }
                    }
                }
                
            }else{
                if ($request->isAjax){

               
                    $message = "Veuillez remplir les villes de départ et d'arrivée.";
                    $statusClass = "alert-warning";
            }
        }
            
                if (Yii::$app->request->isAjax) {
                   
                     Yii::$app->response->format = Response::FORMAT_JSON;
                
                    return [
                    
                    'html' => $this->renderPartial('result_search', [
                        'resultats' => $resultats, 
                        'trajetTrouve' => $trajetTrouve,
                        'nbPersonnes' => $nbPersonnes
                    ]),
                    'message' => $message,
                    'statusClass' => $statusClass
                ];
        
        }
             return $this->render('search', [
                'villeDepart' => $villeDepart,
                'villeArrivee' => $villeArrivee,
                'nbPersonnes' => $nbPersonnes,
                'resultats' => $resultats,
                'trajetTrouve' => $trajetTrouve
            ]);
    }*/


    
    //etape 5
   //ActionSearch avec correspandance
  public function actionSearch()
    {
        $request = Yii::$app->request;
        
        $villeDepart = $request->get('ville_depart');
        $villeArrivee = $request->get('ville_arrivee');
        $nbPersonnes = $request->get('nb_personnes', 1);
        $accepteCorrespondance = $request->get('correspondance', false); 

        $resultats = [];
        $message = "";
        $statusClass = "alert-info";

        if ($villeDepart && $villeArrivee) {
            //La rechecher Directe
            $trajetDirect = Trajet::getTrajetInfos($villeDepart, $villeArrivee);

            if ($trajetDirect) {
                $voyages = Voyage::getVoyagesByTrajetId($trajetDirect->id);

                foreach ($voyages as $voyage) {
                    
                    
                    // Si la voiture a été créée avec 3 places et qu'on en cherche 4 :
                    // On saute ce voyage, il ne s'affichera pas.
                    if ($voyage->nbplacedispo < $nbPersonnes) {
                        continue; 
                    }

                    // on vérifier les dispo réel
                    // La voiture est assez grande, mais est-ce qu'elle est pleine ?
                    $placesRestantes = $voyage->getPlacesRestantes();
                    
                    // Si places restantes < demande => On affiche "COMPLET"
                    $estComplet = ($placesRestantes < $nbPersonnes);

                    $horaires = Voyage::calculerHoraires($voyage->heuredepart, $trajetDirect->distance);

                    $resultats[] = [
                        'type' => 'direct',
                        'voyageObject' => $voyage, 
                        'ids_reservation' => $voyage->id, 
                        'places' => $placesRestantes,
                        'estComplet' => $estComplet, 
                        'coutTotal' => $voyage->tarif * $trajetDirect->distance * $nbPersonnes,
                        'heureDepart' => $horaires['depart_formate'],
                        'heureArrivee' => $horaires['arrivee_formate'],
                        'distanceTotal' => $trajetDirect->distance,
                        'detailsTrajet' => [
                            [
                                'depart' => $trajetDirect->depart,
                                'arrivee' => $trajetDirect->arrivee,
                                'infos' => 'Trajet Direct'
                            ]
                        ],
                        // Gris si complet, Vert si disponible
                        'borderClass' => $estComplet ? "border-secondary opacity-75" : "border-success"
                    ];
                }
            }

          //Recherche avec correspandance
            if ($accepteCorrespondance) {
                $trajetsDepart = Trajet::getTrajetsAuDepart($villeDepart);

                foreach ($trajetsDepart as $t1) {
                    $villePivot = $t1->arrivee;
                    if ($villePivot == $villeArrivee) continue;

                    $t2 = Trajet::getTrajetInfos($villePivot, $villeArrivee);

                    if ($t2) {
                        $voyagesV1 = Voyage::getVoyagesByTrajetId($t1->id);
                        $voyagesV2 = Voyage::getVoyagesByTrajetId($t2->id);

                        foreach ($voyagesV1 as $v1) {
                            //  Capacité physique V1
                            if ($v1->nbplacedispo < $nbPersonnes) continue;

                            $horairesV1 = Voyage::calculerHoraires($v1->heuredepart, $t1->distance);

                            foreach ($voyagesV2 as $v2) {
                                //Capacité physique V2
                                if ($v2->nbplacedispo < $nbPersonnes) continue;

                                // Vérification Horaire (Arrivée V1 < Départ V2)
                                if ($v2->heuredepart > $horairesV1['arrivee_decimal']) {
                                    
                                    $placesV1 = $v1->getPlacesRestantes();
                                    $placesV2 = $v2->getPlacesRestantes();
                                    
                                    // REGLE 2 : Disponibilité Réelle
                                    // Si l'un des deux est complet, tout le voyage est marqué complet
                                    $placesDispoGlobal = min($placesV1, $placesV2);
                                    $estComplet = ($placesV1 < $nbPersonnes || $placesV2 < $nbPersonnes);

                                    $horairesV2 = Voyage::calculerHoraires($v2->heuredepart, $t2->distance);
                                    
                                    $resultats[] = [
                                        'type' => 'escale',
                                        'voyageObject' => $v1, // On stocke le 1er pour info conducteur
                                        'ids_reservation' => $v1->id . ',' . $v2->id,
                                        'places' => $placesDispoGlobal,
                                        'estComplet' => $estComplet,
                                        'coutTotal' => ($v1->tarif * $t1->distance * $nbPersonnes) + ($v2->tarif * $t2->distance * $nbPersonnes),
                                        'heureDepart' => $horairesV1['depart_formate'],
                                        'heureArrivee' => $horairesV2['arrivee_formate'],
                                        'distanceTotal' => $t1->distance + $t2->distance,
                                        'detailsTrajet' => [
                                            [
                                                'depart' => $t1->depart,
                                                'arrivee' => $t1->arrivee,
                                                'infos' => '1er Trajet'
                                            ],
                                            [
                                                'depart' => $t2->depart,
                                                'arrivee' => $t2->arrivee,
                                                'infos' => 'Correspondance'
                                            ]
                                        ],
                                        'borderClass' => $estComplet ? "border-secondary opacity-75" : "border-warning"
                                    ];
                                }
                            }
                        }
                    }
                }
            } 

            // Messages et Stats
            if (empty($resultats)) {
                $message = "Aucun voyage trouvé (capacité insuffisante).";
                $statusClass = "alert-warning";
            } else {
                $count = count($resultats);
                $message = "$count voyage(s) trouvé(s).";
                $statusClass = "alert-success";
            }

        } elseif ($request->isAjax && $villeDepart !== null) {
            $message = "Veuillez remplir les villes.";
            $statusClass = "alert-warning";
        }

        // RETOUR AJAX
        if ($request->isAjax) {
             Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($villeDepart !== null) {
                return [
                    'html' => $this->renderPartial('result_search', [
                        'resultats' => $resultats, 
                        'nbPersonnes' => $nbPersonnes
                    ]),
                    'message' => $message,
                    'statusClass' => $statusClass
                ];
            } else { 
                return [
                    'content' => $this->renderPartial('search', [
                        'villeDepart' => $villeDepart, 'villeArrivee' => $villeArrivee, 'nbPersonnes' => $nbPersonnes,
                        'resultats' => $resultats
                    ]),
                ];
            }
        }

        return $this->render('search', [
            'villeDepart' => $villeDepart, 'villeArrivee' => $villeArrivee, 'nbPersonnes' => $nbPersonnes,
            'resultats' => $resultats
        ]);
    }
      
   
    public function actionSignup()
    { 
       
       //On crée une instance du modèle qui va gérer les règles de validation (pseudo unique, mot de passe complexe, etc.).
        $model = new SignupForm();
        // SOUMISSION
        if ($model->load(Yii::$app->request->post())) {//on rempli avec les donnes 
            //du json
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Validation : Si le formulaire est incomplet, $model->signup() renverra null/false
            
            if ($user = $model->signup()) {
                if (Yii::$app->user->login($user)) {
                   return [
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès !',
                   
                    ];
                    
                }
            }
            // Si erreur de validation (champ vide côté serveur ou pseudo pris)
            // On récupère la première erreur pour l'afficher
            $errors = $model->getFirstErrors();
            $msg = !empty($errors) ? reset($errors) : 'Erreur lors de l\'inscription.';
            
            return ['success' => false, 'message' => $msg];
            
        }

        // AFFICHAGE (GET)
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
             
                'content' =>  $this->renderPartial('signup', ['model' => $model])
           
            ];
        }
        
            return $this->render('signup', ['model' => $model]);
    
    }
            


    /*login avant correpsondance
    public function actionLogin()
    {
        $model = new LoginForm();

        // SOUMISSION DU FORMULAIRE 
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->login()) {
               
                $response = [ 
                    'success' => true,
                    'message' => 'Connexion réussie !',
                    'navbar' => $this->renderPartial('//layouts/_navbar')
                ];

                // gestion des voyage
                // On récupère l'ID du voyage
                $voyageId = Yii::$app->session->get('voyage_en_attente');
                
                
                // On récupère AUSSI le nombre de places (par défaut 1 si introuvable)
                $nbPlaces = Yii::$app->session->get('nb_places_en_attente', 1);

                if ($voyageId) {
                    // On ne supprime pas la session tout de suite ($session->remove)
                    // On attend que l'utilisateur confirme vraiment la réservation dans actionReserver.
                    
                    // On récupère le voyage
                    $voyage = Voyage::findOne($voyageId);

                    // On vérifie s'il est valide et s'il reste ASSEZ de places
                    if ($voyage && $voyage->getPlacesRestantes() >= $nbPlaces) {
                        
                        //  CALCUL DU PRIX 
                        // Indispensable pour l'affichage
                        $prixTotal = $voyage->tarif * $nbPlaces;

                        // On renvoie le HTML avec TOUTES les variables
                        $response['reservationHtml'] = $this->renderPartial('reservation_result', [
                           'voyage' => $voyage,
                            'user' => Yii::$app->user->identity,
                          'nbPlaces' => $nbPlaces,   
                           'prixTotal' => $prixTotal  
                        ]);
                    }
                }
               
               return $response;
               //return [
                  //  'reservationHtml' => $this->renderPartial('reservation_result', [
                    //    'voyage' => $voyage,
                    //    'user' => Yii::$app->user->identity,
                    //    'nbPlaces' => $nbPlaces,   
                    //    'prixTotal' => $prixTotal  
                   // ]),
                    
              //  ];
            } else {
                return ['success' => false, 'message' => 'Pseudo ou mot de passe incorrect.'];
            }
        }

        // 2. Affichage du formulaire (GET AJAX)
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('login', ['model' => $model]);
        }

        return $this->render('login', ['model' => $model]);
    }*/
   
       public function actionLogin()
    {
        $model = new LoginForm();
        $request = Yii::$app->request;
        
        
        // SOUMISSION DU FORMULAIRE (POST)
       
        if ($model->load($request->post())) {//Cette partie récupère l'intégralité des données envoyées
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->login()) {

                // Gestion de Voyage
                $idsString = Yii::$app->session->get('voyage_en_attente');
                $nbPlaces = Yii::$app->session->get('nb_places_en_attente', 1);

                if (!empty($idsString)) {
                    
                    // On vérifie quand même si les places sont toujours dispos avant de rediriger
                    $listeIds = explode(',', $idsString);
                    $toutEstOk = true;

                    foreach ($listeIds as $idVoyage) {
                        $voyage = Voyage::findOne($idVoyage);
                        if ($voyage) {
                            if ($voyage->getPlacesRestantes() < $nbPlaces) {
                                $toutEstOk = false;
                            }
                        } else {
                            $toutEstOk = false;
                        }
                    }

                    // Si tout est bon, on redirige vers l'action "reserver"
                    // C'est elle qui affichera la popup "reservation_result" après le rechargement
                    if ($toutEstOk) {
                        return [
                            'success' => true,
                            'message' => 'Connexion réussie ! Redirection vers la réservation...',
                            'redirect' => Url::to(['site/reserver']) 
                        ];
                    }
                }

                // CONNEXION CLASSIQUE (Si pas de réservation en attente) 
                return [ 
                    'success' => true,
                    'message' => 'Connexion réussie !',
                    'redirect' => Url::to(['site/compte'])
                ];

            } else {
                return [
                    'success' => false, 
                    'message' => 'Pseudo ou mot de passe incorrect.'
                ];
            }
        }

        
        // AFFICHAGE DU FORMULAIRE (GET)
        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'content' => $this->renderPartial('login', ['model' => $model])
            ];
        }

        // Accès direct 
        return $this->render('login', ['model' => $model]);
    }
    

    
        /*action resever avant correspondance*/
        /*
        public function actionReserver($id)
    {
        // RÉCUPÉRATION DU NOMBRE DE PLACES
        $nbPlaces = Yii::$app->request->get('nb_places');

        //  Si pas dans l'URL, on regarde en Session (cas retour après Login)
        if (empty($nbPlaces)) {
            $nbPlaces = Yii::$app->session->get('nb_places_en_attente', 1);
        }

        // non Connécté
        if (Yii::$app->user->isGuest) {
            // On mémorise l'ID ET le nombre de places
            Yii::$app->session->set('voyage_en_attente', $id);
            Yii::$app->session->set('nb_places_en_attente', $nbPlaces); // <--- AJOUT CRUCIAL
            
            $model = new LoginForm();
            return $this->renderAjax('login', ['model' => $model]);
        }

        // SI CONNECTÉ : On récupère le voyage
        $voyage = Voyage::findOne($id);

        if (!$voyage) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => false, 'message' => 'Voyage introuvable'];
        }

        // Vérification des places disponibles
        if ($voyage->getPlacesRestantes() < $nbPlaces) { // On vérifie pour le nombre demandé
             Yii::$app->response->format = Response::FORMAT_JSON;
             return ['success' => false, 'message' => 'Pas assez de places disponibles.'];
        }

        // CALCUL DU PRIX TOTAL 
        $prixTotal = $voyage->tarif * $nbPlaces * $voyage->trajetInfos->distance;

        //  TRAITEMENT DE LA VALIDATION (POST)
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Vérif doublon
            $reservations = Reservation::getReservationsByVoyageId($id);
            foreach ($reservations as $r) {
                if ($r->voyageur == Yii::$app->user->id) 
                    return ['success'=>false, 'message'=>'Vous avez déjà réservé ce trajet.'];
            }

            // Enregistrement
            $reservation = new \app\models\Reservation();
            $reservation->voyage = $id;
            $reservation->voyageur = Yii::$app->user->id;
            
            // On enregistre le nombre de places correct
            $reservation->nbplaceresa = $nbPlaces; 

            if ($reservation->save()) {
                // On nettoie la session après succès
                Yii::$app->session->remove('voyage_en_attente');
                Yii::$app->session->remove('nb_places_en_attente');

                return [
                    'success' => true, 
                    'message' => 'Réservation validée pour ' . $nbPlaces . ' personne(s) !',
                    
                ];
            } else {
                 return ['success' => false, 'message' => 'Erreur technique.'];
            }
        }

        // AFFICHAGE DE LA CONFIRMATION (GET)
        if (Yii::$app->request->isAjax) {
        return $this->renderPartial('reservation_result', [
            'voyage' => $voyage,
            'user' => Yii::$app->user->identity,
            'nbPlaces' => $nbPlaces,   // On passe le nombre
            'prixTotal' => $prixTotal  // On passe le prix déjà calculé
        ]);
    }
    }*/


      public function actionReserver()
{
    $request = Yii::$app->request;
    
    // RÉCUPÉRATION DES INFORMATIONS
    //  avoir quel voyage l'utilisateur veut et combien de places.
    $idsString = $request->get('ids'); 
    $nbPlaces = $request->get('nb_places');
    //// Si on ne les trouve pas dans l'URL, on regarde dans la Session (utile après une reconnexion).
    if (empty($idsString)) $idsString = Yii::$app->session->get('voyage_en_attente');
    if (empty($nbPlaces)) $nbPlaces = Yii::$app->session->get('nb_places_en_attente', 1);

    // 2. GESTION DES INVITÉS (SÉCURITÉ)
    if (Yii::$app->user->isGuest) {
        // On sauvegarde l'intention dans la session avant de l'envoyer au login
        Yii::$app->session->set('voyage_en_attente', $idsString);
        Yii::$app->session->set('nb_places_en_attente', $nbPlaces);
        
        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        
    }

    $listeIds = explode(',', $idsString);//un tableu des id
    $tousLesVoyages = []; 
    $prixTotal = 0;

    foreach ($listeIds as $idVoyage) {
        $voyage = Voyage::findOne($idVoyage);
        if (!$voyage) continue;

        // Vérification de la disponibilité 
        if ($voyage->getPlacesRestantes() < $nbPlaces) {
            $msg = "Désolé, il n'y a plus assez de places vers " . $voyage->trajetInfos->villearrivee;
            if ($request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => false, 'message' => $msg];
            }
          
        }

        $tousLesVoyages[] = $voyage; 
        $prixTotal += ($voyage->tarif * $nbPlaces * $voyage->trajetInfos->distance);
    }

    // SAUVEGARDE FINALE 
    if ($request->isPost) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        foreach ($listeIds as $idVoyage) {
            // Vérification anti-doublon (si déjà réservé par cet utilisateur)
            $existe = Reservation::find()
                ->where(['voyage' => $idVoyage, 'voyageur' => Yii::$app->user->id])
                ->exists();

            if ($existe) {
                return ['success' => false, 'message' => "Vous avez déjà une réservation pour l'un de ces trajets."];
            }

            $reservation = new Reservation();
            $reservation->voyage = $idVoyage;
            $reservation->voyageur = Yii::$app->user->id;
            $reservation->nbplaceresa = $nbPlaces;

            if (!$reservation->save()) {
                return ['success' => false, 'message' => "Erreur lors de l'enregistrement en base de données."];
            }
        }

        // Nettoyage de la session après succès
        Yii::$app->session->remove('voyage_en_attente');
        Yii::$app->session->remove('nb_places_en_attente');

        return [
            'success' => true, 
            'message' => 'Réservation validée ! Bon voyage.',
            
        ];
    }

    // 5. AFFICHAGE DU RÉCAPITULATIF (GET)
    $params = [
        'voyages' => $tousLesVoyages, 
        'idsString' => $idsString,
        'nbPlaces' => $nbPlaces,
        'prixTotal' => $prixTotal,
        'user' => Yii::$app->user->identity
    ];

    if ($request->isAjax) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'content' => $this->renderPartial('reservation_result', $params)
        ];
    }

    
    return $this->render('reservation_result', $params);
}

    public function actionBillets()
    {
        $utilisateur = Yii::$app->user->identity;
        $reservations = $utilisateur->reservations;

        // 1. REQUÊTE AJAX (Navigation SPA)
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'content' => $this->renderPartial('billets', [
                    'reservations' => $reservations
                ])
            ];
        }

        //  ACCÈS DIRECT (Via URL navigateur)
        return $this->render('billets', [
            'reservations' => $reservations
        ]);
    }

    public function actionProposer()
{
    // On prépare la réponse JSON pour AJAX dès le début
    if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    //  VÉRIFICATION STRICTE DES DROITS (Avant d'afficher quoi que ce soit)
    // Un conducteur DOIT être connecté ET avoir un numéro de permis
    if (Yii::$app->user->isGuest || empty(Yii::$app->user->identity->permis)) {
        
        $msg = "Accès refusé : Vous devez être connecté et posséder un permis pour proposer un voyage.";

        if (Yii::$app->request->isAjax) {
            // Si c'est un invité, on renvoie la vue login pour la SPA
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->set('redirect_after_login', 'site/proposer');
                return [
                    'content' => $this->renderPartial('login', ['model' => new LoginForm()]),
                    'message' => "Veuillez vous connecter pour continuer.",
                    'success' => false
                ];
            }
            // Si connecté sans permis, on bloque avec le message d'erreur
            return [
                'success' => false,
                'message' => $msg
            ];
        }
        
    }

    // 2. INITIALISATION DU MODÈLE ET DES DONNÉES
    $model = new Voyage();
    $user = Yii::$app->user->identity;
    $villeDepart = ''; 
    $villeArrivee = '';

    // 3. TRAITEMENT DE LA SOUMISSION (POST)
    if (Yii::$app->request->isPost) {
        $post = Yii::$app->request->post();
        
        // Chargement des données 
        if ($model->load($post)) {
            
            // Vérification que le trajet est pré-défini dans l'application
            $trajet = \app\models\Trajet::getTrajetInfos(
                $post['ville_depart'] ?? '', 
                $post['ville_arrivee'] ?? ''
            );

            if ($trajet) {
                $model->trajet = $trajet->id; // Liaison avec le trajet existant
                $model->conducteur = $user->id; // Liaison avec l'internaute

                if ($model->save()) {
                    return [
                        'success' => true,
                        'message' => 'Votre voyage a été publié avec succès !',
                        
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => "Erreur : Le trajet sélectionné n'existe pas ou les données sont invalides."
            ];
        }
    }

    // RENDU DU FORMULAIRE (GET)
    // Récupération des listes fermées pour les véhicules
    $viewParams = [
        'model' => $model,
        'villeDepart' => $villeDepart, 
        'villeArrivee' => $villeArrivee,
        'typesVehicules' => \yii\helpers\ArrayHelper::map(\app\models\Typevehicule::find()->all(), 'id', 'typev'),
        'marquesVehicules' => \yii\helpers\ArrayHelper::map(\app\models\Marquevehicule::find()->all(), 'id', 'marquev'),
    ];

    if (Yii::$app->request->isAjax) {
        return ['content' => $this->renderPartial('proposer', $viewParams)];
    }

    return $this->render('proposer', $viewParams);
}

   public function actionMesoffres()
    {
        
        // sécurité Vérifier si l'utilisateur est connecté
        if (Yii::$app->user->isGuest) {
            
            // Si c'est un appel Ajax (via le menu), on renvoie le formulaire de login
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
               return [
                    'content' => $this->renderPartial('login', [
                        'model' => new LoginForm()
                    ])
                ];
            }
        }
        $userId = Yii::$app->user->id;
        // On cherche tous les voyages où 'conducteur' est l'utilisateur connecté
        $mesVoyages = Voyage::getVoyagesByConducteur($userId);

        // Paramètres à envoyer à la vue
        $viewParams = [
            'voyages' => $mesVoyages
        ];
        
        // Si la demande du formulaire 
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'content' => $this->renderPartial('mesoffres', $viewParams)
            ];
        }

        // Si l'utilisateur a tapé l'URL directement dans le navigateur
        return $this->render('mesoffres', $viewParams);
    }
}