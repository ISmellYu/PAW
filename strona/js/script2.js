function gettheDate() {
    Todays = new Date();
    TheDate = "" + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100);
    document.getElementById("data").innerHTML = TheDate;
}

var myTimerID = null;
var myTimerRunning = false;

function stopclock() {
    if (myTimerRunning) {
        clearTimeout(myTimerID);
        myTimerRunning = false;
    }
}

function startclock() {
    stopclock();
    gettheDate();
    showtime();
}

function showtime() {
    var now_date = new Date();
    var now_horus = now_date.getHours();
    var now_minutes = now_date.getMinutes();
    var now_seconds = now_date.getSeconds();
    var timeValue = "" + ((now_horus > 12) ? now_horus - 12 : now_horus);
    timeValue += ((now_minutes < 10) ? ":0" : ":") + now_minutes;
    timeValue += ((now_seconds < 10) ? ":0" : ":") + now_seconds;
    timeValue += (now_horus >= 12) ? " P.M." : " A.M.";
    document.getElementById("zegarek").innerHTML = timeValue;
    myTimerID = setTimeout("showtime()", 1000);
    myTimerRunning = true;
}