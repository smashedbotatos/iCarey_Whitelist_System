<?php
/**
 * Footer file for the main site
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 */
?>


<!-- Footer
 ================================================== -->
<div class="container footer">
    <div class="row mt-4">
        <div class="col-sm-4">
            <h4 class="mb-4 icarey-orange-text"><strong><?php print_r($config['general']['sitename']); ?></strong></h4>
            <ul class="list-unstyled">
                <li class="icarey-orange-text"><?php print_r($config['minecraft']['version']); ?> <?php print_r($config['minecraft']['type']); ?>
                    Minecraft Server
                </li>
            </ul>
        </div>
        <div class="col-sm-4">
            <h4 class="mb-4 icarey-orange-text"><strong>Useful Links</strong></h4>
            <ul>
                <li><a class="icarey-orange-text" href="https://optifine.net/home" target="_blank">Optifine</a></li>
                <li><a class="icarey-orange-text" href="http://chunkbase.com/" target="_blank">Chunk Base</a></li>
                <li><a class="icarey-orange-text" href="http://minecraft.tools/en/" target="_blank">Minecraft Tools</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-4">
            <h4 class="mb-4 icarey-orange-text"><strong>Contact Us!</strong></h4>

            <p><a class="icarey-orange-text"
                  href="mailto:<?php $config['email']['primaryemail'] ?>?Subject=Web%20Inquiry" target="_top"><i
                            class="fas fa-envelope fa-1x mr-2 icarey-orange-text"></i><?php print_r($config['email']['primaryemail']); ?>
                </a></p>
            <p><a class="icarey-orange-text" href="https://discord.gg/<?php print_r($config['general']['discord']) ?>"
                  target="_blank"><i class="fab fa-discord fa-1x mr-2 icarey-orange-text"></i>Discord Chat</a></p>
            </ul>
        </div>
        <a class="icarey-orange-text" href="https://www.icarey.net">&nbsp;&copy;iCarey Computer Services
            2009-<?php echo date('Y'); ?></a>
    </div>
</div>
</div> <!-- Main Container Close -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>