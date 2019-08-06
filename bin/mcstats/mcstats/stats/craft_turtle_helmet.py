from mcstats import mcstats

mcstats.registry.append(
    mcstats.MinecraftStat(
        'craft_turtle_helmet',
        {
            'title': 'Turtlehead',
            'desc': 'Turtle Helmets crafted',
            'unit': 'int',
        },
        mcstats.StatSumReader([
            mcstats.StatReader(['minecraft:crafted','minecraft:turtle_helmet']),
        ]),
        1467 # turtle helmets introduced in 18w07a
    ))
