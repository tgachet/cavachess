# Cavachess

Cavachess is a communtiy website around Chess  
This project was developped in 9 days for a school project (webforce3)  
Actual version : 1.0   
Screenshots : see screenshots folder  

## Install
* Git clone this repository 
* Install Symfony => composer install
* Update parameters.yml with your server config
* Git clone : https://github.com/tgachet/cavachess_socket
* Read readme to configure and launch the socket.io server

## Version 1.0
### Front-office
* Play online
* Game modes (ingame timers) 
* Rankings
* Random matchmaking : Players are matched if they play the same competition and are close (+/- 200 pts) in ranks.
* Ranking and players statistics
* Ingame chat
* Member + Friend list
* Blog
* Desktop, tablet landscape, smartphone renders.

### Back-office (CRUD = Create, Read, Update, Delete)
* Admin Role only
* Blog : Categories and Articles CRUD
* Competition : CRUD.
* Users : RD. Update role.

### Issues
* You cannot delete competitions (throw an error). Fix set null on cascade
* Issues relatives to the websocket server
* Menu render for tablet portrait 
* Draw is not recorded
* Rankings are all initialized at 1500 points. No dynamic Entry.

## Dependencies
* Symfony 3.2
* jQuery 3.1.1
* Bootstrap 3.3.7
* jQuery-ui 1.12.1
* chess.js 0.10.2
* chessboard.js 0.3.0
* socket.io 1.7.2
* express 4.14.0

## Authors
Ali Belqziz  
Pierre Antoine Wurmser  
Thomas Gachet  
Sabri Mtir  