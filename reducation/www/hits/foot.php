<br />
<br />
<br />
  <hr width="100%" /><br />

    <TABLE border="0"  cellpadding="0" cellspacing="0" width="100%">
	      <TR  align="top">
					  	  <TD  height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">

                            <div class="cap-right">
                            <h4>
                            Chat 
                            </h4>
                            </div>
                            </div>
                            </div>
						  </TD>
					  </TR>

			   		  <TR >
						  	  <TD  class="menubody"   >
                             <?php                           
					global $phpEx, $phpbb_root_path;

		
	  	if(!$user->data['is_bot']){
	 if($user->data['is_registered']){

	
    // Get the URL to the chat directory:
    if (!defined('AJAX_CHAT_URL'))
    {
        define('AJAX_CHAT_URL', './forum/chat/');
    }
 
    // Get the real path to the chat directory:
    if (!defined('AJAX_CHAT_PATH'))
    {
        if (empty($_SERVER['SCRIPT_FILENAME']))
        {
            $_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_URL'];
        }
        define('AJAX_CHAT_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/forum/chat') . '/');
	}
 
    // Validate the path to the chat:
    if (@is_file(AJAX_CHAT_PATH . 'lib/classes.' . $phpEx))
    {
        // Include Class libraries:
        require_once(AJAX_CHAT_PATH.'lib/classes.' . $phpEx);
        // Initialize the shoutbox:
        $ajaxChat = new CustomAJAXChatShoutBox();
 
        // Parse and return the shoutbox template content:
        echo $ajaxChat->getShoutBoxContent();
    } 
	 }else{
		echo "Please <a href='http://fastclan.org/forum/ucp.php?mode=register'>register</a> before chatting ";
	 }
		}
		
	?>
    </TD></TR></TABLE>  
</td><td valign="top" width="170px">

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<center>
<input type="image" src="http://fastclan.org/bgs/donate.jpg" name="submit" alt="">
<img alt="" border="0" src="https://www.paypal.com/sv_SE/i/scr/pixel.gif" width="1" height="1">
</center>
</form>
  <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">
					  	  <TD width="170" height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">

                            <div class="cap-right">
                            <h4>
                            Recent Posts
                            </h4>
                            </div>
                            </div>
                            </div>
						  </TD>
					  </TR>

			   		  <TR >
					  	  <TD  class="menubody">
<?php
global $db;
$ret="";

$query = 
" SELECT post_subject, post_id, username
FROM (

SELECT concat( substr( po.post_subject, 1, 15 ) , if( length( po.post_subject ) >15, '', '' ) ) 'post_subject', po.post_id, u.username
FROM posts po, users u
WHERE po.forum_id NOT
IN ( 7, 22 )
AND u.user_id = po.poster_id
ORDER BY post_time DESC
) AS p ";

$result = $db->sql_query_limit($query, 20);

while ($r = $db->sql_fetchrow($result))
{
 $ret.=" <a href='http://fastclan.org/forum/viewtopic.php?p=".$r['post_id']."#p".$r['post_id']."'>".$r['post_subject']."</a> ".$r['username']."<br />
";

   
   unset($message,$poster_id);
}
echo $ret;
?>
                        </TD></TR></TABLE>           
                            <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">
					  	  <TD width="170" height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">
                            <div class="cap-right">

                            <h4>
                            Weeklyboard 
                            </h4>
                            </div>
                            </div>
                            </div>
						  </TD>
					  </TR>
			   		  <TR >

					<TD  class="menubody" id=weeklydiv >
              <img src="http://fastclan.org/images/load.gif" />
              <script type="text/javascript">
		
				function loadwb(){
				getLHttp("http://fastclan.org/ajaxloads/weeklyboard.php", "weeklydiv");
				}
				</script>
                          </TD></TR></TABLE>      
                                                     <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">
					  	  <TD width="170" height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">

                            <div class="cap-right">
                            <h4>
                            Leaderboard 
                            </h4>
                            </div>
                            </div>
                            </div>
						  </TD>
					  </TR>

			   		  <TR >
						  	  <TD  class="menubody" id=leaderdiv >
              <img src="http://fastclan.org/images/load.gif" />
              <script type="text/javascript">

			addLoadEvent(loadlb);
			addLoadEvent(loadts);
			addLoadEvent(loadwb);
			
			  function addLoadEvent(func) {
			  var oldonload = window.onload;
			  if (typeof window.onload != 'function') {
				window.onload = func;
			  } else {
				window.onload = function() {
				  if (oldonload) {
					oldonload();
				  }
				  func();
				}
			  }
			}

				function loadlb(){
				getLHttp("http://fastclan.org/ajaxloads/leaderboard.php", "leaderdiv");
				getLHttp("http://fastclan.org/ajaxloads/getTable.php", "stable1");
				getLHttp("http://fastclan.org/ajaxloads/getTable2.php", "stable2");
				
				}
				</script>
                          </TD></TR></TABLE>   

        
                          </td>
</tr></table><!-- end holder-->


	</td>

</tr>
</table>
<?php include('hits/hits.php');?><br />
<center><a href="http://crasxit.net">&copy;Matt Ramir - crasXIT 2009;</a> All rights reserved</CENTER>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<br />
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7954906-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>