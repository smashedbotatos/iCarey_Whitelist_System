<?php
/**
 * Rcon Connection PHP Class
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2018 iCarey Computer Services.
 */

define("SERVERDATA_EXECCOMMAND", 2);
define("SERVERDATA_AUTH", 3);

class rconConnect
{
    var $Password;
    var $Host;
    var $Port = 27015;
    var $_Sock = null;
    var $_Id = 0;

    public function __construct($Host, $Port, $Password)
    {
        $this->Password = $Password;
        $this->Host = $Host;
        $this->Port = $Port;
        $this->_Sock = @fsockopen($this->Host, $this->Port, $errno, $errstr, 30) or
        die("Unable to open socket: $errstr ($errno)\n");
        $this->_Set_Timeout($this->_Sock, 2, 500);
    }

    function _Set_Timeout(&$res, $s, $m = 0)
    {
        if (version_compare(phpversion(), '4.3.0', '<')) {
            return socket_set_timeout($res, $s, $m);
        }
        return stream_set_timeout($res, $s, $m);
    }

    function Auth()
    {
        $PackID = $this->_Write(SERVERDATA_AUTH, $this->Password);

        // Real response (id: -1 = failure)
        $ret = $this->_PacketRead();
        //var_dump($ret);
        if ($ret[0]['ID'] == -1) {
            return false;
        }
        return true;
    }

    function _Write($cmd, $s1 = '', $s2 = '')
    {
        // Get and increment the packet id
        $id = ++$this->_Id;

        // Put our packet together
        $data = pack("VV", $id, $cmd) . $s1 . chr(0) . $s2 . chr(0);

        // Prefix the packet size
        $data = pack("V", strlen($data)) . $data;

        // Send packet
        fwrite($this->_Sock, $data, strlen($data));

        // In case we want it later we'll return the packet id
        return $id;
    }

    function _PacketRead()
    {
        //Declare the return array
        $retarray = array();
        //Fetch the packet size
        while ($size = @fread($this->_Sock, 4)) {
            $size = unpack('V1Size', $size);
            //Work around valve breaking the protocol
            if ($size["Size"] > 4096) {
                //pad with 8 nulls
                $packet = "\x00\x00\x00\x00\x00\x00\x00\x00" . fread($this->_Sock, 4096);
            } else {
                //Read the packet back
                $packet = fread($this->_Sock, $size["Size"]);
            }
            array_push($retarray, unpack("V1ID/V1Response/a*S1/a*S2", $packet));
        }
        return $retarray;
    }

    function rconCommand($Command)
    {
        $this->sendcommand($Command);

        $ret = $this->Read();

        //ATM: Source servers don't return the request id, but if they fix this the code below should read as
        // return $ret[$this->_Id]['S1'];
        return $ret[$this->_Id]['S1'];
    }

    function sendCommand($Command)
    {
        //$Command = '"'.trim(str_replace(' ','" "', $Command)).'"';
        //$Command="stop";
        $this->_Write(SERVERDATA_EXECCOMMAND, $Command, '');
    }

    function Read()
    {
        $Packets = $this->_PacketRead();

        foreach ($Packets as $pack) {
            if (isset($ret[$pack['ID']])) {
                $ret[$pack['ID']]['S1'] .= $pack['S1'];
                $ret[$pack['ID']]['S2'] .= $pack['S1'];
            } else {
                $ret[$pack['ID']] = array(
                    'Response' => $pack['Response'],
                    'S1' => $pack['S1'],
                    'S2' => $pack['S2'],
                );
            }
        }
        return $ret;
    }
}
