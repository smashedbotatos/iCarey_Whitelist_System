
//Import Config
const config = require('../config.json');

//Import project libraries
const mojang = require('../lib/mojang.js');
const mysql = require('../lib/mysql.js');

module.exports = {
    name: 'update',
    description: 'tests a command',
    usage: 'skin username',
    cooldown: 0,
    execute(message, args) {

        uData = [];
        const data = [];

        if (args[0].toLowerCase() === 'help') {
            data.push('Command Usage');
            data.push('Example: !update skin Smashedbotatos');
            data.push('Example: !update username Smashedbotatos Smashedbotatos360NoScope');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with update instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        } else if (args[0].toLowerCase() === "skin") {
            uData.username = args[1].toLowerCase();

            Database.getUserUser(uData.username, function (uRes, rData) {
               if(uRes){
                   Mojang.getBody(rData[0].uuid, function (bRes) {
                       if (bRes) {
                           Mojang.getHead(rData[0].uuid, function (hRes) {
                               if (hRes) {
                                   message.channel.send("Updated Skin for " + uData.username)
                               } else {
                                   message.channel.send("Skin update failed.")
                               }
                           })
                       } else {
                           message.channel.send("Skin update failed.")
                       }
                   });

               } else{
                   message.channel.send("Database Error")
               }
            });

        } else if (args[0].toLowerCase() === "username") {

            if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
                return message.reply("Sorry, you don't have permissions to use this command! Ask an admin or mod to update your username.");
            }

            if (args.length !== 3) {
                data.push('You must include old username first and the new username second.');
                data.push('Example: !update username Smashedbotatos Smashedbotatos360NoScope');

                return message.author.send(data, {split: true})
                    .then(() => {
                        if (message.channel.type === 'dm') return;
                        message.reply('I\'ve sent you a DM with update instructions.');
                    })
                    .catch(error => {
                        console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                        message.reply('it seems like I can\'t DM you!');
                    });
            } else {
                uData.olduser = args[1].toLowerCase();
                uData.newuser = args[2].toLowerCase();
                Database.getUserUser(uData.olduser, function (uRes, rData) {
                    if (uRes) {
                        if (rData[0].discord_id !== message.author.id) {
                            return message.channel.send('You may only update a username from that users verified discord account');
                        } else if (rData[0].username !== uData.olduser) {
                            return message.channel.send('Current username doesn\'t match database');
                        } else {
                            Database.updateUser(uData, function (uuRes) {
                                if (uuRes) {
                                    return message.channel.send(uData.olduser + ' updated to ' + uData.newuser);
                                }
                            })
                        }
                    } else {
                        return message.channel.send('Database Error');
                    }

                });

            }
        } else {
            data.push('You must include an action and arguments.');
            data.push('Example: !update skin Smashedbotatos');
            data.push('Example: !update username Smashedbotatos Smashedbotatos360NoScope1');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with update instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        }
    }
};