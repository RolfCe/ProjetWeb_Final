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

function getJsonFromTab($tab){
    return json_encode($tab);

}   

function getNbBilletParLieu($tab){
    
    $result = array();
   
    foreach($tab as $value){
        $spectacle = $value[5]; 
        if(isset($result[$spectacle])){
            for($i=0; $i< 5;$i=$i+1){
                $result[$spectacle][$i] =  $result[$spectacle][$i] + intval($value[$i+6]);
            }
        }else{
            $result[$spectacle] = array();
            for($i=0; $i< 5;$i=$i+1){
                array_push($result[$spectacle],intval($value[$i+6]));
            }
        }        
    }
    $result["Spectacle"] = array();
    foreach($result as $spectacle => $v){
    	if($spectacle !== "Spectacle"){
        	array_push($result["Spectacle"],$spectacle);
        }
    }
    
    return $result;   
}

$fichier = "ResultatsFestival.csv";

echo getJsonFromTab(getNbBilletParLieu(getTableau($fichier)))

?>
