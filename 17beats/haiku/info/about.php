<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
ob_start();


echo "<div id=register class='white round full'  ><div class=pad >
<h2>About this site</h2>
Ok... So you made it here... But what is 'here'?<br />
This website is the brainchild of crasX IT (<a href='http://crasxit.net'>http://crasxit.net</a>). It was created in 2010 as a way for authors to easily share and discuss haiku poetry. This is intended to be a <b>friendly</b> website, therefore I will not tolerate racial, offensive, or illegal-content haiku or comments. We are all humans, we are all equal, let us treat eachother as such.<br /><br />

This website is funded by love and ads. If the cost of hosting the website (and other expenses like domain registration and paying for images) is offset by income from <u>all</u> of crasX IT products the profit will be split 50-50. Half will pay for my college and the other half will go to a charity of my choice. Feel free to send suggestions of charities to support[at]17beats.com. 
<br />
This email can also be used for misc questions or comments. Note that I am a very busy person and may not reply right away, but I will do my best to reply ASAP.
<br />
<br />
I will need help moderating haiku and comments and I'm always looking for new friends! So feel free to comment me via IM (found on crasXIT.net website).<br />
<br />
<a name=terms ></a>
<h2>Terms</h2>
This website is owned and managed by <a href='http://crasxit.net'>crasX IT</a>. Any disputes can be emailed to support [at] 17bytes.com or via one of the ways found on the crasX IT website. The term 'crasX IT' or 'us' or 'we' refers to the owner of the website whose registered office is in the Chicago area of Illinois. The term 'you' refers to the user or viewer of our website.
The use of this website is subject to the following terms of use:
<ul style='padding-left:5px;'>
<li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>
<li>You may not redistribute the content of this website without the original author's consent. </li>
<li>We do not own any of the user submitted content and will not reproduce or sell it without consent of the original author.</li>
<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>
<li>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</li>
<li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
<li>Your use of this website and any dispute arising out of such use of the website is subject to the laws of The United States of America</li>

</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("category", -2);
$smarty->assign("title", " | Submit");
$smarty->display('std.tpl');

?>
