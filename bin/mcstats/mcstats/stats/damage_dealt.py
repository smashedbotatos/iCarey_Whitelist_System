from mcstats import mcstats

mcstats.registry.append(
    mcstats.MinecraftStat(
        'damage_dealt',
        {
            'title': 'Berserk!',
            'desc': 'Damage dealt',
            'unit': 'int',
        },
        mcstats.StatReader(['minecraft:custom','minecraft:damage_dealt'])
    ))
