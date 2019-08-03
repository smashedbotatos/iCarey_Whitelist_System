/**
 * A discord bot, with examples of connecting to mysql and reading .json files.
 */

//Import NODE.JS libraries
// Import discord.js
const Discord = require('discord.js');
// Import FileSystem library
const fs = require("fs");
const glob = require("glob");
const path = require("path");

//Import our libraries
const database = require('./lib/mysql.js');
const mojang = require('./lib/mojang.js');
const time = require('./lib/time.js');
const hackcheck = require('./lib/hackchecks.js');
const rconn = require('./lib/rcon.js');
const stats = require("./lib/stats.js");


// Import our Config file
const config = require('./config.json');


uData = [];

// Create an instance of a Discord client
const client = new Discord.Client();
client.commands = new Discord.Collection();

const commandFiles = fs.readdirSync('./commands').filter(file => file.endsWith('.js'));

for (const file of commandFiles) {
    const command = require(`./commands/${file}`);
    client.commands.set(command.name, command);
}

const cooldowns = new Discord.Collection();

client.once('ready', () => {
    client.user.setActivity('Minecraft', { type: 'PLAYING' })
        .then(presence => console.log(`Activity set to ${presence.game ? presence.game.name : 'none'}`))
        .catch(console.error);
    client.user.setStatus('online')
        .then(console.log)
        .catch(console.error);
    console.log('Ready!');
});

client.on('guildMemberAdd', message => { // Commands Go Inside The client.on('message',
    message.guild.channels.get(config.default_channel_id).send(config.botwelcome);
    });

client.on('guildMemberAdd', member => {
    member.setRoles([config.default_role_id]).catch(console.error);
});

client.on('message', message => {
    if (!message.content.startsWith(config.prefix) || message.author.bot) return;

    const args = message.content.slice(config.prefix.length).split(/ +/);
    const commandName = args.shift().toLowerCase();

    const command = client.commands.get(commandName)
        || client.commands.find(cmd => cmd.aliases && cmd.aliases.includes(commandName));

    if (!command){
        return message.reply('404 Command not found.')
    }

    if (command.guildOnly && message.channel.type !== 'text') {
        return message.reply('I can\'t execute that command inside DMs!');
    }

    if (command.args && !args.length) {
        let reply = `You didn't provide any arguments, ${message.author}!`;

        if (command.usage) {
            reply += `\nThe proper usage would be: \`${config.prefix}${command.name} ${command.usage}\``;
        }

        return message.channel.send(reply);
    }

    if (!cooldowns.has(command.name)) {
        cooldowns.set(command.name, new Discord.Collection());
    }

    const now = Date.now();
    const timestamps = cooldowns.get(command.name);
    const cooldownAmount = (command.cooldown || 3) * 1000;

    if (timestamps.has(message.author.id)) {
        const expirationTime = timestamps.get(message.author.id) + cooldownAmount;

        if (now < expirationTime) {
            const timeLeft = (expirationTime - now) / 1000;
            return message.reply(`please wait ${timeLeft.toFixed(1)} more second(s) before reusing the \`${command.name}\` command.`);
        }
    }

    timestamps.set(message.author.id, now);
    setTimeout(() => timestamps.delete(message.author.id), cooldownAmount);

    try {
        command.execute(message, args, client);
    } catch (error) {
        console.error(error);
        message.reply('there was an error trying to execute that command!');
    }
});

