var _eventHandlers = {};

//Donne une couleur selon un numero
function colorBlock(n){
    switch(n){
    case 0 : return "#800000";break;
    case 1 : return "#ff0000";break;
    case 2 : return "orange";break;
    case 3 : return "blue";break;
    case 4 : return "#000080";break;
    default: return "black";
    }


}


//Donne les informations du ticket selon le type du ticket représenté par un numéro
function getInfoTicket(n){
    switch(n){
    case 0 : return {"prix":15,"EstOffert":"non","tarif":"Tarif Plein"};break;
    case 1 : return {"prix":10,"EstOffert":"non","tarif":"Tarif Réduit"};break;
    case 2 : return {"prix":0,"EstOffert":"non","tarif":"Tarif Enfant"};break;
    case 3 : return {"prix":12.5,"EstOffert":"oui","tarif":"Tarif Plein"};break;
    case 4 : return {"prix":9,"EstOffert":"oui","tarif":"Tarif Réduit"};break;
    default:return {"prix":0,"EstOffert":"non","tarif":"Erreur"};
    }
}


//Dessine une block dans le canvas   
function block(tab,ctx,x,y, nbTicket,color,tabForm,infoTicket,mult){
    const form = new Path2D();
    let val = 0;
    if(infoTicket.EstOffert == "non"){
        val = nbTicket*infoTicket.prix*0.1;
        form.rect(x,y,50,-val*mult);
    }else{
        val = nbTicket*infoTicket.prix;
        form.rect(x,y,50,-val*mult);
    }
    ctx.fillStyle = color;
    ctx.fill(form);
    var ObjForm = {
       "nbTicket":nbTicket,
       "form":form,
       "color":color,
       "prix":infoTicket.prix,
       "tarif":infoTicket.tarif,
       "EstOffert":infoTicket.EstOffert,
       "highlight":false
    }
    tabForm.push(ObjForm);
    
    return val*mult;
}


//Donne la position de la souris
function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
    }; 
}

//Vérifie si la souris n'est sur aucune des blocks du graph
function checkAllFalse(tabForm,event,ctx){
    if(tabForm.length < 1){
	    return true;
    }else{
	    for(let i = 0; i< tabForm.length;i++){
	        if(ctx.isPointInPath(tabForm[i].form,event.offsetX,event.offsetY)){
	            return false;
	        }
	    }
	return true;
    }		    
			   
			
}


//Donne le type de tarif selon le type de ticket représenté par un numéro
function typeTarif(n){
    switch(n){
    case 0 : return "Tarif Plein";break;
    case 1 : return "Tarif réduit";break;
    case 2 : return "Tarif Enfant";break;
    case 3 : return "Tarif Plein Offert";break;
    case 4 : return "Tarif Réduit Offert";break;
    default: return "Erreur";    
    }
}

//Affiche la légende du graph
function afficheLegende(ctx,x,y){
    ctx.textAlign = "left";
    for(let i = 0; i<5;i++){
        if(i==0){
            ctx.font = "10px Arial";
            ctx.fillText("Gain : ",x,y-5);
        }else if(i==3){
            ctx.font = "10px Arial";
            ctx.fillText("Dépense : ",x,y-5);
        }
        ctx.fillStyle = colorBlock(i);
        ctx.fillRect(x,y,10,10);
        ctx.fillStyle = "black";
        
        ctx.font = "10px Arial";
        ctx.fillText(typeTarif(i),x+12,y+10);
        x = x + 100;
    }
    
    x= x + 50;
    ctx.fillText("Selected",x+12,y+10);
    ctx.fillStyle = "green";
    ctx.fillRect(x,y,10,10);
}

//Dessine la bulle d'information selon le block du graph survolé
function afficheDetailRecette(ctx,infoTicket){
    ctx.fillStyle = "white";
	ctx.fillRect(0,0,500,140);
	ctx.fillStyle = "black";
	
	ctx.font = "26px Arial";
	ctx.fillText("Détail",10,30);
	
	let msg = "Type de Tarif : ";
	ctx.font = "16px Arial";
	ctx.fillText(msg.concat(infoTicket.tarif),50,50)
	
	msg = "Billet Offert : ";
	ctx.fillText(msg.concat(infoTicket.EstOffert),50,70)
	
	msg = "Nombre de Tickets vendus";
	ctx.fillText(msg.concat(" : ",infoTicket.nbTicket),50,90);
	
	msg = "Prix : ";
	ctx.fillText(msg.concat(infoTicket.prix," €"),50,110);
	
	msg = "Total : ";
	if(infoTicket.EstOffert == "oui"){
	    ctx.fillText(msg.concat(infoTicket.nbTicket," x ",infoTicket.prix,"€ = ",infoTicket.prix*infoTicket.nbTicket," €"),50,130);
	}else{
	    ctx.fillText(msg.concat("10% x ",infoTicket.nbTicket," x ",infoTicket.prix,"€ = ",infoTicket.prix*infoTicket.nbTicket*0.1," €"),50,130);
	}
	
	ctx.lineWidth = "3";
	ctx.rect(0,0,500,140);
	ctx.stroke();
	
	
	
}

