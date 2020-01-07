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

function getTroupe($tab){
	$troupe = $tab[0][5];
	echo "<a href=\"#$troupe\"> $troupe </a> <br>";
	$restant = array();
	foreach($tab as $value){
		if($value[5]!==$troupe){
			array_push($restant,$value);
		}
	}
	if(count($restant)!==0){
		getTroupe($restant);
	}
	
}

function afficherParDateSansTroupe($tab,$d){

              $date = $tab[0];
              
                         
              if($date !== $d){
                  $a = ucfirst($date);
                  echo "<h3>$a<h3>";
              
              }
              
              $horaire = $tab[1];
              $spectacle = $tab[2];
              $lieu = $tab[3];
              $village = $tab[4];
              echo "<p>";
              echo "<horaire>à $horaire</horaire>, ";
              echo "<titrespectacle>$spectacle</titrespectacle>, ";
              echo "<lieu>au $lieu à $village</lieu>";
              echo "</p>";



}


function afficheProgParTroupe($tab){

     $restant = array();
     
     $troupe = $tab[0][5];
     $date = "";
     
     echo '<div class="Lieu">';
     echo "<h2 id=\"$troupe\" > $troupe </h2>";
     echo "<h2> Le Programme :  </h2>";
     
     for($i=0; $i< count($tab); $i= $i+1){
          if($tab[$i][5] == $troupe){
               afficherParDateSansTroupe($tab[$i],$date);
               $date = $tab[$i][0];
          
          }else{
              array_push($restant, $tab[$i]);
          }
     }
     echo "</div>\n\n";
     
     if(count($restant) !== 0){
     
         afficheProgParTroupe($restant);
     
     }
    

}


?>


<html>
    <head> 
        <link rel="stylesheet" type="text/css" href="styleTheatresDeBourbonPourPHP.css">
    
    <head>
    
    <body>
        <div class="bandeau">
            <h1> Festival de Theatre </h1>
        </div>
        
        <div class="menu">
            <ul class="nav">
                <li> <a href="index.html">Index</a> </li>
                <li><a href="QuiSommeNous">Qui sommes nous ?</a></li>
                <li><a href="ParJour.php">Par Jour</a></li>
                <li><a href="ParLieu.php">Par Lieu</a></li>
                <li> <a href="Tarif.php"> Tarif</a>  </li>
                <li> <a href="Finance.php">Recette</a></li>
            </ul>
        </div>

        <main>
            <div class="decalage">
            <?php
            $fichier ="data/ResultatsFestival.csv";
            $tab = getTableau($fichier);
			echo "<div class=Lieu> <h2>Liste des troupes : </h2><br>";
			$tabTroupe = getTroupe($tab);
			echo "</div>";
            afficheProgParTroupe($tab);
            ?>
            </div>
        </main>    
        
    
    
    
    </body>





</html>
