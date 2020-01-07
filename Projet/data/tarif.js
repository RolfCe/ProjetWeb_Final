function incrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById(id).value = value;
}

function dncrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    if(value>0)
    value--;
    document.getElementById(id).value = value;
}


var Tarif = {"panier":{},
			  Prix_adulte:15,
			  Prix_enfant:0,
			  Prix_reduit:12,
			  nbTicketAdulte:0,
			  nbTicketEnfant:0,
			  nbTicketReduit:0,
			  nbTicketAdulteOffert:0,
			  nbTicketReduitOffert:0
			};

function valider(){
	let e = document.getElementById("spectacle");
	let spectacle = e.options[e.selectedIndex].value;
	let ticketA = parseInt(document.getElementById("adulte").value);
	let ticketE = parseInt(document.getElementById("enfant").value);
	let ticketR = parseInt(document.getElementById("tarif_reduit").value);
	if( Tarif.panier.hasOwnProperty(spectacle)){
		Tarif.panier[spectacle][0] += ticketA;
		Tarif.panier[spectacle][1] += ticketR;
		Tarif.panier[spectacle][2] += ticketE;
		Tarif.nbTicketAdulte += ticketA;
		Tarif.nbTicketEnfant += ticketE;
		Tarif.nbTicketReduit += ticketR;
	}else{
		Tarif.panier[spectacle] = [ticketA,ticketR,ticketE,0,0,0];
		Tarif.nbTicketAdulte += ticketA;
		Tarif.nbTicketEnfant += ticketE;
		Tarif.nbTicketReduit += ticketR;
	}
	
	document.getElementById("adulte").value = 0;
	document.getElementById("enfant").value = 0;
	document.getElementById("tarif_reduit").value = 0;
	MAJbilletOffert();
	miseAJourPanier();
	
}

function resetPanier(){
	Tarif = {"panier":[],
			  Prix_adulte:15,
			  Prix_enfant:0,
			  Prix_reduit:10,
			  nbTicketAdulte:0,
			  nbTicketEnfant:0,
			  nbTicketReduit:0,
			  nbTicketAdulteOffert:0,
			  nbTicketReduitOffert:0
			};
	miseAJourPanier();
}

function miseAJourPanier(){
	let nbTicketA = Tarif.nbTicketAdulte - Tarif.nbTicketAdulteOffert;
	let nbTicketR = Tarif.nbTicketReduit - Tarif.nbTicketReduitOffert;
	let prixTotal = nbTicketA*Tarif.Prix_adulte +nbTicketR*Tarif.Prix_reduit;
	$("#PrixTotal").html(prixTotal);
	$("#Panier").html("");
	for (var key in Tarif.panier) {
		if (Tarif.panier.hasOwnProperty(key)) {
			$("#Panier").append("<li>".concat(key," : "));
			if(Tarif.panier[key][0].toString()>0){
				$("#Panier").append("Ticket Adulte : ",Tarif.panier[key][0].toString(),"<br>");
			}
			if(Tarif.panier[key][1].toString()>0){
				$("#Panier").append("Ticket Reduit :",Tarif.panier[key][1].toString(),"<br>");
			}
			if(Tarif.panier[key][2].toString()>0){
				$("#Panier").append("Ticket Enfant : ",Tarif.panier[key][2].toString());
			}
			
			$("#Panier").append("</li>")
		}
	}
	$("#Panier").append("<li>".concat("Nombre de billet Adulte offert : ",Tarif.nbTicketAdulteOffert.toString(),"</li>"));
	$("#Panier").append("<li>".concat("Nombre de billet Reduit offert : ",Tarif.nbTicketReduitOffert.toString(),"</li>"));

	
}

function validerCommande(){
	if(Tarif.panier.length !==0 ){
		let res = [];
		for (let key in Tarif.panier) {
			if (Tarif.panier.hasOwnProperty(key)){
				let a = key.split(",");
				let b = Tarif.panier[key];
				b[0] = b[0] - b[3];
				b[1] = b[1] - b[4];
				res.push(a.concat(b));
			}
		}
		
		let data = {"data": res};
		$.ajax({
			url: "data/tarif.php",
			method: "POST",
			data: data,
			success: function (result) {
				//$("#alert").html(result);
				alert(result);
			},
			error: function(request,Status,error){
				console.log(Status);
				console.log(error);
			}
		});
		resetPanier();
	}
}

