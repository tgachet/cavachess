/* Function gamelenth */
function gamelength(ingameclock, clockp1, clockp2, option){
    var gamelentgh;
    
    /* Calcul des timestamp */
    var gametime = Date.parse(new Date(Date.parse(new Date()) + ((ingameclock.hours*60*60) + (ingameclock.minutes * 60) + ingameclock.seconds) * 1000));    
    var clockp1 = Date.parse(new Date(Date.parse(new Date()) + ((clockp1.hours*60*60) + (clockp1.minutes * 60) + clockp1.seconds) * 1000));  
    var clockp2 = Date.parse(new Date(Date.parse(new Date()) + ((clockp2.hours*60*60) + (clockp2.minutes * 60) + clockp2.seconds) * 1000));
    
    if (option === 'total'){
        gamelentgh = (gametime + gametime - clockp1 - clockp2)/1000;
        return gamelentgh;
    }
    else if (option === 'white'){
        gamelentgh = (gametime - clockp1)/1000;
        return gamelentgh;        
    }
    else if (option === 'black'){
        gamelentgh = (gametime - clockp2)/1000;
        return gamelentgh;        
    }
}

/* AJAX */
function registerGame(url, winner, looser, gamelength, gamelengthwinner, gamelengthlooser, nbplays, nbplayswinner, nbplayslooser, competition){
   $.ajax({
            url : url,
            method : 'POST',			
            data :  { 
                        winner : winner,
                        looser : looser,
                        gamelength : gamelength,
                        gamelengthwinner : gamelengthwinner,
                        gamelengthlooser : gamelengthlooser,
                        nbplays : nbplays,
                        nbplayswinner : nbplayswinner,
                        nbplayslooser : nbplayslooser,
                        competition : competition
                    },
            success : function(data) {
                    // fonction exécutée au succès de la requête
                        console.log(data);
            },
            error : function(jqXHR, textStatus, errorThrow){
                    // fonction exécutée à l'échec de la requête
                    console.log(jqXHR, textStatus, errorThrow);
            },
            complete : function (data) {
                    // fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status	
            }						
    });	
}

function updateRank(url, winner, looser, winnerrank, looserrank, competition){
   $.ajax({
            url : url,
            method : 'POST',			
            data :  {
                        winner : winner,
                        looser : looser,                
                        winnerrank : winnerrank,
                        looserrank : looserrank,
                        competition : competition                        
                    },
            success : function(data) {
                    // fonction exécutée au succès de la requête
                        console.log(data);
            },
            error : function(jqXHR, textStatus, errorThrow){
                    // fonction exécutée à l'échec de la requête
                    console.log(jqXHR, textStatus, errorThrow);
            },
            complete : function (data) {
                    // fonction exécutée lorsque la requête est terminée. Renvoie un objet readyState + response + status	
            }						
    });	    
}

/* Enregistrement de la partie */
//if(gameisover === 'youwin'){
//   registerGame(username, opponent, gamelength(ingameclock, getClockp1(), getClockp2()), nbplays, competition); 
//}
