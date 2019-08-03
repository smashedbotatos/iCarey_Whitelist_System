const { ShardingManager } = require('discord.js');
const { token } = require('./config.json');

const manager = new ShardingManager('./AlexBot.js', { token: token });

manager.spawn().catch();
manager.on('launch', shard => console.log(`Launched shard ${shard.id}`));