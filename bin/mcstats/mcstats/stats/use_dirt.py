from mcstats import mcstats

mcstats.registry.append(
    mcstats.MinecraftStat(
        'use_dirt',
        {
            'title': 'Dirtbag',
            'desc': 'Dirt blocks placed',
            'unit': 'int',
        },
        mcstats.StatReader(['minecraft:used','minecraft:dirt'])
    ))