//Met en valeur le block survolé
function highlightBar(ctx,infoTicket){
    ctx.fillStyle = "green";
    ctx.fill(infoTicket.form);
    infoTicket.highlight = true;
    
}

//Annule la mise en valeur
function undoHighlightBar(ctx,infoTicket){
    ctx.fillStyle = infoTicket.color;
    ctx.fill(infoTicket.form);
    infoTicket.highlight = false;
}

//Clear le Canvas et enleve tout les event listener
function resetCanvasIdStat(){
    let c = document.getElementById("Stat");
    let ctx = c.getContext("2d");
    ctx.clearRect(0,0,1300,700);
    removeAllEvents(c,'mousemove');
    
}



function addEvent(node, event, handler, capture) {
    if (!(node in _eventHandlers)) {
        // _eventHandlers stores references to nodes
        _eventHandlers[node] = {};
    }
    if (!(event in _eventHandlers[node])) {
        // each entry contains another entry for each event type
        _eventHandlers[node][event] = [];
    }
    // capture reference
    _eventHandlers[node][event].push([handler, capture]);
    node.addEventListener(event, handler, capture);
}


function removeAllEvents(node, event) {
    if (node in _eventHandlers) {
        var handlers = _eventHandlers[node];
        if (event in handlers) {
            var eventHandlers = handlers[event];
            for (var i = eventHandlers.length; i--;) {
                var handler = eventHandlers[i];
                node.removeEventListener(event, handler[0], handler[1]);
            }
        }
    }
}

function dessineStatParLieu(){
        var c = document.getElementById("Stat");
		var ctx = c.getContext("2d");//Gere le canvas id=Stat
        //Reset le canvas
		resetCanvasIdStat();
		//Dessine la zone blanche et les axes du graph
		ctx.beginPath();
		ctx.strokeStyle = "black";
		ctx.moveTo(100,80);
		ctx.lineTo(100,600);
		ctx.lineTo(1100,600);
		ctx.fillStyle="white";
		ctx.fillRect(0,0,1200,700);
		ctx.stroke();
		
		ctx.font = "24px Arial";
		ctx.fillStyle = "black";
		ctx.fillText("Recette & Dépense par Lieu", 400 ,40);
		
        ctx.fillStyle = "black";
        ctx.font = "14px Arial";
        ctx.textAlign = "right";
        let x = 100;
        let h = 600;
        for(let i = 1; i<=5; i++){
            h = h-100;
            ctx.fillText(100*i, x-10 ,h);
            ctx.beginPath
            ctx.moveTo(x,h);
            ctx.lineTo(x+1000,h);
            ctx.stroke();
        }
		
		var c2 = document.getElementById("StatInfo");
		var ctx2 = c2.getContext("2d"); //Gere le canvas id=StatInfo, càd la bulle d'info
		
		
		$.ajax({
                url: "data/getRecette.php",
                success: function(data){
                    
                       var RectPath2D = [];
                       var j = 150;
			ctx.fillStyle="black";
			ctx.moveTo(0,0);
			//Dessine les block du graph
			for(let i = 0; i< data.Lieu.length;i++){
			        ctx.textAlign = "center";
		    		ctx.font = "12px Arial";
		    		ctx.fillStyle = "black";
		    		ctx.fillText(data.Lieu[i], j+20, 630,200);
		    		
		    		
		    		let h_recette = 600;
		    		let h_depense = 600;
		    		let rLieu = data[data.Lieu[i]];
		    		for(let y = 0; y < rLieu.length;y++){
		    		    let val = 0;
		    		    let infoTicket = getInfoTicket(y);
		    		    if(y<3){
		    		        val = block(rLieu,ctx,j-30,h_recette,rLieu[y],colorBlock(y),RectPath2D,getInfoTicket(y),1);
		    		        h_recette = h_recette - val;
		    		    }else{
		    		        val = block(rLieu,ctx,j+30,h_depense,rLieu[y],colorBlock(y),RectPath2D,getInfoTicket(y),1);
		    		        h_depense = h_depense - val; 
		    		    }
		    		}
		    		j = j + 175;
		    		
			}
			//Dessine la legende
			afficheLegende(ctx,300,70);
			
			//Initialise les listener d'event
			for(let i = 0; i < RectPath2D.length;i++){
			    let rect = RectPath2D[i].form;
			    addEvent(c,'mousemove',function(event){
			    	if(ctx.isPointInPath(rect,event.offsetX,event.offsetY)){
			    	    afficheDetailRecette(ctx2,RectPath2D[i]);
			    	    if(RectPath2D[i].highlight == false){
			    	        highlightBar(ctx,RectPath2D[i]);
			    	    }
			    	}else{
			    	    if(RectPath2D[i].highlight == true){
			    	        undoHighlightBar(ctx,RectPath2D[i]);
			    	    }
			    	}
			    	if(checkAllFalse(RectPath2D,event,ctx)){
				        ctx2.clearRect(0,0,c2.width,c2.height);
				    }
				
			    
			    
			        },false);
			}
						
			
			
                }
            });
}

