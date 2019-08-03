//TODO This needs TESTED

var main = require('../AlexBot.js');
const config = require('../config.json');
const Discord = require('discord.js');

module.exports = {
    name: 'whitelist',
    description: 'Apply/Add/Remove for the server whitelist',
    usage: 'apply add remove waiting',
    cooldown: 5,
    execute(message, args, client) {
        uData = [];

        const data = [];

        if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        if (!args.length > 0 || args[0].toLowerCase() === 'help') {
            data.push('You must include an action and arguments.');
            data.push('Example: approve Smashedbotatos');
            data.push('Example: remove Smashedbotatos');
            data.push('Example: waiting');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with whitelist instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        } else if (args[0].toLowerCase() === "waiting") {

            Database.getApp(function (res, rData) {
                if (!res) {
                    return message.reply("No waiting applications.");
                } else {
                    console.log(rData);
                    rData.forEach(function (entry) {
                        message.channel.send(entry.username + " " + entry.application);
                    });

                }
            });
        } else if (args[0].toLowerCase() === "approve") {

            if (!args[1]) {
                return message.channel.send("You must supply a waiting users username.")
            }

            Database.getUserUser(args[1].toLowerCase(), function (res, rData) {
                uData.approved_by = message.member.user.tag;
                uData.username = args[1].toLowerCase();
                if (!res) {
                    return message.channel.send("User doesn't exist in the database.")
                }
                if (rData[0].approved !== 0) {
                    return message.channel.send("User has already been approved by " + rData[0].approved_by);
                }

                Rconn.addWhitelist(uData, function (res) {
                    if (res === true) {
                        Database.addWhitelist(uData, function (res) {
                            if (res === true) {
                                message.channel.send(uData.username + " has been approved by " + uData.approved_by);
                                client.fetchUser(rData[0].discord_id)
                                    .then(user => {
                                        user.send(uData.approved_by + " has added you to the whitelist for " + config.conn_address)
                                    }).catch(console.error);
                            }
                        })
                    }
                });


            });


        } else if (args[0].toLowerCase() === "remove") {
            if (!args[1]) {
                return message.channel.send("You must supply a whitelisted users minecraft name.")
            }
            if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
                return message.reply("Sorry, you don't have permissions to use this command!");
            }
            Database.getUserUser(args[1].toLowerCase(), function (res, rData) {
                uData.denied_by = message.member.user.tag;
                uData.username = args[1].toLowerCase();
                if (!res) {
                    return message.channel.send("User doesn't exist in the database.")
                }
                if (rData[0].approved !== 1) {
                    return message.channel.send("User has already been removed, or not added yet." + rData[0].denied_by);
                }

                Rconn.remWhitelist(uData, function (res) {
                    if (res === true) {
                        Database.remWhitelist(uData, function (res) {
                            if (res === true) {
                                message.channel.send(uData.username + " has been removed by " + uData.denied_by);
                                client.fetchUser(rData[0].discord_id)
                                    .then(user => {
                                        user.send(uData.denied_by + " has removed you from the whitelist for " + config.conn_address)
                                    })
                            }

                        })
                    }
                });


            });

        }

    }

};
