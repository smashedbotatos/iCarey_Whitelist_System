//Import other mysql class

var main = require('../AlexBot.js');
const config = require('../config.json');
const Discord = require('discord.js');


module.exports = {
    name: 'verify',
    description: 'Verifies a user with our Website',
    aliases: [''],
    usage: '[@username][verification code]',
    cooldown: 5,
    execute(message, args, client) {

        let member = message.mentions.members.first();

        const uData = [];

        if (args.length !== 2) {
            data.push('You must supply your verification code.');
            data.push('Example: !verify @Smashedbotatos e4cc6adv82312c8aa730ab286cae2533 ');

            return message.author.send(data, {split: true})
                .then(() => {
                    if (message.channel.type === 'dm') return;
                    message.reply('I\'ve sent you a DM with verification instructions.');
                })
                .catch(error => {
                    console.error(`Could not send verify DM to ${message.author.tag}.\n`, error);
                    message.reply('it seems like I can\'t DM you!');
                });
        }

        uData.verification = args[1].toLowerCase();
        uData.discord_id = message.mentions.members.first().id;
        let username = getUserFromMention(args[0]);
        uData.discord_username = username.username + '#' + username.discriminator;


        if (message.mentions.members.first().id !== message.author.id && !message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to verify another user.");
        }


        Database.verifyUser(uData, function (res, rData) {
            console.log(res);
            if (res) {
                if (rData[0].role === config.admin_role) {
                    member.setRoles([config.admin_role_id, config.verified_role_id]).catch(console.error);
                } else if (rData[0].role === config.mod_role) {
                    member.setRoles([config.mod_role_id, config.verified_role_id]).catch(console.error);
                } else if (rData[0].role === config.donor_role) {
                    member.setRoles([config.donor_role_id, config.verified_role_id]).catch(console.error);
                } else if (rData[0].role === config.trusted_role) {
                    member.setRoles([config.trusted_role_id, config.verified_role_id]).catch(console.error);
                } else if (rData[0].role === config.member_role) {
                    member.setRoles([config.member_role_id, config.verified_role_id]).catch(console.error);
                } else {
                    member.setRoles([config.member_role_id, config.verified_role_id]).catch(console.error);
                }

                member.setNickname(rData[0].username).catch(console.error);

                message.channel.send(uData.discord_username + ' has linked their Minecraft account to ' + rData[0].username);
            } else {
                message.channel.send('Please check your verification code.');
            }
        });

        function getUserFromMention(mention) {
            if (!mention) return;

            if (mention.startsWith('<@') && mention.endsWith('>')) {
                mention = mention.slice(2, -1);

                if (mention.startsWith('!')) {
                    mention = mention.slice(1);
                }

                return client.users.get(mention);
            }

        }
    }

};