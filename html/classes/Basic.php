<?php
/**
 * Basic PHP Class - Includes basic functions.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2018 iCarey Computer Services.
 */


include_once('DatabaseConnect.php');


class Basic extends DatabaseConnect
{

    public static function getPlayerBody($uuid)
    {
        $skinurl = "https://crafatar.com/renders/body/$uuid/?size=100&overlay";
        $getimage = file_put_contents("img/skins/body/$uuid.png", fopen("$skinurl", 'r'));
        if (empty($getimage)) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function getPlayerHead($uuid)
    {
        $skinurl = "https://crafatar.com/avatars/$uuid/?size=100&overlay";
        $getimage = file_put_contents("img/skins/head/$uuid.png", fopen("$skinurl", 'r'));
        if (empty($getimage)) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function updateGroup($uuid, $group)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $database->query("UPDATE website_users SET role = '$group' WHERE '$uuid' = uuid");
        if ($database->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getPlayername($uuid)
    {
        global $config;
        $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
        $username = $database->query("SELECT * FROM website_users WHERE '$uuid' = uuid");
        $nameresult = $database->fetch($username);
        if ($database->affected_rows() > 0) {
            return $nameresult['username'];
        } else {
            return 0;
        }
    }

    public static function getPlayerstats($uuid)
    {
        global $config;
        $worldpath = $config['minecraft']['worldpath'];
        $statspath = "/stats/$uuid.json";

        if (file_exists($worldpath . $statspath)) {
            $statString = file_get_contents($worldpath . $statspath);
            $statsJson = json_decode($statString, true);
            return $statsJson['stats'];
        } else {
            return 0;
        }
    }
}