setInterval(function() {
    glob(config.statspath + "*.json", function (err, files) {
        if (err) {
            console.log("cannot read the folder, something goes wrong with glob", err);
        }
        files.forEach(function (file) {
            fs.readFile(file, 'utf8', function (err, data) { // Read each file
                if (err) {
                    console.log("cannot read the file, something goes wrong with the file", err);
                }
                let iStats = JSON.parse(data);

                Database.getUserUuid(path.basename(file, path.extname(file)), function (uRes, uData) {
                    if (uRes) {
                        if (uData[0].hackcheck === 0) {
                            Hackcheck.Xray(iStats, function (xRes, averageDia) {
                                if (xRes) {
                                    console.log(uData[0].username, " " + averageDia);
                                    client.channels.get(config.admin_channel_id).send(":fire: " + uData[0].username + " " + averageDia + " Check user for x-ray!!");
                                } else {

                                }
                            });
                            Hackcheck.Grief(iStats, function (gRes, averageFire) {
                                if (gRes) {
                                    console.log(uData[0].username, " " + averageFire + "Fire Average");
                                    client.channels.get(config.admin_channel_id).send(":fire: " + uData[0].username + " " + averageFire + " Check user for griefing!!");
                                } else {

                                }
                            });

                            Hackcheck.Tnt(iStats, function (tRes, averageTnt) {
                                if (tRes) {

                                    client.channels.get(config.admin_channel_id).send(":fire: " + uData[0].username + " " + averageTnt + " Check user for TNT griefing!!");
                                } else {

                                }
                            });
                            Hackcheck.stealing(iStats, function (eRes, averageChests) {
                                if (eRes) {
                                    client.channels.get(config.admin_channel_id).send(":fire: " + uData[0].username + " has broken too many chests. " + averageChests + " Check user for griefing!!");
                                } else {

                                }
                            });

                        } else {

                        }
                    } else {

                    }
                })
            });
        });
    });

}, 3000 * 100); // 3000 * 100 milsec = 5min

setInterval(function() {
    Stats.updateStats( function (sRes) {
        if(sRes){
            console.log('Stats Updated')
        } else {
            console.log('Stats Update Failed')
        }

    })

}, 9000 * 100); // 9000 * 100 milsec = 15min

setInterval(function() {
    glob(config.statspath + "*.json", function (err, files) {
        if (err) {
            console.log("cannot read the folder, something goes wrong with glob", err);
        }
        files.forEach(function (file) {
            fs.readFile(file, 'utf8', function (err, data) { // Read each file
                if (err) {
                    console.log("cannot read the file, something goes wrong with the file", err);
                }
                let iStats = JSON.parse(data);
                const uuid_from_file = [];
                uuid_from_file.uuid = path.basename(file, path.extname(file));

                    Database.getUserUuid(uuid_from_file, function (uRes, rData) {
                        if (uRes && rData[0].role === config.member_role) {
                            if (iStats['stats']['minecraft:custom']['minecraft:play_one_minute'] >= config.promote_time_seconds*20) {
                                uData.role = config.trusted_role;
                                uData.uuid = rData[0].uuid;
                                uData.username = rData[0].username;

                                Database.updateGroup(uData, function (cRes) {
                                    if (cRes) {
                                        if(config.luck_perms.toLowerCase() === "true") {
                                            Rconn.updateGroup(uData, function (rRes) {
                                                if (rRes) {
                                                    if (rData[0].discord_id !== 0) {
                                                        client.guilds.get(config.guild_id).members.get(rData[0].discord_id).setRoles([config.trusted_role_id, config.verified_role_id]).catch(console.error);
                                                    } else {
                                                        console.log("User " + rData[0].username + " has not linked account to Discord.")
                                                    }
                                                } else {
                                                    console.log("Rcon group updated failed. Check Rcon Settings");
                                                }
                                            })

                                        } else if(config.luck_perms.toLowerCase() === "false") {
                                            if (rData[0].discord_id !== 0) {
                                                client.guilds.get(config.guild_id).members.get(rData[0].discord_id).setRoles([config.trusted_role_id, config.verified_role_id]).catch(console.error);
                                                console.log("Updated " + rData[0].username + "'s Discord group");
                                            }
                                        } else{
                                            console.log('Luckperms option in config unreadable.');
                                        }
                                    } else {
                                        console.log("Failed to update users group in Database! " + rData[0].username);
                                    }
                                })
                            }
                        }
                    });
            });

        });
    });

}, 18000 * 100); // 18000 * 100 milsec = 30min

client.login(config.token)
    .catch(console.error);