const config = require('../config.json');
const Canvas = require('canvas');
const Discord = require('discord.js');
const database = require('../lib/mysql.js');

module.exports = {
    name: 'ender',
    description: 'Lists how many times the Ender Dragon has been killed.',
    usage: '',
    cooldown: 5,
    execute(message) {
        Database.getEnderkills(function (res,rData){
            if(res){
                const canvas = Canvas.createCanvas(700, 250);
                let ctx    = canvas.getContext('2d');
                let background    = "./assets/backgrounds/bedrock.png";
                let icon = "./assets/icons/enderdragon.png";
                let kills = "Ender Dragon Kills: " + rData;


                Canvas.loadImage(background).then((image) => {
                    ctx.fillStyle = ctx.createPattern(image, "repeat");
                    ctx.fillRect(0, 0, 700, 250);
                    Canvas.loadImage(icon).then((icon) => {
                        ctx.drawImage(icon, 25, 0, 200, canvas.height);
                        ctx.strokeStyle = '#74037b';
                        ctx.strokeRect(0, 0, canvas.width, canvas.height);
                        // Select the style that will be used to fill the text in
                        ctx.fillStyle = '#ffffff';
                        // Actually fill the text with a solid color
                        ctx.font = applyText(canvas, kills);
                        ctx.fillText(kills, canvas.width / 2.5, canvas.height / 1.8);
                        const attachment = new Discord.Attachment(canvas.toBuffer(), 'ender-image.png');
                        message.channel.send(attachment);

                    });

                });

            } else {
                const canvas = Canvas.createCanvas(700, 250);
                let ctx    = canvas.getContext('2d');
                let background    = "./assets/backgrounds/bedrock.png";
                let icon = "./assets/icons/enderdragon.png";
                let kills = "Ender Dragon Kills: " + rData;


                Canvas.loadImage(background).then((image) => {
                    ctx.fillStyle = ctx.createPattern(image, "repeat");
                    ctx.fillRect(0, 0, 700, 250);
                    Canvas.loadImage(icon).then((icon) => {
                        ctx.drawImage(icon, 25, 0, 200, canvas.height);
                        ctx.strokeStyle = '#74037b';
                        ctx.lineWidth = 4;
                        ctx.strokeRect(0, 0, canvas.width, canvas.height);
                        // Select the style that will be used to fill the text in
                        ctx.fillStyle = '#ffffff';
                        // Actually fill the text with a solid color
                        ctx.font = applyText(canvas, kills);
                        ctx.fillText(kills, canvas.width / 2.5, canvas.height / 1.8);
                        const attachment = new Discord.Attachment(canvas.toBuffer(), 'ender-image.png');
                        message.channel.send(attachment);

                    });

                });
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