const config = require('../config.json');
const Canvas = require('canvas');
const Discord = require('discord.js');
const database = require('../lib/mysql.js');

module.exports = {
    name: 'elytra',
    description: 'Show list of players that have picked up an elytra.',
    usage: '',
    cooldown: 5,
    execute(message) {
        Database.getElytra(function (res,eData){
            if(res){
                eData.forEach(function(row){
                    message.channel.send(row.username + " has picked up " + row.elytra + " elytras.").catch(console.error)
                });
            } else {
                message.channel.send(rData);

            }

        });

        // Pass the entire Canvas object because you'll need to access its width, as well its context
        const applyText = (canvas, text) => {
            const ctx = canvas.getContext('2d');

            // Declare a base size of the font
            let fontSize = 70;

            do {
                // Assign the font to the context and decrement it so it can be measured again
                ctx.font = `${fontSize -= 10}px sans-serif`;
                // Compare pixel width of the text to the canvas minus the approximate avatar size
            } while (ctx.measureText(text).width > canvas.width - 300);

            // Return the result to use in the actual canvas
            return ctx.font;
        };
    },
};