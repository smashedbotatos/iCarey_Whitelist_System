//Mojang API to get usernames from UUID, UUID from Usernames, and player skins


//Import Config
const config = require('../config.json');

//Import project libraries
const database = require('./mysql.js');

//Import Node.js packages
const request = require('request');
const fs = require('fs');

//Mojang API address
const mojanguser = "https://api.mojang.com/users/profiles/minecraft/";
const mojanguuid = "https://api.mojang.com/user/profiles/";
const mojangbody = "https://crafatar.com/renders/body/";
const bodypath = "assets/skins/body/";
const mojanghead = "https://crafatar.com/avatars/";
const headpath = "assets/skins/head/";

//Initialize empty Database object
Mojang = {};


//GET uuid from username
Mojang.getUuid = function (username, cb){
    request.get(mojanguser + username, (err, response, body) => {
        if(!err){
            let apires = JSON.parse(body);
            let uuid = apires['id'].toString();
            let dashed = uuid.replace(/(.{8})(.{4})(.{4})(.{4})(.{12})/g, "$1-$2-$3-$4-$5");
            cb(true, dashed);
        } else if(err){
            cb(false);
        }
    })
};

//Get Username from UUID
Mojang.getUsername = function (uuid, cb){
    request.get(mojanguuid + uuid + "/names", (err, response, body) => {
        if(!err){
            let apires = JSON.parse(body);
            let username = apires[apires.length-1].name.toString();
            cb(true, username);
        } else if(err){
            cb(false);
        }
    })
};

//Get Body from Cravatar
Mojang.getBody = function (uuid, cb) {
    request.head(mojangbody + uuid + "/?size=100&overlay", (err, response, body) => {
      if(!err){
          request(mojangbody + uuid + "/?size=100&overlay").pipe(fs.createWriteStream(bodypath + uuid + ".png"));
          cb(true);
      } else {
          cb(false);
      }
    })

};

//Get Head from Cravatar
Mojang.getHead = function (uuid, cb) {
    request.head(mojanghead + uuid + "/?size=100&overlay", (err, response, body) => {
        if(!err){
            request(mojanghead + uuid + "/?size=100&overlay").pipe(fs.createWriteStream(headpath + uuid + ".png"));
            cb(true);
        } else {
            cb(false);
        }
    })

};