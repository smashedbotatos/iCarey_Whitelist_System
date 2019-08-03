const config = require("../config.json");
const time = require("../lib/time.js");
const mysql = require("../lib/mysql.js");
const fs = require("fs");
const Discord = require('discord.js');


module.exports = {
    name: 'stats',
    description: 'Users Minecraft stats.',
    usage: 'username',
    args: true,
    cooldown: 5,

    execute(message, args) {
        uData = [];
        const bodypath = "skins/body/";

        if (!message.member.roles.some(r => [config.member_role_id, config.trusted_role_id, config.mod_role_id, config.admin_role_id, config.verified_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        if(!args) {
            return message.channel.send("You must give a minecraft username.")
        }

        uData.username = args[0].toLowerCase();

        Database.getUserUser(uData.username, function (res,rData) {
           if(!res){
               return message.channel.send("User does not exist.")
           }

            const sumValues = obj => Object.values(obj).reduce((a, b) => a + b);
            const path = config.statspath + rData[0].uuid + '.json';

            fs.readFile(path, 'utf8', function (err, jsonData) {
                if (err) console.log(err);
                let messageBody = new Discord.RichEmbed();
                messageBody.title = "[Stats for " + rData[0].username + "]";
                messageBody.color = 0xFF6600;
                messageBody.setThumbnail(config.site_url + "/img/" + bodypath + rData[0].uuid + '.png');

                let stats = JSON.parse(jsonData);

                if(stats['stats']['minecraft:custom']['minecraft:play_one_minute'] === 0){
                    messageBody.addField("Player has not logged on.");
                } else {

                    let playTimeTicks = stats['stats']['minecraft:custom']['minecraft:play_one_minute'];
                    let playSeconds = playTimeTicks/20;
                    Time.secondsToReadable(playSeconds, function(day, hr, min){
                        timeReadable = day + ' Days ' + hr + ' Hours ' + min + ' Minutes';
                        messageBody.addField("Played: ", timeReadable);
                    });
                }

                if(!stats['stats']['minecraft:mined']){
                    const minedSum = 0;
                    messageBody.addField("Blocks Mined", minedSum);
                } else {
                    const minedSum = sumValues(stats['stats']['minecraft:mined']);
                    messageBody.addField("Blocks Mined", minedSum);
                }
                if(!stats['stats']['minecraft:used']){
                    const placedSum = 0;
                    messageBody.addField("Blocks Placed", placedSum);
                } else {
                    let placedSum = sumValues(stats['stats']['minecraft:used']);
                    messageBody.addField("Blocks Placed", placedSum);
                }
                if(!stats['stats']['minecraft:killed']){
                    const killedSum = 0;
                    messageBody.addField("Mobs Killed", killedSum);
                } else {
                    const killedSum = sumValues(stats['stats']['minecraft:killed']);
                    messageBody.addField("Mobs Killed", killedSum);
                }
                if(!stats['stats']['minecraft:custom']['minecraft:deaths']){
                    const deathSum = 0;
                    messageBody.addField("Deaths", deathSum);
                } else {
                    const deathSum = stats['stats']['minecraft:custom']['minecraft:deaths'];
                    messageBody.addField("Deaths", deathSum);
                }
                message.channel.send(messageBody)
            });

        });

    },
};