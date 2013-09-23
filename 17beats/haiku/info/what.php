<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
ob_start();


echo "<div id=register class='white round full'  ><div class=pad >
<h2>Haiku.... what?</h2><br />

Haiku (often pronounced hi-koo) is a form of Japanese poetry with 17 syllables going back as early as the 1600s. Typically the syllables are split into 3 lines with 5, 7 and 5 syllables respectively. For example:<br />
<br />
This is a haiku<br />
It is very elegant<br />
and inspires you<br />
<br />

<i>This &bull; is &bull; a &bull; hai&bull;ku (5)<br />
It &bull; is &bull; ve&bull;ry &bull; el&bull;e&bull;gant (7)<br />
and &bull; in&bull;spi&bull;res &bull; you (5)</i><br />
<br />
Traditional Japanese haikus are written vertically, and the count sound called \"moras\", which in English is known as the \"<a href='http://en.wikipedia.org/wiki/Onji'>on</a>\". However while traditional Japanese haikus use moras to denote count, the English version uses syllables.
There is no requirement to haiku except that it has 17 syllables. Therefore you can split it into 3 lines or mix it up as you please. For example:<br />
<br />
<i>This haiku has only one line, one soul and one chance to create hope</i>
<br />
<br />
When the haiku started it was originally used to describe the beauty of nature. It's length requires an author to craft words in a way to fit the constraints. This allows for work that is simple yet can have deep meanings or takes suprising turns. <br />
<br />
In modern culture they can be about anything so be creative!  Write down words that go with an idea and then put them into 17 syllables (aka beats). If you don't have the right amount of syllables, think of synonyms to replace words.
And when you're done, submit your haiku <a href='http://17beats.com/submit'>here</a>. We would love to see your work!

</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("category", -2);
$smarty->assign("title", " | Submit");
$smarty->display('std.tpl');

?>