function resetBilletOffert(){
	for(var key in Tarif.panier){
			if (Tarif.panier.hasOwnProperty(key)){
				Tarif.panier[key][3] = 0;
				Tarif.panier[key][4] = 0;
			}
	}
			
}
function ajoutBilletReduitOffert(nbBilletOffert){
		for(var key in Tarif.panier){
			if (Tarif.panier.hasOwnProperty(key)){
				if(Tarif.panier[key][1]<=nbBilletOffert){
					Tarif.panier[key][4] = nbBilletOffert;
					break;
				}else{
					Tarif.panier[key][4] = Tarif.panier[key][1];
					nbBilletOffert = nbBilletOffert - Tarif.panier[key][1];
				}
			}
		}	
		return nbBilletOffert;
}

function ajoutBilletAdulteOffert(nbBilletOffert){
		for(var key in Tarif.panier){
			if (Tarif.panier.hasOwnProperty(key)){
				if(Tarif.panier[key][0]<=nbBilletOffert){
					Tarif.panier[key][3] = nbBilletOffert;
					break;
				}else{
					Tarif.panier[key][3] = Tarif.panier[key][1];
					nbBilletOffert = nbBilletOffert - Tarif.panier[key][1];
				}
			}
		}	
		return nbBilletOffert;	
}

function MAJbilletOffert(){
	nbBilletA = Tarif.nbTicketAdulte;
	nbBilletR = Tarif.nbTicketReduit;
	Tarif.nbTicketAdulteOffert = 0;
	Tarif.nbTicketReduitOffert = 0;
	nbBilletTotal = nbBilletA + nbBilletR;
	nbBilletOffert = Math.floor(nbBilletTotal/6);
	resetBilletOffert();
	if(nbBilletOffert <= nbBilletR){ //ajout de billet offert Réduit
		Tarif.nbTicketReduitOffert = nbBilletOffert;
		ajoutBilletReduitOffert(nbBilletOffert);
	}else{
		Tarif.nbTicketReduitOffert = nbBilletR;
		Tarif.nbTicketAdulteOffert = nbBilletOffert - nbBilletR;
		nbBilletOffert = ajoutBilletReduitOffert(nbBilletOffert);
		ajoutBilletAdulteOffert(nbBilletOffert);
	}
	console.log(Tarif);
}

function getTempsDistance(){
	let e1 = document.getElementById("lieu1");
	let lieu1 = e1.options[e1.selectedIndex].value;
	let e2 = document.getElementById("lieu2");
	let lieu2 = e2.options[e2.selectedIndex].value;
	let heure = document.getElementById("heure").value;
	let data = {"lieu1":lieu1,"lieu2":lieu2,"heure":heure};
	$.post("data/getDistanceTemps.php",data,
		   function(result){
			   console.log("ok");
			   let ville1 = e1.options[e1.selectedIndex].textContent;
			   let ville2 = e2.options[e2.selectedIndex].textContent;
			   $("#textTpsDist").html( ville1.concat(" - ",ville2,"<br>"));
			   $("#textTpsDist").append(result);
		   }
			   
	)
}


function checkConflit(){
	$("#conflict").hide();
	let e = document.getElementById("spectacle");
	let spectacle = e.options[e.selectedIndex].value;
	let temp = spectacle.split(",");
	let lieu = temp[4].split(" ",1);
	let jour = temp[0];
	let horaire = temp[1]
	for (var key in Tarif.panier) {
		if (Tarif.panier.hasOwnProperty(key)) {
			if(spectacle !== key){
				temp = key.split(",");
				if(jour !== temp[0]){
					continue;
				}else{
					let horaire2 = temp[1]
					let lieu2 = temp[4].split(" ",1);
					let data = {"lieu1":lieu,"lieu2":lieu2,"heure1":horaire,"heure2":horaire2};
					$.post("data/getTemps.php",data,
					       function(result){
							   //$("#erreur").html(result);
							   if(result === 'true'){
								   $("#conflict").show();
							   }
						   }
					);
					break;
					
				}
			}
		}
	}
}
