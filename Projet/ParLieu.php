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

//Donne le lien de l'image selon les lieux
function getImgLieu($lieu){

    if($lieu === "Manoir des noix") return "images/imgLieuVeauceManoirEtEglise.jpg";
    if($lieu === "Eglise") return "images/imgLieuVeauceEglise.jpg";
    if($lieu === "Château de Lachaise") return "images/imgLieuLachaise2.jpg";
    if($lieu === "Château d'Idogne") return "images/imgLieuIdogne2.jpg";
    if($lieu === "Domaine de la Quérye") return "images/imgLieuQuerye1.jpg";
    if($lieu === "Centre National du Costume de Scène") return "images/imgLieuCentre.jpg";

    return "";
}

//affiche les donnés du tableau par ordre chronologique sans le lieu
function afficherParDateSansLieu($tab,$d){
              
              $date = $tab[0];
              if($date !== $d){
                  $a = ucfirst($date);
                  echo "<h3>$a<h3>";
              
              }
              
              $horaire = $tab[1];
              $spectacle = $tab[2];
              $troupe = $tab[5];
              echo "<p>";
              echo "<horaire>à $horaire</horaire>,&nbsp";
              echo "<titrespectacle>$spectacle</titrespectacle>,&nbsp";
              echo "<troupe>par $troupe</troupe>";
              echo "</p>";

}



//affiche les données par lieu
function afficheProgParLieu($tab){
     
     $restant = array();
     
     $Lieu = $tab[0][3];
     $Village = $tab[0][4];
     $date = "";
     
     echo "<div class=Lieu>";
     echo "<h2 id=\"$Lieu\"> $Lieu à $Village </h2>";
     
     $lien = getImgLieu($Lieu);
     echo "<div>";
     echo "<figure class=lieu> <img src=$lien  width=100%></img></figure>";
     echo "</div>";
     
     
     
     echo "<h2> Le Programme :  </h2>";
     
     for($i=0; $i< count($tab); $i= $i+1){
          if($tab[$i][3] == $Lieu){
              afficherParDateSansLieu($tab[$i],$date);
              $date = $tab[$i][0];
          
          }else{
              array_push($restant, $tab[$i]);
          }
     }
     echo "</div>\n\n";
     
     if(count($restant) !== 0){
     
         afficheProgParLieu($restant);
     
     }

}

?>




<hmtl>

     <head>
          <title> Programme par Lieu</title>
          <link rel="stylesheet" type="text/css" href="styleTheatresDeBourbonPourPHP.css">
          <meta charset="UTF-8">
     </head>

     <body>

        <div class="bandeau">
            <h1>Programme Par Lieu</h1>
        </div>

        <div class="menu">
            <ul class="nav">
                <li> <a href="index.html">Index</a> </li>
                <li><a href="QuiSommeNous">Qui sommes nous ?</a></li>
                <li><a href="ParJour.php">Par Jour</a></li>
                <li><a href="ParTroupe.php">Par Troupe</a></li>
                <li> <a href="Tarif.php"> Tarif</a>  </li>
                <li> <a href="Finance.php">Recette</a></li>
            </ul>
            <ul class="navbar">
			
			La page :	
			</ul>
			<div id="vignette">
				<a href="#Eglise">
					<img class="vignetteP" src="images/imgLieuVeauceEglise.jpg" alt="[ Eglise de Veauce      ]">
				</a>
				<a href="#Château de Lachaise">
					<img class="vignetteP" src="images/imgLieuLachaise2.jpg" alt="[ Photo du château de Lachaise      ]">
				</a>
				<a href="#Manoir des noix">		
					<img class="vignetteP" src="images/imgLieuVeauceManoirEtEglise.jpg " alt="[ Pigeonnier du manoir des noix et de l'église de Veauce vue du ciel           ]">
				</a>
				<a href="#Château d'Idogne">		
					<img class="vignetteP" src="images/imgLieuIdogne2.jpg" alt="[ Photo du château d'Idogne     ]">
				</a>
				<a href="#Domaine de la Quérye">		
					<img class="vignetteP" src="images/imgLieuQuerye1.jpg" alt="[ Photo du domaine de la Querye     ]">
				</a>					
			</div>
        </div>     
        
        <main>
            <div class="decalage">
            
            <div class="Lieu">
                <h2>  Quatres demeures de l'Allier, un musée et une église vous ouvrent leurs grilles pour assister aux représentations théâtrales. </h2>
                <p> Choissisez un lieu en cliquant sur son bouton (dans le menu de la page) pour voir la programmation qu'il accueille puis selectionnez les spectacles qui s'y jouent et vous intéresse. 
                </p>
                <figure>
                <img src="images/kje.jpg" alt=" Infographie Pour Situer les châteaux sur la carte du département " width="100%" id="localisation">
                <figcaption>Photocomposition :Edmée Deusy</figcaption>
                </figure>
            </div>
            
            
            <?php
            $fichier ="data/ResultatsFestival.csv";
            $tab = getTableau($fichier);
            afficheProgParLieu($tab);
            ?>
            </div>
         </main>
         
         
         
         
         
     </body>




</hmtl>
