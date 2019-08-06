//Import other mysql class
var main = require('../AlexBot.js');
const config = require('../config.json');



module.exports = {
    name: 'exempt',
    description: 'Exempts user from hack checks.',
    usage: '{add} {remove} {list} [username]',
    cooldown: 5,
    execute(message, args, client) {
        const data = [];

        if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }
        if (!args.length > 0 || args[0].toLowerCase() === 'help') {
            data.push('You must include an action and Minecraft Username.');
            data.push('Example: add Smashedbotatos');
            data.push('Example: remove Smashedbotatos');
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
        } else if(args[0] === 'list') {
            Database.getExemptUsers( function (euRes, users) {
                if (euRes) {
                    users.forEach(function(row){
                        message.channel.send(row.username + " is exempt!").catch(console.error)
                    });
                } else {
                    message.channel.send("No Exemptions Found ").catch(console.error)
                }

            });
        } else if(args[0] === 'add'){

            const data = [];

            data.username = args[1];

            Database.exemptUser(data, function (eRes) {

                if (eRes) {
                    message.channel.send(data.username + " was set to exempt from hack checks.")
                } else {
                    message.channel.send(data.username + " was NOT set to exempt form hack checks.")
                }

            });


        } else if(args[0] === 'remove'){
            const data = [];

            data.username = args[1];

            Database.unexemptUser(data, function (eRes) {

                if (eRes) {
                    message.channel.send(data.username + " was set to non-exempt from hack checks.")
                } else {
                    message.channel.send(data.username + " was NOT set to non-exempt from hack checks.")
                }

            });
        } else {
            message.channel.send(" Usage was incorrect, try !help exempt")
        }

    }};