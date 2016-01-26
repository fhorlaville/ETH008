<?php
/*
* PHP code to manipulate a Robot Electronics ETH008 relay box
* http://www.robot-electronics.co.uk/products/relay-modules/ethernet-relay/eth008-8-x-16a-ethernet-relay.html
*/

$g_ip_address = "";
$g_port = "";
$g_timeout = 1;

define("C_ACTIVATE",        32);
define("C_DEACTIVATE",      33);
define("C_SET_OUTPUTS",     35);
define("C_GET_OUTPUTS",     36);
define("C_SET_STRING",      58);


function testConnect($ip_address="", $port="", $timeout=""){
    $fp = openConnection($ip_address, $port, $timeout);
    if (!$fp) {
        return false;
    } else {
        closeConnection($fp);
        return true;
    }
}

function openConnection($ip_address="", $port="", $timeout=""){
    global $g_ip_address, $g_port, $g_timeout;

    if($ip_address == "") { $ip_address = $g_ip_address; }
    if($port == "") { $port = $g_port; }
    if($timeout == "") { $timeout = $g_timeout; }

    try {
        $fp = fsockopen($ip_address, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return false;
        } else {
            return $fp;
        }
    } catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}

function closeConnection($fp){
    if($fp) {
        fclose($fp);
    }
}

function setup($ip_address = "192.168.0.100", $port = 17494, $timeout=1) {
    global $g_ip_address, $g_port, $g_timeout;

    $g_ip_address = $ip_address;
    $g_port = $port;
    $g_timeout = $timeout;

    return($g_ip_address == $ip_address && $g_port == $port && $g_timeout == $timeout);
}

function message($command, $param1=null, $param2=null)
{
    $msg = prepareMsg($command, $param1, $param2);
    return sendMsg($msg);
}

function prepareMsg($command, $param1, $param2)
{
    if (isset($param2)){
        $msg = pack("CCC", $command, $param1, $param2);
    } elseif (isset($param1)) {
        $msg = pack("CC", $command, $param1);
    } else {
        $msg = pack("C", $command);
    }
    return $msg;
}

function sendMsg($msg)
{
    $fp = openConnection();
    if ($fp) {
        fwrite($fp, $msg);
        $result = ord(fread($fp, 1));
        closeConnection($fp);
        return ($result);
    } else return false;
}


function turnOn($relay, $duration=0){
    return message(C_ACTIVATE,$relay, $duration); //0 for success, 1 for failure
}

function turnOff($relay, $duration=0){
    return message(C_DEACTIVATE,$relay, $duration); //0 for success, 1 for failure
}

function setRelays($relayByte=0){
    return message(C_SET_OUTPUTS,bindec($relayByte)); //0 for success, 1 for failure
}

function getRelays(){
    return decbin(message(C_GET_OUTPUTS)); //string with current relay states
}

function setRelayString($config){
    return sendMsg($config); //0 for success, 1 for failure
}

function setMultiple($relayArray){
    $result=0;
    foreach($relayArray as $relay){
        $result+=message(($relay[1]==1? C_ACTIVATE: C_DEACTIVATE) ,$relay[0], $relay[2]);
    }
    return ($result>0 ? 1 : 0); //0 for success, 1 for failure
}


?>