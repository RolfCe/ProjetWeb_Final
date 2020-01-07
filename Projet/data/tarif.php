<?php



function getTableau($fichier){
	$res = array();
	if(($handle = fopen($fichier, "r")) !== FALSE){
		while (($data = fgetcsv($handle)) !== FALSE){
			array_push($res,$data);
		}
	}
	fclose($handle);
	//array_shift($res);	
	return $res;
}

function fusionTab($data,$ajout){
	$res = array();
	foreach($data as $v1){
		foreach($ajout as $v2){
			if($v1[0] !== $v2[0])continue;
			if($v1[1] !== $v2[1])continue;
			if($v1[2] !== $v2[2])continue;
			if($v1[3] !== $v2[3])continue;
			if($v1[4] !== $v2[4])continue;
			if($v1[5] !== $v2[5])continue;
			$v1[6] = intval($v1[6]) + intval($v2[6]);
			$v1[7] = intval($v1[7]) + intval($v2[7]);
			$v1[8] = intval($v1[8]) + intval($v2[8]);
			$v1[9] = intval($v1[9]) + intval($v2[9]);
			$v1[10] = intval($v1[10]) + intval($v2[10]);
		}
		array_push($res,$v1);
	}
	return $res;
}


function modifFichierCSV(){
	$tab = fusionTab(getTableau("ResultatsFestival.csv"),$_POST["data"]);
	if(($handle = fopen("ResultatsFestival.csv", "w")) !== FALSE){
		foreach($tab as $value){
			fputcsv($handle,$value);
		}
	}
	fclose($handle);
	
	echo "Commande Effectué";
	
}
modifFichierCSV();
?>
