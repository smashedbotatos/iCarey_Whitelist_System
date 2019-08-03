const mojang = require('../lib/mojang.js');

module.exports = {
    name: 'test',
    description: 'tests a command',
    usage: 'uuid',
    cooldown: 0,
    execute(message, args) {
        if(!args[0]){
        } else {
            Mojang.getBody(args[0], function (res){
            if(!res){
                message.reply("This function didn't work as expected")
            } else {
                message.channel.send("Downloaded Body for " + args[0])
            }
        });
            Mojang.getHead(args[0], function (res){
                if(!res){
                    message.reply("This function didn't work as expected")
                } else {
                    message.channel.send("Downloaded Head for " + args[0])
                }
            })
        }
    }
};
