<?php
function getTableau($fichier){
	$res = array();
	if(($handle = fopen($fichier, "r")) !== FALSE){
		while (($data = fgetcsv($handle)) !== FALSE){
			array_push($res,$data);
		}
	}
	fclose($handle);	
	return $res;
}

function affiche(){
	$lieu1 = intval($_POST["lieu1"]);	
	$lieu2 = intval($_POST["lieu2"]);	
	$heure= 0;
	$minute=0;
	if($lieu1 === $lieu2){
		echo "Distance 0 km, Temps de Trajet 00 h 00 min";
	}else{
		$h = explode("h",$_POST["heure"]);
		$fichier = "distanceVille.csv";
		$tab = getTableau($fichier);
		$data = explode('/',$tab[$lieu1][$lieu2]);
		$distance = intval($data[0]);
		$temps = $data[1];
		if(intval($h[0])>=17 && intval($h[0])<19){
			$distance = $distance*1.1;
			$temps = $temps *1.1;
		}
		
		$heure = intdiv($temps,60);
		$minute = $temps % 60;
		echo "Distance $distance km, Temps de Trajet $heure h $minute min";
	}
}
affiche();
?>