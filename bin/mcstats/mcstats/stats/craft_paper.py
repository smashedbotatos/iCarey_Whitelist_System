from mcstats import mcstats

mcstats.registry.append(
    mcstats.MinecraftStat(
        'craft_paper',
        {
            'title': 'Paperboy',
            'desc': 'Paper produced',
            'unit': 'int',
        },
        mcstats.StatReader(['minecraft:crafted','minecraft:paper'])
    ))