function dessineStatParRepresentation(){
        let c = document.getElementById("Stat");
		let ctx = c.getContext("2d");//Gere le canvas id=Stat
        
        //Reset le canvas
		resetCanvasIdStat();
		//Dessine la zone blanche et les axes du graph
		ctx.beginPath();
		ctx.strokeStyle = "black";
		ctx.moveTo(100,80);
		ctx.lineTo(100,600);
		ctx.lineTo(1250,600);
		ctx.fillStyle="white";
		ctx.fillRect(0,0,1250,700);
		ctx.stroke();
		
		ctx.font = "24px Arial";
		ctx.fillStyle = "black";
		ctx.fillText("Recette & Dépense par Représentation", 400 ,40);
		
        ctx.fillStyle = "black";
        ctx.font = "14px Arial";
        ctx.textAlign = "right";
        let x = 100;
        let h = 600;
        for(let i = 1; i<=5; i++){
            h = h-100;
            ctx.fillText(100*i, x-10 ,h);
            ctx.beginPath
            ctx.moveTo(x,h);
            ctx.lineTo(x+1200,h);
            ctx.stroke();
        }
		
		let c2 = document.getElementById("StatInfo");
		let ctx2 = c2.getContext("2d"); //Gere le canvas id=StatInfo, càd la bulle d'info
		
		
		$.ajax({
                url: "data/getRecetteParRepresentation.php",
                success: function(data){
                    
                       var RectPath2D = [];
                       
                       let j = 150;
			ctx.fillStyle="black";
			ctx.moveTo(0,0);
			//Dessine les block du graph
			for(let i = 0; i< data.Spectacle.length;i++){
			        ctx.textAlign = "center";
		    		ctx.font = "12px Arial";
		    		ctx.fillStyle = "black";

		    		ctx.fillText(data.Spectacle[i], j+20, 630,150);
		    		
		    		
		    		let h_recette = 600;
		    		let h_depense = 600;
		    		let rSpec = data[data.Spectacle[i]];
		    		for(let y = 0; y < rSpec.length;y++){
		    		    let val = 0;
		    		    let infoTicket = getInfoTicket(y);
		    		    if(y<3){
		    		        val = block(rSpec,ctx,j-30,h_recette,rSpec[y],colorBlock(y),RectPath2D,getInfoTicket(y),1);
		    		        h_recette = h_recette - val;
		    		    }else{
		    		        val = block(rSpec,ctx,j+30,h_depense,rSpec[y],colorBlock(y),RectPath2D,getInfoTicket(y),1);
		    		        h_depense = h_depense - val; 
		    		    }
		    		}
		    		j = j + 125;
		    		
			}
			//Dessine la legende
			afficheLegende(ctx,300,70);
			
			//Initialise les listener d'event
			for(let i = 0; i < RectPath2D.length;i++){
			    let rect = RectPath2D[i].form;
			    addEvent(c,'mousemove',function(event){
			    	if(ctx.isPointInPath(rect,event.offsetX,event.offsetY)){
			    	    afficheDetailRecette(ctx2,RectPath2D[i]);
			    	    if(RectPath2D[i].highlight == false){
			    	        highlightBar(ctx,RectPath2D[i]);
			    	    }
			    	}else{
			    	    if(RectPath2D[i].highlight == true){
			    	        undoHighlightBar(ctx,RectPath2D[i]);
			    	    }
			    	}
			    	if(checkAllFalse(RectPath2D,event,ctx)){
				        ctx2.clearRect(0,0,c2.width,c2.height);
				    }
				
			    
			    
			        },false);
			}
						
			
			
                }
            });
}
            