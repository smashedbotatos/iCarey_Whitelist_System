<?php
/**
 * Logout function for the main site.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */
if (!isset($_SESSION['icarey']['email'])) :
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, '/');
    session_regenerate_id(true);
endif;

/** Voila! Here we shall gently nudge them somewhere else. */
header('Location:index.php');
exit();