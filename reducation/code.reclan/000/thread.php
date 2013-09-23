<?php
	$dbuser = 'halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_leaderboard';
set_time_limit(0);
	$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
	mysql_select_db($dbname);
	$sl=mysql_query("select * from servers");
	$curlz=array();
	define("MAX",5);
	$rows=mysql_num_rows($sl);
	$tr=0;
	while($s=mysql_fetch_array($sl)){
		$rows--;
		$tr++;
		$curlz[] = curl_init();	
		$cid=sizeof($curlz)-1;
		curl_setopt($curlz[$cid], CURLOPT_URL, "http://reclan.com:1080/000/run.php?sid=".$s['id']);
		curl_setopt($curlz[$cid], CURLOPT_TIMEOUT, 0);
		
		echo "http://reclan.com:1080/000/run.php?sid=".$s['id']."\n";
		
		curl_setopt($curlz[$cid], CURLOPT_HEADER, 0);	
		if($tr==MAX||$rows==0){
				$mh=curl_multi_init();
				foreach($curlz as $c){
					curl_multi_add_handle($mh,$c);	
				}
				do {
					$mrc = curl_multi_exec($mh, $active);
					sleep(1);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);

				while ($active && $mrc == CURLM_OK) {
					if (curl_multi_select($mh) != -1) {
						do {
							$mrc = curl_multi_exec($mh, $active);
							sleep(1);
						} while ($mrc == CURLM_CALL_MULTI_PERFORM);
					}
				}
				 
				foreach($curlz as $c){
					curl_multi_remove_handle($mh,$c);	
				}
				$curlz=array();
				curl_multi_close($mh);			
				$tr=0;
		}
	}

?>