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
	


function afficherRepParJour($tab){
    $date = "";
    
    echo "<main> \n <div class='decalage '> <div class=Lieu>";
    foreach($tab as $value){
        if($date !== $value[0]){
            $date = $value[0];
            echo "<h2> $date </h2>";
        }
        $horaire = $value[1];
        $lieu = $value[3];
        $titreSpectacle = $value[2];
        $troupe = $value[5];
        $village = $value[4];
        
        
        echo "<p >";
        echo "<horaire> $horaire</horaire>, ";
        echo "<lieu>au $lieu à $village</lieu>, ";
        echo "<titrespectacle> $titreSpectacle</titrespectacle>, ";
        echo "<troupe> $troupe</troupe>";
        echo "</p>";
    }
    
    echo  "</div></div>\n</main>";
}

function afficherRepParHeureSansDate($tab){           
	$lieu = $tab[3];
    $titreSpectacle = $tab[2];
    $troupe = $tab[5];
    $village = $tab[4];
    echo "<p>";
    echo "<lieu>au $lieu à $village</lieu>, ";
    echo "<titrespectacle> $titreSpectacle</titrespectacle>, ";
    echo "<troupe>par $troupe</troupe>";
    echo "</p>";	
}

function afficherRepParJour_v2($tab,$d){
     $restant = array();
     
     $date = $tab[0][0];
     $horaire = $tab[0][1];
	 if($date !== $d){
		echo "<h2> $date </h2>";
	 }
     echo "<h3>$horaire</h3>";
     for($i=0; $i< count($tab); $i= $i+1){
          if($tab[$i][0] === $date && $tab[$i][1]===$horaire){
              afficherRepParHeureSansDate($tab[$i]);
              $horaire = $tab[$i][1];
          
          }else{
              array_push($restant, $tab[$i]);
          }
     }
     
     if(count($restant) !== 0){
     
         afficherRepParJour_v2($restant,$date);
     
     }
	
	
}

?>

<html>
    <head>
        <title> Programme par Jour</title>
        <link rel="stylesheet" type="text/css" href="styleTheatresDeBourbonPourPHP.css">
        <meta charset="UTF-8">
    </head>

    <body>
        <div class="bandeau">
            <h1>Programme Par Jour</h1>
        </div>

        <div class="menu">
            <ul class="nav">
                <li> <a href="index.html">Index</a> </li>
                <li><a href="QuiSommeNous">Qui sommes nous ?</a></li>
                <li><a href="ParTroupe.php">Par Troupe</a></li>
                <li><a href="ParLieu.php">Par Lieu</a></li>
                <li> <a href="Tarif.php"> Tarif</a>  </li>
                <li> <a href="Finance.php">Recette</a></li>
            </ul>
        </div> 
			<main><div class='decalage '> <div class=Lieu>
        <?php
        $fichier ="data/ResultatsFestival.csv";
        $tab = getTableau($fichier);
        afficherRepParJour_v2($tab,"");
        ?>
			</div></div></main>
    </body>

</html>
