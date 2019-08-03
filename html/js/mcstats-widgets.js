// Number formatter
intlFormat = new Intl.NumberFormat('en');

formatFloat = function (value) {
    return (value != parseInt(value)) ? value.toFixed(1) : value;
};

formatTime = function (unixTime) {
    var date = new Date();
    date.setTime(unixTime * 1000);

    return date.toLocaleDateString('en-US', {day: 'numeric', month: 'short', year: 'numeric'}) +
        ' - ' +
        date.toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit', hour12: false});
};

// Format an award value
mcstats.formatValue = function (value, unit) {
    switch (unit) {
        case 'cm':
            if (value >= 100000) {
                value /= 100000;
                unit = 'km';
            } else if (value >= 100) {
                value /= 100;
                unit = 'm';
            }

            value = formatFloat(value) + unit;
            break;

        case 'ticks':
            seconds = value / 20; // ticks per second
            value = '';
            var higher = false;

            if (seconds > 86400) {
                value += Math.floor(seconds / 86400) + 'd ';
                seconds %= 86400;
                higher = true;
            }

            if (higher || seconds > 3600) {
                value += Math.floor(seconds / 3600) + 'h ';
                seconds %= 3600;
                higher = true;
            }

            if (higher || seconds > 60) {
                value += Math.floor(seconds / 60) + 'min ';
                seconds %= 60;
            }

            value += Math.floor(seconds) + 's';
            break;

        case 'int':
            value = intlFormat.format(parseInt(value));
            break;

        default:
            value = '' + value + ' ' + unit;
            break;
    }

    return `<span class="text-data">${value}</span>`;
};

// Award types
mcstats.awardType = {
    medal: {title: 'Medal', imgPrefix: 'fatcow/medal_award_'},
    crown: {title: 'Crown', imgPrefix: 'fatcow/crown_'},
};

// Create a rank widget
mcstats.rankWidget = function (rank, type = 'medal') {
    var awardType = mcstats.awardType[type];
    if (rank) {
        var widget = `<span class="rank rank-${rank}">#${rank}</span>`;
        var medal, medalTitle;
        switch (rank) {
            case 1:
                // gold
                medal = 'gold';
                medalTitle = 'Gold';
                break;

            case 2:
                // silver
                medal = 'silver';
                medalTitle = 'Silver';
                break;

            case 3:
                // bronze
                medal = 'bronze';
                medalTitle = 'Bronze';
                break;

            default:
                medal = false;
        }

        if (medal) {
            widget = `
                <img class="img-textsize-1_5 mr-1 align-top" title="${medalTitle} ${awardType.title}" src="img/${awardType.imgPrefix}${medal}.png"/>
            ` + widget;
        }
    } else {
        widget = `<span class="rank">-</span>`;
    }
    return widget;
};

// Test whether a timestamp is within the "active" threshold
mcstats.isActive = function (last) {
    var daysSinceLast = (mcstats.info.updateTime - last) / 86400;
    return (daysSinceLast <= mcstats.info.inactiveDays);
};

// Create a widget showing a player's last online time and activity
mcstats.lastOnlineWidget = function (last) {
    var fmt = formatTime(last);
    if (mcstats.isActive(last)) {
        return `<span class="text-success">${fmt}</span>`;
    } else {
        return `
            <span class="text-danger">${fmt}</span>
        `;
    }
};

// Create an award widget
mcstats.awardWidget = function (id) {
    var award = mcstats.awards[id];
    return `
        <img class="img-pixelated img-textsize-1_5 align-baseline" src="img/award-icons/${id}.png" alt="${id}" title="${award.title}"/>
        <a href="#award:${id}">${award.title}</a>
    `;
};

// Get a player face widget
function drawFace(img) {
    var canvas = $(img).parent('canvas')[0];
    var ctx = canvas.getContext('2d');
    ctx.imageSmoothingEnabled = false;
    ctx.mozImageSmoothingEnabled = false;
    ctx.drawImage(img, 8, 8, 8, 8, 0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 40, 8, 8, 8, 0, 0, canvas.width, canvas.height);
}

mcstats.faceWidget = function (skinUrl, css = '') {
    return `
        <canvas width="8" height="8" class="minecraft-face d-inline-block img-pixelated ${css}">
            <img class="d-none" src="${skinUrl}" onload="drawFace(this);"/>
        </canvas>`;
};

// Create a player widget
mcstats.playerWidget = function (uuid, skinCss = 'textw-1_5 texth-1_5 align-baseline mr-1', asLink = true) {
    if (uuid) {
        var p = mcstats.players[uuid];

        // get player's skin
        if (p['skin']) {
            // compile skin URL
            skin = 'https://textures.minecraft.net/texture/' + p['skin'];
        } else {
            // default skin - find out whether it's Steve or Alex
            var even = parseInt(uuid[7], 16) ^
                parseInt(uuid[15], 16) ^
                parseInt(uuid[23], 16) ^
                parseInt(uuid[31], 16);

            skin = 'img/skins/' + (even ? 'alex' : 'steve') + '.png';
        }

        return mcstats.faceWidget(skin, skinCss) +
            (asLink ? `<a href="#player:${uuid}">${p.name}</a>` : p.name);
    } else {
        return `<span class="text-muted">(nobody)</span>`;
    }
};
