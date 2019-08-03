
const config = require('../config.json');

Time = [];
Time.secondsToReadable = function (timeSeconds, cb) {
        let seconds = parseInt(timeSeconds, 10);
        let days = Math.floor(seconds / (3600*24));
        seconds  -= days*3600*24;
        let hrs   = Math.floor(seconds / 3600);
        seconds  -= hrs*3600;
        let mnts = Math.floor(seconds / 60);
        seconds  -= mnts*60;
        cb(days, hrs, mnts, seconds)
};

/* Not Tested!!!

Time.ticksToSeconds = function (timeTicks, cb) {
        let ticks = parseInt(timeTicks, 10);
        let days = Math.floor(ticks / (20*3600*24));
        ticks  -= days*20*3600*24;
        let hrs   = Math.floor(ticks / 20*3600);
        ticks  -= hrs*20*3600;
        let mnts = Math.floor(ticks / 20*60);
        ticks  -= mnts*20*60;
        cb(days, hrs, mnts)
};*/
