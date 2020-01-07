<?php

function getTableau($fichier){
	$res = array();
	if(($handle = fopen($fichier, "r")) !== FALSE){
		while (($data = fgetcsv($handle)) !== FALSE){
			array_push($res,$data);
		}
	}
	fclose($handle);
	array_shift($res);	
	return $res;
}

function getTableauAvecPremierLigne($fichier){
	$res = array();
	if(($handle = fopen($fichier, "r")) !== FALSE){
		while (($data = fgetcsv($handle)) !== FALSE){
			array_push($res,$data);
		}
	}
	fclose($handle);
	return $res;
}
function afficherListSpectacle($tab){
	echo "<select id=spectacle onclick=\"checkConflit()\">";
	foreach($tab as $value){
		$date = $value[0];
		$horaire = $value[1];
		$spectacle = $value[2];
		$lieu = $value[3];
		$village = $value[4];
		$compagnie = $value[5];
		$v = "$date,$horaire,$spectacle,$lieu,$village,$compagnie";
		echo "<option value=\"$v\">$date, $horaire, $spectacle, $lieu, $village, $compagnie</option>";
	}
	echo "</select>";

}

function afficherListVille(){
	$tab = getTableauAvecPremierLigne("data/distanceVille.csv");
	for($i=1; $i< count($tab[0]);$i=$i+1){
		$ville = $tab[0][$i];
		echo "<option value=\"$i\">$ville</option>";
	}
}


?>

<script src="data/tarif.js"></script>

<html>
    <head> 
        <link rel="stylesheet" type="text/css" href="styleTheatresDeBourbonPourPHP.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <meta charset="UTF-8">
    
    <head>
    
    <body>
        <div class="bandeau">
            <h1> Festival de Theatre : Tarif </h1>
        </div>
        
        <div class="menu">
            <ul class="nav">
                <li> <a href="index.html">index</a> </li>
                <li><a href="QuiSommeNous.html">Qui sommes nous ?</a></li>
                <li><a href="ParJour.php">Par Jour</a></li>
                <li><a href="ParTroupe.php">Par Troupe</a></li>
                <li><a href="ParLieu.php">Par Lieu</a></li>
                <li> <a href="Finance.php"> Finance </a>  </li>
            </ul>
        </div>
		
		
		<main>
			<div class="decalage">
			<div id="erreur"></div>
			<div class="lieu">
					Vérifier la distance et le temps : <br> 
					<select id="lieu1"> <?php afficherListVille() ?></select>
					<select id="lieu2"> <?php afficherListVille() ?></select> 
					<input type="text" placeholder="Départ, format : 00h00" id="heure">
					<button id="DistTps" onclick="getTempsDistance()">valider</button>
				<div id="textTpsDist"></div>
			</div>
			<div class="lieu">
				<font color="red" id="conflict" hidden>Conflit de Temps</font><br>
				<b>Choisir son spectacle :</b><br>
				<?php
					$tab = getTableau("data/ResultatsFestival.csv");
					afficherListSpectacle($tab);
				?>
				<br>
				 Places Adultes :
				<input type="text" id="adulte" value="0" size="1" readonly/>
				<input type="button" onclick=dncrementValue("adulte")  value="-" />
				<input type="button" onclick=incrementValue("adulte") value="+" /><br>

				Places Enfant :
				<input type="text" id="enfant" value="0" size="1" readonly/>
				<input type="button" onclick=dncrementValue("enfant") value="-" />
				<input type="button" onclick=incrementValue("enfant") value="+" /><br>
				
				Places Tarifs reduit :
				<input type="text" id="tarif_reduit" value="0" size="1" readonly/>
				<input type="button" onclick=dncrementValue("tarif_reduit") value="-" />
				<input type="button" onclick=incrementValue("tarif_reduit") value="+" /><br>
				
				<button onclick="valider()"> Valider </button>
				
				
				<div>
					<h2> Panier </h2> <b>Format :</b><br> infoSpectacle : nbTicketAdulteTotal , nbTicketReduitTotal
					<ul id="Panier">
					</ul><br/>
					Prix Total : <strong id="PrixTotal">0</strong>€<br>
					
					<button onclick="resetPanier()">Reset</button> 
					<button onclick="validerCommande()">Valider Commande</button>
				</div>
				<div id="alert"></div>
			</div>
			</div>
		</main>
        
    
    
    
	
	
	
    </body>





</html>
