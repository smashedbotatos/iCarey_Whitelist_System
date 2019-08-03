mcstats.showPlayer = function(uuid) {
    loadJson('data/playerdata/' + uuid + '.json', function(stats) {
        var player = mcstats.players[uuid];
        var tbody = '';

        // list statistics
        mcstats.awardKeysByTitle.forEach(function(id) {
            var stat = stats[id];

            var award = mcstats.awards[id];
            var awardWidget = mcstats.awardWidget(id);
            var value = mcstats.formatValue(stat ? stat.value : 0, award.unit);
            var rankWidget = stat ? mcstats.rankWidget(stat.rank) : '';

            tbody += `
                <tr>
                    <td class="text-right">${rankWidget}</td>
                    <td>${awardWidget}</td>
                    <td>
                        <span class="text-muted">${award.desc}:</span>&nbsp;
                        <span class="text-data">${value}</span>
                    </td>
                </tr>
            `;
        });

        mcstats.viewContent.html(`
            <div class="mcstats-entry p-1">
            <div class="round-box p-1">
                <table class="table table-responsive-xs table-hover table-sm">
                <thead>
                    <th scope="col" class="text-right text-shadow">Rank</th>
                    <th scope="col" class="text-shadow">Award</th>
                    <th scope="col" class="text-shadow">Score</th>
                </thead>
                <tbody>${tbody}</tbody>
                </table>
            </div>
            </div>
        `);

        // show
        mcstats.showView(
            mcstats.playerWidget(uuid, 'textw texth align-baseline mr-2', false),
            'Player Statistics',
            'Last played: ' + mcstats.lastOnlineWidget(player.last),
            false);
    });
};
