<?php
                        $fh = fopen('online_users/' . 'test', 'r+');
                        flock($fh, LOCK_EX);
                        $i = 0;
                        while (!feof($fh)) {
                            $up[$i] = fgets($fh);
                            $i++;
                        }
                        ftruncate($fh, 0);
                        fclose($fh);
$up = explode("\n",$up[0]);
echo $up[0];
$up = explode(" ",$up[0]);
for($i = 0; $i<count($up);$i++)
echo $up[$i]."<br>";
?>
