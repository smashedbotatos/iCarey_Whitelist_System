const config = require('../config.json');

// Import FileSystem library
const rcon = require('rcon-srcds');

Rconn = {};

Rconn.addWhitelist = function (uData, cb) {
    const server = new rcon({
        host: config.rcon_host,
        port: config.rcon_port,
        maximumPacketSize: 0,
        encoding: 'ascii',
        timeout: 1000
    });

    server.authenticate(config.rcon_pass)
        .then(() => {
            console.log('Authenticated');
            server.execute('whitelist add ' + uData.username);
            server.execute('whitelist reload');
            cb(true)
        })
        .catch(() => {
            console.log('Connection Failed');
            cb(false);
        })
};

Rconn.remWhitelist = function (uData, cb) {
    const server = new rcon({
        host: config.rcon_host,
        port: config.rcon_port,
        maximumPacketSize: 0,
        encoding: 'ascii',
        timeout: 1000
    });

    server.authenticate(config.rcon_pass)
        .then(() => {
            console.log('Authenticated');
            server.execute('whitelist remove ' + uData.username);
            server.execute('whitelist reload');
            cb(true)
        })
        .catch(() => {
            console.log('Connection Failed');
            cb(false);
        })
};

Rconn.banUser = function (uData, cb) {
    const server = new rcon({
        host: config.rcon_host,
        port: config.rcon_port,
        maximumPacketSize: 0,
        encoding: 'ascii',
        timeout: 1000
    });

    server.authenticate(config.rcon_pass)
        .then(() => {
            console.log('Authenticated');
            server.execute('ban ' + uData.username + " " + uData.reason);
            cb(true)
        })
        .catch(() => {
            console.log('Connection Failed');
            cb(false);
        })
};

Rconn.unbanUser = function (uData, cb) {
    const server = new rcon({
        host: config.rcon_host,
        port: config.rcon_port,
        maximumPacketSize: 0,
        encoding: 'ascii',
        timeout: 1000
    });

    server.authenticate(config.rcon_pass)
        .then(() => {
            console.log('Authenticated');
            server.execute('pardon ' + uData.username);
            cb(true)
        })
        .catch(() => {
            console.log('Connection Failed');
            cb(false);
        })
};
Rconn.updateGroup = function (uData, cb) {
    const server = new rcon({
        host: config.rcon_host,
        port: config.rcon_port,
        maximumPacketSize: 0,
        encoding: 'ascii',
        timeout: 1000
    });

    server.authenticate(config.rcon_pass)
        .then(() => {
            console.log('Authenticated');
            server.execute('lp user ' + uData.username + ' parent set ' + config.trusted_role);
            server.execute('tellraw @a {"text":"' + uData.username + ' is now a Trusted Member!!","color":"green"}');
            cb(true)
        })
        .catch(() => {
            console.log('Connection Failed');
            cb(false);
        })
};