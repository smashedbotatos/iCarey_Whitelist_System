const config = require('../config.json');

module.exports = {
    name: 'prune',
    description: 'Removes messages from a channel, up to 100',
    usage: '',
    cooldown: 20,

    execute(message, args) {
        if (!message.member.roles.some(r => [config.admin_role_id, config.mod_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        // This command removes all messages from all users in the channel, up to 100.

        // get the delete count, as an actual number.
        const deleteCount = parseInt(args[0], 10);

        // Ooooh nice, combined conditions. <3
        if(!deleteCount || deleteCount < 2 || deleteCount > 100)
            return message.reply("Please provide a number between 2 and 100 for the number of messages to delete");

        // So we get our messages, and delete them. Simple enough, right?
        message.channel.fetchMessages({limit: deleteCount}).then((messages) => {
            message.channel.bulkDelete(messages).catch(error => console.log(error.stack));
        });

    }
};