const config = require('../config.json');
const fs = require("fs");
const database = require('../lib/mysql.js');
const rcon = require('../lib/rcon.js');

module.exports = {
    name: 'ban',
    description: 'Bans a user from the discord server.',
    usage: 'ban/pardon/appeal username',
    cooldown: 5,

    execute(message, args, client) {
        uData = [];
        let sender = message.author.username;
        const data = [];


        if (!args.length > 0 || args[0].toLowerCase() === 'help') {
            data.push('You must include an action and arguments.');
            data.push('Example: ban Smashedbotatos');
            data.push('Example: pardon Smashedbotatos');
            data.push('Example: appeal Smashedbotatos I didn\'t mean to use xray or dupe items. I have learned my lesson.');
            data.push('Example: list');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with whitelist instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        } else if (args[0].toLowerCase() === "ban") {

            if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
                return message.reply("Sorry, you don't have permissions to use this command!");
            }

            let reason = args.slice(2).join(' ');
            if (!reason) {
                reason = "No reason given";
            }

            uData.reason = reason;
            uData.username = args[1].toLowerCase();
            uData.banned_by = sender;
            uData.denied_by = sender;

            Database.getUserUser(uData.username, function (aRes, rData) {
                if (aRes) {
                    Database.banUser(uData, function (bRes) {
                        if (bRes) {
                            Rconn.banUser(uData, function (rcRes) {
                                if (rcRes) {
                                    Database.remWhitelist(uData, function (rRes) {
                                        if (rRes) {
                                            fs.unlink(config.statspath + rData[0].uuid + '.json', (err) => {
                                                if (err) {
                                                    console.log(err);
                                                }
                                                return client.channels.get(config.admin_channel_id).send(rData[0].username + " has been banned and removed from whitelist.");
                                            });
                                        } else {
                                            client.channels.get(config.admin_channel_id).send("Banned, but failed to remove " + rData[0].username + " from whitelist!");
                                        }
                                    })
                                } else {
                                    client.channels.get(config.admin_channel_id).send("Rcon failed, use the website to ban this user. " + config.site_url);
                                }

                            });

                        } else {
                            client.channels.get(config.admin_channel_id).send(rData[0].username + " has not been banned, please use the website. " + config.site_url);
                        }
                    });

                } else {
                    return message.reply("User not found or has not verified, please use " + config.site_url)
                }
            });
        } else if (args[0].toLowerCase() === "pardon") {
            if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
                return message.reply("Sorry, you don't have permissions to use this command!");
            }

            uData.username = args[1].toLowerCase();
            uData.approver = sender;

            Database.getUserUser(uData.username, function (aRes, rData) {
                if (aRes) {
                    Database.pardonUser(uData, function (bRes) {
                        if (bRes) {
                            Rconn.unbanUser(uData, function (rcRes) {
                                if (rcRes) {
                                    Database.addWhitelist(uData, function (rRes) {
                                        if (rRes) {
                                                return client.channels.get(config.admin_channel_id).send(rData[0].username + " has been pardoned and add back to the whitelist.");
                                        } else {
                                            client.channels.get(config.admin_channel_id).send("Pardoned, but failed to add " + rData[0].username + " to whitelist!");
                                        }
                                    })
                                } else {
                                    client.channels.get(config.admin_channel_id).send("Rcon failed, use the website to pardon this user. " + config.site_url);
                                }

                            });

                        } else {
                            client.channels.get(config.admin_channel_id).send(rData[0].username + " has not been pardoned, please use the website. " + config.site_url);
                        }
                    });

                } else {
                    return message.reply("User not found or is not banned, please use " + config.site_url)
                }
            });

        } else if (args[0].toLowerCase() === "appeal") {
            let reason = args.slice(2).join(' ');
            if (!reason) {
                reason = "No reason given";
            }
            uData.username = args[1].toLowerCase();
            uData.discord_name = message.member.user.tag;
            uData.discord_id = message.member.user.id;
            uData.role = config.default_role;
            uData.appeal = reason;

            Database.getUserUser(uData, function (uRes, rData) {
                if(uRes){
                    if(rData[0].discord_id !== uData.discord_id){
                        return message.channel.send("You may only appeal for a player from that players verified Discord Account")
                    }
                } else {
                    Database.banAppeal(uData, function (aRes) {
                        if(aRes){
                            return message.channel.send("Appeal has been submitted for " + uData.username);
                        }

                    })

                }

            })

        } else if (args[0].toLowerCase() === "list") {
            if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
                return message.reply("Sorry, you don't have permissions to use this command!");
            }
            Database.getAppeal(function (res, rData) {
                if (!res) {
                    return message.reply("No waiting appeals.");
                } else {
                    console.log(rData);
                    rData.forEach(function (entry) {
                        message.channel.send(entry.username + " " + entry.appeal);
                    });
                }
            });

        }
    }
};