<?php
ob_start();

define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Player history');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
?>
<script type="text/javascript" src="/grapher/excanvas.js"></script>
<script type="text/javascript" src="/grapher/mochikit/MochiKit.js"></script>
<script type="text/javascript" src="/grapher/plotkit/Base.js"></script>
<script type="text/javascript" src="/grapher/plotkit/Layout.js"></script>
<script type="text/javascript" src="/grapher/plotkit/Canvas.js"></script>
<script type="text/javascript" src="/grapher/plotkit/SweetCanvas.js"></script>
	<center>
    <script type="text/javascript">
    <?php

$dbhost = '127.0.0.1';
$dbuser = 'halo_time';
$dbpass = 'uzJX8ycahz9pCCh7';
$dbname = 'halo_time';
if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}
mysql_select_db($dbname);
$queries=array("select minute(time) time, count from time where hour(time)=hour(now())",
			"select minute(time) time, count from time where hour(time)=hour(now())-1",
			"select hour(time) time, count from time_hourly where day(time)=day(now())",
			"select hour(time) time, count from time_hourly where day(time)=day(now())-1",
			"select day(time) time, count from time_daily where week(time)=week(now())",
			"select day(time) time, count from time_daily where week(time)=week(now())-1"
			);
foreach($queries as $i=>$q){			
$time="";	
$que=mysql_query($q);
$x=array(-1,-1);
$y=-1;
if($que)while($r=mysql_fetch_array($que)){
	$time.="['".$r['time']."',".$r['count']."],";
	if($r['time']<$x[0]||$x[0]==-1){
		$x[0]=$r['time'];	
	}	
	if($r['time']>$x[1]||$x[1]==-1){
		$x[1]=$r['time'];	
	}	
	if($r['count']>$y||$y==-1){
		$y=$r['count'];	
	}
}
$time=substr($time,0,-1);
echo 'function drawGraph'.$i.'() {
	var options = {
    "xAxis": ['.$x[0].','.$x[1].'], 
	"yAxis":[0,'.($y+5) .']
};

    var layout = new PlotKit.Layout("line", options);
    layout.addDataset("sqrt", ['.$time.']);
    layout.evaluate();
    var canvas = MochiKit.DOM.getElement("graph'.$i.'");
    var plotter = new PlotKit.SweetCanvasRenderer(canvas, layout, {});
    plotter.render();
}
MochiKit.DOM.addLoadEvent(drawGraph'.$i.');
';
}
	?> </script>
    <h1>Players online</h1>
    <h1>This hour (by minute)</h1>
     <div><canvas id="graph0" height="300" width="500"></canvas></div>
    <h1>Last hour (by minute)</h1>
     <div><canvas id="graph1" height="300" width="500"></canvas></div>
    <h1>Today (by hour)</h1>
     <div><canvas id="graph2" height="300" width="500"></canvas></div>
	<h1>Yesterday (by hour)</h1>
     <div><canvas id="graph3" height="300" width="500"></canvas></div>
      <h1>This week (by day)</h1>
     <div><canvas id="graph4" height="300" width="500"></canvas></div>
      <h1>Last week (by day)</h1>
     <div><canvas id="graph5" height="300" width="500"></canvas></div>

    </center>
<?php
$template->assign_vars(array(
	'HEADERTEXT'		=> "Player history",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?>