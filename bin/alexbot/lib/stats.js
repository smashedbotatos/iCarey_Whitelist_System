const config = require('../config.json');
const database = require('./mysql.js');
// Import FileSystem library
const fs = require("fs");
const glob = require("glob");
const path = require("path");

//Initialize empty Database object
Stats = {};

Stats.updateStats = function (cb) {
    data = [];

    glob(config.statspath + "*.json", function (err, files) {
        if (err) {
            console.log("Can not read stats folder. " + config.statspath, err);
            cb(false);
        }
        files.forEach(function (file) {
            fs.readFile(file, 'utf8', function (err, data) { // Read each file
                if (err) {
                    console.log("Cannot read the file: " + file, err);
                    cb(false);
                }
                let iStats = JSON.parse(data);


                Database.checkStats(path.basename(file, path.extname(file)), function (sRes) {
                    if (sRes) {
                        Database.statsUpdate(iStats, path.basename(file, path.extname(file)), function (upRes) {

                        });
                    } else {
                        Database.getUser(path.basename(file, path.extname(file)), function (uRes, uData) {
                            if (uRes) {
                                Database.statsInit(iStats, uData[0], function (iRes) {
                                });
                            }
                        });
                    }
                });
                data.id = 0;
            })
        });
        cb(true);
    });
};