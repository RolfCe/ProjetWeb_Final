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

function getIndex($lieu,$listVille){
	for($i = 0; $i<count($listVille); $i=$i+1){
		if($lieu === $listVille[$i]){
			return $i;
		}
	}
	return 1;
}

function isInConflict(){
	$fichier = "distanceVille.csv";
	$tab = getTableau($fichier);
	$lieu1 = getIndex($_POST["lieu1"][0],$tab[0]);	
	$lieu2 = getIndex($_POST["lieu2"][0],$tab[0]); 
	$tab = getTableau($fichier);
	$data = explode('/',$tab[$lieu1][$lieu2]);
	$temps = $data[1];
	$horaire1 = explode("h",$_POST["heure1"]);
	$horaire2 =  explode("h",$_POST["heure2"]);
	$heure1 = intval($horaire1[0]);
	$heure2 = intval($horaire2[0]);
	if($heure1<$heure2){
		if(($heure1<17 && $heure2>17) || ($heure1<19 && $heure2>19)){
			$temps = $temps*1.1;
		}	
	}else{
		if(($heure2<17 && $heure1>17) || ($heure2<19 && $heure1>19)){
			$temps = $temps*1.1;
		}		
	}
	$h1 = intval($horaire1[0])*60 + intval($horaire1[1]);
	$h2 = intval($horaire2[0])*60 + intval($horaire2[1]);
	if(abs($h1-$h2)> 120+$temps){
		echo 'false';
	}else{
		echo 'true';
	}
}
isInConflict();
?>