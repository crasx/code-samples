<?php
/*
Description: Twitter PHP code
Author: Andrew MacBean
Version: 1.0.0
*/
 get_tweets("crasx");
/** Method to make twitter api call for the users timeline in XML */ 
function twitter_status($twitter_id) {	
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, "http://twitter.com/statuses/user_timeline/$twitter_id.xml");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	$response = curl_exec($c);
	$responseInfo = curl_getinfo($c);
	curl_close($c);
	if (intval($responseInfo['http_code']) == 200) {
		if (class_exists('SimpleXMLElement')) {
			$xml = new SimpleXMLElement($response);
			return $xml;
		} else {
			return $response;
		}
	} else {
		return false;
	}
}

/** Method to add hyperlink html tags to any urls, twitter ids or hashtags in the tweet */ 
function processLinks($text) {
	$text = utf8_decode( $text );
	$text = preg_replace('@(https?://([-\w\.]+)+(d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>',  $text );
	$text = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $text);  
	$text = preg_replace("#(^|[\n ])\#([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://hashtags.org/search?query=\\2\" >#\\2</a>'", $text);
	return "<a href='http://twitter.com/reclan'>".$text."</a>";
}

/** Main method to retrieve the tweets and return html for display */
function get_tweets($twitter_id, 
					$nooftweets=1, 
					$dateFormat="D jS M y H:i", 
					$includeReplies=false, $dateTimeZone="Usa/Chicago",
					$beforeTweetsHtml="", 
					$tweetStartHtml="",
					$tweetMiddleHtml="",
					$tweetEndHtml="", 
					$afterTweetsHtml="",
					$showDate=false) {

	date_default_timezone_set($dateTimeZone);
   	if ( $twitter_xml = twitter_status($twitter_id) ) {
		$result = $beforeTweetsHtml;
		foreach ($twitter_xml->status as $key => $status) {
			if ($includeReplies == true | substr_count($status->text,"@") == 0 | strpos($status->text,"@") != 0) {
				$message = processLinks($status->text);
				$result.=$tweetStartHtml.$message.$tweetMiddleHtml.($showDate?date($dateFormat,strtotime($status->created_at)):"").$tweetEndHtml;
				++$i;
				if ($i == $nooftweets) break;
    			}
    		}
			$result.=$afterTweetsHtml;
    } 
	else {
        $result.= $beforeTweetsHtml."Twitter seems to be unavailable at the moment".$afterTweetsHtml;
    }	
    echo $result;
}
?>