const config = require('../config.json');

module.exports = {
    name: 'kick',
    description: 'Kicks a user from the discord server.',
    usage: '@username',
    cooldown: 5,
    execute(message, args) {

        if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        let member = message.mentions.members.first() || message.guild.members.get(args[0]);
        if (!member)
            return message.reply("Please mention a valid member of this server");
        if (!member.kickable)
            return message.reply("I cannot kick this user! Do they have a higher role? Do I have kick permissions?");


        let reason = args.slice(1).join(' ');
        if (!reason) reason = "No reason provided";

        member.kick(reason)
            .catch(error => message.reply(`Sorry ${message.author} I couldn't kick because of : ${error}`));
        message.reply(`${member.user.tag} has been kicked by ${message.author.tag} because: ${reason}`);
    }
};