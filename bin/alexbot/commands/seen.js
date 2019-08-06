const config = require('../config.json');
const fs = require("fs");
const Discord = require('discord.js');
const database = require('../lib/mysql.js');
const time = require('../lib/time.js');

module.exports = {
    name: 'seen',
    description: 'Check the last time a user logged in.',
    usage: '{seen} [username]',
    cooldown: 5,

    execute(message, args, client) {
        const data = [];

        if (!args.length > 0 || args[0].toLowerCase() === 'help') {
            data.push('Command Usage');
            data.push('Example: !seen Smashedbotatos');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with update instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        } else if (args[0].length > 0 || args[0].toLowerCase() !== 'help') {
           let username = args[0].toLowerCase();

            Database.getUserUser(username, function (aRes, uData) {
                if (aRes) {
                    statsfile = config.statspath + uData[0].uuid + ".json";
                    fs.stat(statsfile, 'utf8', function (err, data) {
                        if (err) {
                            console.log('Error reading stats file: ' + err);
                        } else {
                            let current = new Date().getTime() / 1000;
                            let lastModified = data.mtimeMs / 1000;
                            let seenseconds = current - lastModified;

                            Time.secondsToReadable(seenseconds, function (day, hr, min) {
                                timeReadable = day + ' Days ' + hr + ' Hours ' + min + ' Minutes ago.';
                            });

                            let messageBody = new Discord.RichEmbed();
                            messageBody.title = "[ " + uData[0].username + " ]";
                            messageBody.color = 0xFF6600;
                            messageBody.setThumbnail('https://mc.icarey.net/img/skins/body/' + uData[0].uuid + '.png');
                            messageBody.addField("Last Seen: ", timeReadable);
                            message.channel.send(messageBody)

                        }

                    });
                } else {
                    message.channel.send("User not found.")
                }
            });
        } else {
            message.channel.send("Try the help command. !seen help")
        }
    }

};