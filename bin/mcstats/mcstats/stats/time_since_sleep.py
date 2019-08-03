from mcstats import mcstats

mcstats.registry.append(
    mcstats.MinecraftStat(
        'time_since_sleep',
        {
            'title': 'Insomnia',
            'desc': 'Time since last sleep',
            'unit': 'ticks',
        },
        mcstats.StatReader(['minecraft:custom','minecraft:time_since_rest'])
    ))
