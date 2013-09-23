<?php
 passthru("C:\\xampp\\mysql\\bin\\mysql.exe -uroot -phimal952 < c:\\root\\judger_script\\cj_demo.sql", $out);
 echo $out;
unlinkRecursive("C:\\root\\judger\\demo\\uploads", false);

function unlinkRecursive($dir, $deleteRootToo)
{
    if(!$dh = @opendir($dir))
    {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..')
        {
            continue;
        }

        if (!@unlink($dir . '/' . $obj))
        {
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);
   
    if ($deleteRootToo)
    {
        @rmdir($dir);
    }
   
    return;
} 
?>