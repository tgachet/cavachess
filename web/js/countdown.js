function getTimeRemaining(endtime) {
  var t = endtime - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  return {
    'total': t,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}
var timeintervalp1;
var timeintervalp2;

function initializeClockp1(id, endtime) {
  var clock = document.getElementById(id);

  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);


    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeintervalp1);
    }
  }

  updateClock();
  timeintervalp1 = setInterval(updateClock, 1000);
}

function initializeClockp2(id, endtime) {
  var clock = document.getElementById(id);

  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);


    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeintervalp2);
    }
  }

  updateClock();
  timeintervalp2 = setInterval(updateClock, 1000);
}


/* Functions pour récupérer les temps des joueurs */
function getClockp1(){
    var clock = {
        hours : parseInt($("#clockdivp1 .hours").text()),
        minutes :  parseInt($("#clockdivp1 .minutes").text()),
        seconds :  parseInt($("#clockdivp1 .seconds").text())
    }; 
    return clock; 
};      
function getClockp2(){
    var clock = {
        hours : parseInt($("#clockdivp2 .hours").text()),
        minutes :  parseInt($("#clockdivp2 .minutes").text()),
        seconds :  parseInt($("#clockdivp2 .seconds").text())
    }; 
    return clock; 
}; 