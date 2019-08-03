<?php
/**
 * Admin Functions Class
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2018 iCarey Computer Services.
 *
 */


$config = parse_ini_file('includes/config.ini.php', 1, true);
include_once('rconConnect.php');
include_once('DatabaseConnect.php');
require_once('MojangAPI.php');
require_once('mail.php');

class adminFunctions extends DatabaseConnect
{

    public static function whitelistApprove($id, $username, $email, $admin)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $resultapprove = $database->query("SELECT * FROM website_users WHERE `id`='$id' LIMIT 1");
        if ($database->num_rows($resultapprove) == 1) {
            $database->query("UPDATE website_users SET approved = '1', denied = '0', denied_by = '0', denial_reason = '0', approved_by = '$admin' WHERE `id`='$id' LIMIT 1");
            if ($database->affected_rows() > 0) {
                $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
                if ($r->Auth()) {
                    $r->rconCommand("whitelist add $username");
                    $r->rconCommand("whitelist reload");
                    $r->rconCommand("say Added $username to whitelist!");

                    $sendmail = mail::sendApprove($email, $username, $admin);
                    if (!$sendmail) {
                        return 0;
                    } else {
                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }

    public static function whitelistDeny($id, $username, $email, $admin, $reason)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $resultdeny = $database->query("SELECT * FROM website_users WHERE `id`='$id' LIMIT 1");
        if ($database->num_rows($resultdeny) == 1) {
            $database->query("UPDATE website_users SET approved = '0', denied = '1', denied_by = '$admin', denial_reason = '$reason' WHERE `id`='$id' LIMIT 1");
            if ($database->affected_rows() > 0) {
                $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
                if ($r->Auth()) {
                    $r->rconCommand("whitelist remove $username");
                    $r->rconCommand("whitelist reload");
                    $r->rconCommand("say Removed $username to whitelist!");
                    $sendmail = mail::sendDeny($email, $username, $admin, $reason);
                    if (!$sendmail) {
                        return 0;
                    } else {
                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
        return 0;
    }

    public static function banUser($id, $username, $email, $admin, $reason)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $resultban = $database->query("SELECT * FROM website_users WHERE `id`='$id' LIMIT 1");
        if ($database->num_rows($resultban) == 1) {
            $database->query("UPDATE website_users SET denied = '1', denied_by = '$admin', banned = '1', banned_by = '$admin', ban_reason = '$reason' WHERE `id`='$id' LIMIT 1");
            if ($database->affected_rows() > 0) {
                $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
                if ($r->Auth()) {
                    $r->rconCommand("ban $username $reason");

                    $r->rconCommand("say $admin banned $username for $reason");
                    $sendmail = mail::sendBan($email, $username, $admin, $reason);
                    if (!$sendmail) {
                        return 0;
                    } else {
                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
        return 0;

    }

    public static function unBan($id, $username, $email, $admin)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $resultban = $database->query("SELECT * FROM website_users WHERE `id`='$id' LIMIT 1");
        if ($database->num_rows($resultban) == 1) {
            $database->query("UPDATE website_users SET denied = '0', denied_by = '0', banned = '0', banned_by = '0', ban_reason = '0' WHERE `id`='$id' LIMIT 1");
            if ($database->affected_rows() > 0) {
                $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
                if ($r->Auth()) {
                    $r->rconCommand("pardon $username");

                    $r->rconCommand("say $admin unbanned $username");
                    $sendmail = mail::sendunBan($email, $username, $admin);
                    if (!$sendmail) {
                        return 0;
                    } else {
                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
        return 0;

    }

    public static function changeGroup($username, $group)
    {
        global $config;
        $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
        if ($r->Auth()) {
            $r->rconCommand("lp user $username parent set $group");
        } else {
            return 0;
        }
    }


    public static function sendCmd($command)
    {
        global $config;

        $r = new rconConnect($config['minecraft']['host'], $config['minecraft']['rport'], $config['minecraft']['rpass']);
        if ($r->Auth()) {
            $r->rconCommand("$command");
            return 1;
        } else {
            return 0;
        }

    }

}