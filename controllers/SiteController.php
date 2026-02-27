<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Internaute;
use app\models\Voyage;
use app\models\Reservation;
use app\models\Trajet;

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
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
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
}

