<?php
require("../GameQ.php");
function getNumeric($g)
{
    if (!isset($_REQUEST[$g]))
        return 0;
    $n = $_REQUEST[$g];
    if (!is_numeric($n))
        die("");
    return $n;
}
define("ECHOQUERY", true);

if (!isset($_REQUEST['pw']))
    exit;
if (!$_REQUEST['pw'] == "ZzaGCjw8iA")
    exit;
if (!($conn = mysql_connect($dbhost, $dbuser, $dbpass))) {
    echo "Error, can't connect right now because" . mysql_error();
    exit;
}
mysql_select_db($dbname);
if ($_SERVER['HTTP_X_FORWARD_FOR']) {
    $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

if (!isset($_REQUEST['port']))
    exit();
$port = addslashes($_REQUEST['port']);
if (!is_numeric($port))
    exit();
$que = mysql_query("select * from servers where ip='$ip' and port='$port' and name=''");
if ($que)
    if ($r = mysql_fetch_array($que)) {
        $servers   = array();
        $servers[] = array(
            "halo",
            $ip,
            $port
        );
        $gq        = new GameQ;
        // Add the servers defined earlier
        try {
            $gq->addServers($servers);
        }
        catch (Exception $e) {
            print 'One of the server entries was not defined correctly.';
            exit;
        }
        
        $gq->setOption('timeout', 2000); // Socket timeout in ms
        $gq->setOption('raw', false); // Return raw or parsed data
        $gq->setOption('sockets', 128); // The maximum number of sockets used by the script
        $gq->setFilter('normalise');
        try {
            $results = $gq->requestData();
        }
        catch (Exception $e) {
            print 'An error occurred while requesting or processing data.';
            exit;
        }
        
        foreach ($results as $id => $result) {
            //if(ECHOQUERY)mysql_query("insert into queries(q, time) values('got new info".addslashes(mysql_error())."', now())");
            mysql_query("update servers set map='" . addslashes($result['gq_mapname']) . "', maxplayers='" . $result['gq_maxplayers'] . "', type='" . $result['gametype'] . "', name='" . addslashes($result['hostname']) . "' where ip='$ip' and port='$port'");
        }
        
    }
if (isset($_REQUEST['quit'])) {
    $name    = addslashes($_REQUEST['quit']);
    $a       = getNumeric("assists");
    $b       = getNumeric("betrays");
    $d       = getNumeric("deaths");
    $u       = getNumeric("suicides");
    $retr    = getNumeric("returns");
    $k       = getNumeric("kills");
    $lp      = getNumeric("laps");
    $ctf     = getNumeric("ctf");
    $slayer  = getNumeric("slayer");
    $king    = getNumeric("king");
    $oddball = getNumeric("oddball");
    $c       = getNumeric("captures");
    
    mysql_query("update servers set seen=now() where ip='$ip' and port='$port'");
    
    if (ECHOQUERY)
        mysql_query("insert into queries(q, time) values('quit=$name', now())");
    
    $que = mysql_query("select * from names where name='$name' and not server=0");
    if (mysql_num_rows($que) == 0) {
        
        if (ECHOQUERY)
            mysql_query("insert into queries(q, time) values('hegoneerr=$name', now())");
        
        die("he gone");
    }
    mysql_query("update names set server=0 where name='$name'");
    $qur    = mysql_query("select type, id, maxplayers from servers where ip='$ip' and port='$port'");
    $type   = "";
    $srvrid = -1;
    $maxpl  = 0;
    if ($qq = mysql_fetch_array($qur)) {
        $type   = strtolower($qq['type']);
        $srvrid = $qq['id'];
        $maxpl  = $qq['maxplayers'];
    } else
        die(mysql_error());
    if ($maxpl > 6) {
        $que = mysql_query("select count(*) c from names where server=$srvrid");
        if ($que)
            while ($r = mysql_fetch_array($que)) {
                if ($r['c'] < 4) {
                    exit;
                }
            }
    }
    switch ($type) {
        case "ctf":
            mysql_query("update ctf set scores=scores+$ctf, captures=captures+$c, kills=kills+$k, deaths=deaths+$d, returns=returns+$retr, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a, online=online+time_to_sec(timediff(now(),joined)) where name=(select id from names where name='$name')");
            mysql_query("update thisweekctf set scores=scores+$ctf, captures=captures+$c, kills=kills+$k, deaths=deaths+$d, returns=returns+$retr, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a where name=(select id from names where name='$name')");
            break;
        case "slayer":
            mysql_query("update slayer set scores=scores+$slayer, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a, online=online+time_to_sec(timediff(now(),joined)) where name=(select id from names where name='$name')");
            
            mysql_query("update thisweekslayer set scores=scores+$slayer, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a where name=(select id from names where name='$name')");
            break;
        case "race":
            mysql_query("update race set laps=laps+$lp, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a, online=online+time_to_sec(timediff(now(),joined)) where name=(select id from names where name='$name')");
            mysql_query("update thisweekrace set laps=laps+$lp, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a where name=(select id from names where name='$name')");
            break;
        case "oddball":
            mysql_query("update oddball set time=time+$oddball, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a, online=online+time_to_sec(timediff(now(),joined)) where name=(select id from names where name='$name')");
            mysql_query("update thisweekoddball set time=time+$oddball, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a where name=(select id from names where name='$name')");
            break;
        case "king":
            mysql_query("update king set time=time+$king, kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a, online=online+time_to_sec(timediff(now(),joined)) where name=(select id from names where name='$name')");
            mysql_query("update thisweekking set time=time+$king kills=kills+$k, deaths=deaths+$d, suicides=suicides+$u, betrays=betrays+$b, assists=assists+$a where name=(select id from names where name='$name')");
            break;
    }
    
} else
//////////////////////////////////////////////////////////////////
    if (isset($_REQUEST['join'])) {
    $name = addslashes($_REQUEST['join']);
    $pip  = "";
    if (isset($_REQUEST['ip'])) {
        $pip = addslashes($_REQUEST['ip']);
    }
    $phash = "";
    if (isset($_REQUEST['hash'])) {
        $phash = addslashes($_REQUEST['hash']);
    }
    if (ECHOQUERY)
        mysql_query("insert into queries(q, time) values('join-$name', now())");
    mysql_query("insert into names (name, server, thisweek) values('$name',(select id from servers where ip='$ip' and port='$port' limit 1), 1) on duplicate key update server=(select id from servers where ip='$ip' and port='$port' limit 1), thisweek=1");
    $uid  = mysql_insert_id();
    //ip
    /*
    $ipid=mysql_insert_id(mysql_query("insert into crasxit0_ips.ips(ip) values ('$pip')"));
    mysql_query("insert into crasxit0_ips.halo_ips(name,ip_id,date)  values( '$uid', '$ipid', now())");
    
    //hash
    mysql_query("insert into crasxit0_ips.hashes(hash) values ('$phash')");
    $hashid=mysql_insert_id();
    mysql_query("insert into crasxit0_ips.halo_hashes(name, hash) values ('$uid', '$hashid')");
    
    if(ECHOQUERY)mysql_query("insert into queries(q, time) values('".mysql_real_escape_string("$name- $uid- $ipid- $hashid")."', now())");
    */
    $qur  = mysql_query("select type from servers where id=(select server from names where name='$name')");
    $type = "";
    if ($qq = mysql_fetch_array($qur)) {
        $type = strtolower($qq['type']);
    } else
        die(mysql_error());
    $type = addslashes($type);
    mysql_query("insert into " . $type . "(name, joined) values((select id from names where name='$name'), now()) on duplicate key update name=name, joined=now()");
    mysql_query("insert into thisweek" . $type . "(name, joined) values((select id from names where name='$name'), now())");
    mysql_query("update servers set seen=now() where ip='$ip' and port='$port'");
} else
//////////////////////////////////////////////////////////////////////////////
    if (isset($_REQUEST['newmap'])) {
    mysql_query("insert into servers(ip, port, ver) values('$ip', $port,0) on duplicate key update seen=now()");
    $id = mysql_insert_id();
    mysql_query("update names set server=0 where server=$id");
    
    
    if (ECHOQUERY)
        mysql_query("insert into queries(q, time) values('newmap', now())");
    
    $servers   = array();
    $servers[] = array(
        "halo",
        $ip,
        $port
    );
    $gq        = new GameQ;
    // Add the servers defined earlier
    try {
        $gq->addServers($servers);
    }
    catch (Exception $e) {
        print 'One of the server entries was not defined correctly.';
        exit;
    }
    
    $gq->setOption('timeout', 2000); // Socket timeout in ms
    $gq->setOption('raw', false); // Return raw or parsed data
    $gq->setOption('sockets', 128); // The maximum number of sockets used by the script
    $gq->setFilter('normalise');
    try {
        $results = $gq->requestData();
    }
    catch (Exception $e) {
        print 'An error occurred while requesting or processing data.';
        exit;
    }
    
    foreach ($results as $id => $result) {
        mysql_query("update servers set map='" . addslashes($result['gq_mapname']) . "', maxplayers='" . $result['gq_maxplayers'] . "', type='" . $result['gametype'] . "', name='" . addslashes($result['hostname']) . "' where ip='$ip' and port='$port'");
    }
    
} elseif (isset($_REQUEST['shutdown'])) {
    
    mysql_query("update names set server=0 where server in(select id from servers where ip='$ip' and port='$port' and ver=0)");
    mysql_query("delete from servers where ip='$ip' and port='$port'");
}

?>