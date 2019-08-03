// Import our Config file
const config = require('../config.json');
const database = require('./mysql.js');

//Initialize empty Database object
Hackcheck = {};


Hackcheck.Xray = function (stats, cb){
    if(['minecraft:mined'] in stats['stats']) {
        if("minecraft:diamond_ore" in stats['stats']['minecraft:mined']) {
            let averageDia = stats['stats']['minecraft:custom']['minecraft:play_one_minute'] / stats['stats']['minecraft:mined']['minecraft:diamond_ore'];
            if(averageDia <= 5000) {
                cb(true, averageDia);
            } else {
                cb(false);
            }

            } else {
            cb(false)
        }
    } else {
        cb(false);
    }
};

Hackcheck.Grief = function (stats,cb){
    if("minecraft:used" in stats['stats']) {

        if("minecraft:flint_and_steel" in stats['stats']['minecraft:used']) {
            let averageFire = stats['stats']['minecraft:custom']['minecraft:play_one_minute'] / stats['stats']['minecraft:used']['minecraft:flint_and_steel'];
            if(averageFire <= 2000){
                cb(true, averageFire);
            } else {
                cb(false);
            }
        }
    } else {
        cb(false);
    }

};

Hackcheck.Tnt = function (stats,cb){
    if("minecraft:used" in stats['stats']) {
        if("minecraft:tnt" in stats['stats']['minecraft:used']) {
            let averageTnt = stats['stats']['minecraft:custom']['minecraft:play_one_minute'] / stats['stats']['minecraft:used']['minecraft:tnt'];
            if(averageTnt < 5000){
                cb(true, averageTnt);
            } else {
                cb(false);
            }
        }
    } else {
        cb(false);
    }

};

Hackcheck.enderEgg = function (stats,cb){
    if("minecraft:picked_up" in stats['stats']) {
        if("minecraft:dragon_egg" in stats['stats']['minecraft:picked_up']) {
            let dragonegg = stats['stats']['minecraft:picked_up']['minecraft:dragon_egg'];
            if(dragonegg >= 1){
                cb(true, dragonegg);
            } else {
                cb(false);
            }
        }
    } else {
        cb(false);
    }

};

Hackcheck.stealing = function (stats,cb){
    if("minecraft:mined" in stats['stats']) {
        if("minecraft:chest" in stats['stats']['minecraft:mined']) {
            let chestAverage = stats['stats']['minecraft:custom']['minecraft:play_one_minute'] / stats['stats']['minecraft:mined']['minecraft:chest'];
            if(chestAverage <= 3000){
                cb(true, chestAverage);
            } else {
                cb(false);
            }
        }
    } else {
        cb(false);
    }

};