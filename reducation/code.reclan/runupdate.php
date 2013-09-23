<?php
require("../GameQ.php");
if (!($conn = mysql_connect($dbhost, $dbuser, $dbpass))) {
    echo "Error, can't connect right now because" . mysql_error();
    exit;
}
mysql_select_db($dbname);
$ip   = "";
$pass = "";
if (isset($_GET['add'])) {
    if (isset($_POST['password']) && isset($_POST['ip']) && isset($_POST['port'])) {
        if ($_POST['password'] == "andr3w") {
            $pass = 'andr3w';
            $ip   = $_POST['ip'];
            if (mysql_query("insert into servers(ip,port,ver) values('" . mysql_real_escape_string($_POST['ip']) . "','" . mysql_real_escape_string($_POST['port']) . "',1)"))
                echo "OK<br />";
            else
                echo "ERR" . mysql_error();
        } else
            echo "Invalid pass<br />";
        
    }
    echo "<form action='runupdate.php?add=1' method=post >
Password<input type='password' name='password' value='$pass' /><br />
IP <input type=text name=ip value='$ip' /><br />
Port <input type=text name=port /><br /> 
<input type=submit value='Add server' />
";
    exit;
}
$tmt = time();
error_reporting(E_ALL);
while (time() - $tmt < 60) {
    $allS = mysql_query("select * from servers where ver=1");
    echo mysql_error();
    $servers = array();
    while ($r = mysql_fetch_array($allS)) {
        $servers[] = array(
            "halo",
            $r['ip'],
            $r['port']
        );
    }
    $gq = new GameQ;
    
    
    // Add the servers defined earlier
    try {
        $gq->addServers($servers);
    }
    catch (Exception $e) {
        //   print 'One of the server entries was not defined correctly.';
        exit;
    }
    
    $gq->setOption('timeout', 15000); // Socket timeout in ms
    $gq->setOption('raw', false); // Return raw or parsed data
    $gq->setOption('sockets', 10); // The maximum number of sockets used by the script
    $gq->setFilter('normalise');
    try {
        $results = $gq->requestData();
    }
    catch (Exception $e) {
        print 'An error occurred while requesting or processing data.';
        exit;
    }
    // Display the data using a simple table
    $servers     = "";
    $names       = array();
    $playerids   = array();
    $commonNames = array();
    $que         = mysql_query("select name from commonnames where verified=1");
    if ($que)
        while ($r = mysql_fetch_array($que)) {
            $commonNames[] = $r['name'];
        }
    $serverlist = "";
    foreach ($results as $id => $result) {
        //map=mapname
        //ctf=gametype
        //server=hostname
        //score
        if (array_key_exists("hostname", $result)) {
            mysql_query("update servers set seen=now() , name='" . mysql_real_escape_string(preg_replace('/\x01/', '', $result['hostname'])) . "', map='" . mysql_real_escape_string($result['mapname']) . "', type='" . mysql_real_escape_string($result['gametype']) . "',maxplayers='" . mysql_real_escape_string($result['maxplayers']) . "' where ip='" . $result['gq_address'] . "' and port='" . $result['gq_port'] . "'");
            $que      = mysql_query("select id from servers where ip='" . $result['gq_address'] . "' and port='" . $result['gq_port'] . "'");
            $serverid = 0;
            if ($que)
                if ($r = mysql_fetch_array($que)) {
                    $serverid = $r['id'];
                }
            $column = strtolower($result['gametype']);
            $serverlist .= $serverid . ",";
            foreach ($result['players'] as $player) {
                if ($result['maxplayers'] > 4)
                    if ($result['num_players'] <= 3) {
                        if (in_array(mysql_real_escape_string($player['player']), $commonNames))
                            continue;
                        if (in_array(mysql_real_escape_string($player['player']), $names)) {
                            mysql_query("insert into commonnames(name) values('" . mysql_real_escape_string($player['player']) . "') on duplicate key update times=times+1");
                            
                        } else
                            $names[] = mysql_real_escape_string($player['player']);
                        
                        continue;
                    } elseif (in_array(mysql_real_escape_string($player['player']), $names)) {
                        mysql_query("insert into commonnames(name) values('" . mysql_real_escape_string($player['player']) . "') on duplicate key update times=times+1");
                    }
                
                if (in_array(mysql_real_escape_string($player['player']), $commonNames))
                    continue;
                $thisplayer = mysql_query("select * from online where name='" . mysql_real_escape_string($player['player']) . "'");
                switch ($column) {
                    case 'ctf':
                    case 'slayer':
                        $col = "scores";
                        break;
                    case 'race':
                        $col = "laps";
                        break;
                    case 'king':
                    case 'oddball':
                        $col = "time";
                        break;
                        
                }
                $oldscore = 0;
                if ($tpr = mysql_fetch_array($thisplayer)) {
                    if ($tpr['map'] == $result['mapname'] && $tpr['server'] == $result['hostname']) {
                        $oldscore = $tpr['score'];
                    }
                }
                
                switch ($column) {
                    case 'king':
                    case 'oddball':
                        $spl = explode(":", $player['score']);
                        foreach ($spl as $k => $v) {
                            if (!is_numeric($v))
                                $spl[$k] = 0;
                        }
                        if (count($spl) == 2) {
                            $tmpto = $spl[0] * 60;
                            $tmpto += $spl[1];
                        } else
                            $tmpto = $spl[0];
                        $onlinescr = $tmpto;
                        if ($oldscore > $tmpto)
                            $toadd = $tmpto;
                        else
                            $toadd = $tmpto - $oldscore;
                        break;
                    default:
                        $onlinescr = $player['score'];
                        if ($player['score'] < $oldscore)
                            $toadd = $player['score'];
                        else
                            $toadd = $player['score'] - $oldscore;
                }
                if (in_array(mysql_real_escape_string($player['player']), $names)) {
                    mysql_query("insert into commonnames(name) values('" . mysql_real_escape_string($player['player']) . "') on duplicate key update times=times+1");
                    
                } else
                    $names[] = mysql_real_escape_string($player['player']);
                
                mysql_query("insert into names(name, server, thisweek) values('" . mysql_real_escape_string($player['player']) . "', $serverid, 1) on duplicate key update server=$serverid, thisweek=1");
                $plid = 0;
                $que  = mysql_query("select id from names where name='" . mysql_real_escape_string($player['player']) . "'");
                if ($que)
                    while ($r = mysql_fetch_array($que)) {
                        $plid = $r['id'];
                    }
                $playerids[] = $plid;
                
                $query  = "insert into  crasxit0_haloStats." . $column . " (name, " . $col . ", joined, online) values($plid, $toadd, " . time() . ", 0 ) on duplicate key update $col=$col+$toadd, online=online+if(joined=0,0," . time() . "-joined),joined='" . time() . "'";
                $query2 = "insert into  crasxit0_haloStats.thisweek" . $column . " (name, " . $col . ", joined, online) values ($plid, $toadd, " . time() . ", 0) on duplicate key update $col=$col+$toadd, online=online+if(joined=0,0," . time() . "-joined), joined='" . time() . "'";
                
                mysql_query($query);
                echo mysql_error();
                mysql_query($query2);
                echo mysql_error();
                mysql_query("insert into online(name,score,server,map) values('" . mysql_real_escape_string($player['player']) . "','" . mysql_real_escape_string($onlinescr) . "','" . mysql_real_escape_string($result['hostname']) . "','" . mysql_real_escape_string($result['mapname']) . "') on duplicate key update score='" . $onlinescr . "', server='" . mysql_real_escape_string($result['hostname']) . "', map='" . mysql_real_escape_string($result['mapname']) . "'");
                echo mysql_error();
            } //end players	
        } //end hostname
    } //end result
    
    $namess = "'" . implode("','", $names) . "'";
    
    $pids = "'" . implode("','", $playerids) . "'";
    
    mysql_query("update king  set time=0 where time<0");
    mysql_query("update thisweekking  set time=0 where time<0");
    $tables = array(
        "race",
        "king",
        "oddball",
        "ctf",
        "slayer"
    );
    foreach ($tables as $t) {
        mysql_query("update $t set joined=0 where name not in ($pids )");
        mysql_query("update thisweek" . $t . " set joined=0 where name not in ($pids )");
    }
    mysql_query("delete from online where name not in($namess)");
    
    $serverlist = substr($serverlist, 0, -1);
    mysql_query("update names set server=0 where server in(select id from servers where id in($serverlist) and ver=1) and name not in($namess)");
    sleep(30);
} //loop

//cleanup
mysql_query("update names set server=0 where server in(select id from servers where unix_timestamp(now())-unix_timestamp(seen)>7200) and ver=0");
mysql_query("delete from servers where unix_timestamp(now())-unix_timestamp(seen)>7200 and ver=0");
mysql_query("update names set server=0 where server in(select id from servers where time_to_sec(now())-unix_timestamp(seen)>7200) and ver=1");
mysql_query("delete from servers where unix_timestamp(now())-unix_timestamp(seen)>7200 and ver=1");
mysql_query("update names set server=0 where server not in(select id 'server' from servers)");
$que = "select n.id from  commonnames cn, names n where cn.verified=1 and n.name=cn.name";
mysql_query("delete from king where name in($que)");
mysql_query("delete from race where name in($que)");
mysql_query("delete from slayer where name in($que)");
mysql_query("delete from ctf where name in($que)");
mysql_query("delete from oddball where name in($que)");
mysql_query("delete from thisweekking where name in($que)");
mysql_query("delete from thisweekrace where name in($que)");
mysql_query("delete from thisweekslayer where name in($que)");
mysql_query("delete from thisweekctf where name in($que)");
mysql_query("delete from thisweekoddball where name in($que)");
mysql_query("delete from names where id in($que)");









?>