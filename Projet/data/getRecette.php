<?php

header('Content-Type: application/json');

function getTableau($fichier){
    $tab = file($fichier);
    $res = array(count($tab));
    
    for($i = 0; $i< count($tab);$i=$i+1){
        $res[$i] = explode(",",$tab[$i]);
    }
    array_shift($res);
    return $res;
}


//Donne la qté de billet par lieu et par type de billet déterminé par le csv
function getNbBilletParLieu($tab){
    
    $result = array();
   
    foreach($tab as $value){
        $lieu = $value[3]; 
        if(isset($result[$lieu])){
            for($i=0; $i< 5;$i=$i+1){
                $result[$lieu][$i] =  $result[$lieu][$i] + intval($value[$i+6]);
            }
        }else{
            $result[$lieu] = array();
            for($i=0; $i< 5;$i=$i+1){
                array_push($result[$lieu],intval($value[$i+6]));
            }
        }        
    }
    $result["Lieu"] = array();
    foreach($result as $lieu => $v){
    	if($lieu !== "Lieu"){
        	array_push($result["Lieu"],$lieu);
        }
    }
    
    return $result;   
}

function getJsonFromTab($tab){
    return json_encode($tab);

}    

$fichier = "ResultatsFestival.csv";

echo getJsonFromTab(getNbBilletParLieu(getTableau($fichier)));










?>



