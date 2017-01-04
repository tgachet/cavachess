function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function show_game(name, room, color, arg) {
	if (arg === 1)
	{
		$("#adversaire").html(username+" (vous) "+ color +" contre "+name+ " dans la salle "+room);
	}
	else 
	{
		$("#adversaire").html("Partie terminée");
	}
}