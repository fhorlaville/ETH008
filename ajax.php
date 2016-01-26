<?php
require_once("functions.php");

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

$config = array(
    'blue'=> 1,
    'red'=> 4,
    'yellow'=> 7,
    'green'=> 8
);

$gDebug=false;

$bdc=new bdc();
$button1Duration=get('button1');
$button2Duration=get('button2');

$button1On=get('button1on');
$button1Off=get('button1off');
$button2On=get('button2on');
$button2Off=get('button2off');
$allOff=get('alloff');

if (!setup("192.168.1.1", "17494")) {
    returnError('setup');
    exit();
}

function get($parm) {
 $parmOutput="";
  if (isset($_GET[$parm])) { $parmOutput = $_GET[$parm];}
  return addslashes(htmlentities($parmOutput, ENT_QUOTES, "UTF-8"));
}
        
function firstSet($turnOn = 0, $duration = 0)
{
    global $config;
    return !setMultiple(array(
//        [$config['blue'], $turnOn, $duration],
        [$config['yellow'], $turnOn, $duration],
        [$config['green'], $turnOn, $duration]
    ));
}

function secondSet($turnOn = 0, $duration = 0)
{
    global $config;
    return !setMultiple(array(
//        [$config['blue'], $turnOn, $duration],
        [$config['red'], $turnOn, $duration],
        [$config['green'], $turnOn, $duration]
    ));
}

if ($button1Duration) {
    if(firstSet(1, $button1Duration)){
        returnOK('firstSet');
    } else {
        returnError('firstSet');
    }
}

if ($button2Duration) {
    if(secondSet(1, $button2Duration)){
        returnOK('secondSet');
    } else {
        returnError('secondSet');
    }
}

if ($button1On) {
    if(firstSet(1, 0)){
        returnOK('firstSetOn');
    } else {
        returnError('firstSetOn');
    }
}

if ($button1Off) {
    if (firstSet(0, 0)) {
        returnOK('firstSetOff');
    } else {
        returnError('firstSetOff');
    }
}

if ($button2On) {
    if(secondSet(1, 0)){
        returnOK('secondSetOn');
    } else {
        returnError('secondSetOn');
    }
}

if ($button2Off) {
    if(secondSet(0, 0))
    {
        returnOK('secondSetOff');
    } else {
        returnError('secondSetOff');
    }
}

if ($allOff) {
    if (!setRelays('00000000')) {
        returnOK('allOff');
    } else {
        returnError('allOff');
    }
}

//--- functions

function returnError($msg="")
{
    header("Status: 400 ERROR", 400);
    echo "Error:".$msg . "<br>";;
    exit();
}

function returnOK($msg="")
{
    global $gDebug;
    if($gDebug) {
        global $button1Duration, $button2Duration, $button1On, $button1Off, $button2On, $button2Off, $allOff;

        echo "action:".$msg . "<br>";
        echo "button1:" . $button1Duration . '<br>';
        echo "button2:" . $button2Duration . '<br>';
        echo "button1on:" . $button1On . '<br>';
        echo "button1off:" . $button1Off . '<br>';
        echo "button2on:" . $button2On . '<br>';
        echo "button2off:" . $button2Off . '<br>';
        echo "alloff:" . $allOff . '<br>';
        echo "Status:" . getRelays();
    } else {
        return(header("Status: 200 OK", 200));
    }
    exit();
}
