//Import other mysql class
var main = require('../AlexBot.js');
const config = require('../config.json');



module.exports = {
    name: 'exempt',
    description: 'Exempts user from hack checks.',
    usage: '[username][list]',
    cooldown: 5,
    execute(message, args, client) {

        if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        if(args[0] === 'list') {
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

        }


    }};