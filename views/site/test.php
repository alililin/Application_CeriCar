<?php
/**
 * VARIABLES REÇUES DU CONTROLEUR :
 * $pseudo, $user
 * $depart, $arrivee, $trajet
 * $idTrajet, $voyagesTrajet
 * $idVoyage, $reservationsVoyage
 * $voyagesProposes, $reservationsUser
 */
?>

<h1>CERICar</h1>

<!--  cherhcer un internaute -->

<h2>Chercher un internaute par pseudo</h2>

<form method="GET" action="index.php">
    <input type="hidden" name="r" value="site/test">

    <label>Pseudo : </label>
    <input type="text" name="pseudo" value="<?= $pseudo ?? '' ?>">
    <button type="submit">Tester</button>
</form>

<hr>

<?php if ($pseudo): ?>
    <h3>Résultat :</h3>

    <?php if ($user): ?>
        
           
            <li><strong>Pseudo : </strong><?= $user->pseudo ?></li>
            <li><strong>Nom : </strong><?= $user->nom ?></li>
            <li><strong>Prénom : </strong><?= $user->prenom ?></li>
            <li><strong>Email : </strong><?= $user->mail ?></li>
            <li><strong> Permis :  </strong> <?= $user->permis ?></li>
        

    
        <h4>Voyages proposés :</h4>

        <?php
        $voyagesProposes = $user->voyagesProposes; 
        if (!empty($voyagesProposes)): ?>
            <?php foreach ($voyagesProposes as $v): ?>
                <div>
                    
                   <strong> Trajet  </strong>: <?= $v->trajetInfos->depart ?> -- <?= $v->trajetInfos->arrivee ?><br>
                    <strong> Distance :  </strong> <?= $v->trajetInfos->distance ?> km<br>
                    <strong> Places dispo :  </strong><?= $v->nbplacedispo ?><br>
                    <strong> Heure de départ : </strong> <?= $v->heuredepart ?>h<br>
                    <strong> Tarif : </strong> <?= $v->tarif ?> €/km/personne<br>
                    <strong> Nombre de bagages : </strong><?= $v->nbbagage ?><br>
                    <strong> Nom du conducteur : </strong> <?= $v->conducteurInfos->nom ?><br>
                    <strong> Type de véhicule : </strong> <?= $v->typeVehicule->typev ?>
                    
                    
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun voyage proposé</p>
        <?php endif; ?>

    
<br>
        
     <!--  les réservation d'un internaute -->
        <h4><strong>Réservations :</strong></h4>
        <?php 
        $reservationsUser = $user->reservations;
        if (!empty($reservationsUser)): ?>
            <?php foreach ($reservationsUser as $r): ?>
                <div>
                <strong> place réserver: </strong> <?= $r->nbplaceresa ?><br>
                <h5> Voyage </h5>
                <strong> Trajet :  </strong> <?= $r->voyageInfos->trajetInfos->depart ?> -- <?= $r->voyageInfos->trajetInfos->arrivee ?><br>
                <strong> tarif : </strong> <?= $r->voyageInfos->tarif ?>€/km/personne<br>
                <strong> Distance : </strong> <?= $r->voyageInfos->trajetInfos->distance ?> km<br>
                <strong >Départ a  </strong>  : <?= $r->voyageInfos->heuredepart ?>h<br>
                <strong> Nombre de bagage :  </strong> <?= $r->voyageInfos->nbbagage ?><br>
                <strong> Contraintes : </strong> <?= $r->voyageInfos->contraintes ?><br>
               <strong> Nom : </strong> <?= $r->voyageurInfos->nom ?><br>
                Marque vehicule : <?= $r->voyageInfos->marqueVehicule->marquev ?><br>
                
            <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p>Aucune  réservation.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Aucun internaute trouvé.</p>
    <?php endif; ?>
<?php endif; ?>



<!--  on cherche un trajet oar ville départ et arrivée -->
<br>
<h2> Trouver un trajet par villes</h2>

<form method="GET" action="index.php">
    <input type="hidden" name="r" value="site/test">

    Départ : <input type="text" name="depart">
    Arrivée : <input type="text" name="arrivee">
    <button type="submit">Tester Trajet</button>
</form>

<hr>

<?php if ($depart && $arrivee): ?>
    <h3>Résultat :</h3>

    <?php if ($trajet): ?>
       <strong>  Depart : </strong> <?= $trajet->depart ?> <br>
        <strong> Arrivee : </strong> <?= $trajet->arrivee ?><br>
       <strong>  Distance : </strong> <?= $trajet->distance ?> km<br>
    <?php else: ?>
        <p>Aucun trajet trouvé.</p>
    <?php endif; ?>
<?php endif; ?>



<!-- trouver les voyages d'un trajet -->

<h2>Trouver les voyages d’un trajet</h2>

<form method="GET" action="index.php">
    <input type="hidden" name="r" value="site/test">

    ID Trajet : <input type="number" name="id_trajet">
    <button type="submit">Tester Voyages Trajet</button>
</form>

<hr>
<?php if ($idTrajet): ?>
    <h3>Résultat :</h3>

    <?php if (!empty($voyagesTrajet)): ?>
        <?php foreach ($voyagesTrajet as $v): ?>
            <div>
            
            <strong> Trajet : </strong> <?= $v->trajetInfos->depart ?> --<?= $v->trajetInfos->arrivee ?><br>
            <strong> Heure de départ : </strong> <?= $v->heuredepart ?><br>
            <strong> Type de véhicule : </strong> <?= $v->typeVehicule->typev ?> <br>
            <strong> Marque de véhecule du voyage : </strong> <?= $v->marqueVehicule->marquev ?> <br>
            <strong> Nombre des places dispo : </strong> <?= $v->nbplacedispo ?><br>
            <strong> Nombre de bagages : </strong> <?= $v->nbbagage ?><br>
            <strong> Contraintes : </strong> <?= $v->contraintes ?><br>
           
            </div>
        <?php endforeach; ?>
    <?php else: ?> 
        <p>Aucun voyage trouvé pour ce trajet.</p>
    <?php endif; ?>
<?php endif; ?>



<!-- trouver des réservation par un id  voyage -->

<h2>Trouver les réservations d’un voyage</h2>

<form method="GET" action="index.php">
    <input type="hidden" name="r" value="site/test">

    ID Voyage : <input type="number" name="id_voyage">
    <button type="submit">Tester Réservations</button>
</form>

<hr>

<?php if ($idVoyage): ?>
    <h3>Résultat :</h3>

    <?php if (!empty($reservationsVoyage)): ?>
        <?php foreach ($reservationsVoyage as $r): ?>
            <div>
                <strong> Places réservées : </strong> <?= $r->nbplaceresa ?><br>
                <strong> Pseudo: </strong> <?= $r->voyageurInfos->pseudo ?><br>
                <strong >Email :  </strong> <?= $r->voyageurInfos->mail ?><br>
                
                <strong> Trajet : </strong> <?= $r->voyageInfos->trajetInfos->depart ?> -- <?= $r->voyageInfos->trajetInfos->arrivee ?></br>
                <strong> Distance de trajet : </strong> <?= $r->voyageInfos->trajetInfos->distance ?>Km <br>
                <strong> Départ a : </strong> <?= $r->voyageInfos->heuredepart ?> H <br>
                <strong> Tarif :  </strong> <?= $r->voyageInfos->tarif ?> €/km/personne<br>
                <strong> Type de véhicule : </strong>  <?= $r->voyageInfos->typeVehicule->typev ?><br>
                
               
               

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style>Aucune réservation pour ce voyage.</p>
    <?php endif; ?>
<?php endif; ?>
  