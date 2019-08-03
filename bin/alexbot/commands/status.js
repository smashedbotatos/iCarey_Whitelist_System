const ms = require('../lib/mcquery.js');
const config = require('../config.json');

module.exports = {
    name: 'status',
    description: 'Server status',
    usage: '',
    cooldown: 5,

    execute(message) {

        if (!message.member.roles.some(r => [config.member_role_id, config.trusted_role_id, config.mod_role_id, config.admin_role_id, config.verified_role_id].includes(r.id))) {
            return message.reply("Sorry, you don't have permissions to use this command!");
        }

        // For use with Node.js

        ms.init(config.query_host, config.query_port, function(result)
        {

            if(ms.online)
            {
                console.log(ms);
                const embed = {
                    title: 'Server Status',
                    description: 'Online',
                    color: 0xff8c00,
                    image: {
                        url: config.icon_url,
                    },
                    fields: [
                        {
                            name: 'Players',
                            value: ms.current_players + ' of ' + ms.max_players,
                        },
                        {
                            name: 'Version',
                            value: ms.version,
                        },
                        {
                            name: 'Connect Address',
                            value: config.conn_address,
                            inline: true,
                        },
                    ],
                };
                message.channel.send({ embed: embed })
            }
            else
            {
                message.channel.send('Server is Offline');
            }
        });

    }
};