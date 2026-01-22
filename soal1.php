<?php

$jml = $_GET['jml'];
echo "<table border=1>\n";
for ($a = $jml; $a > 0; $a--)
{
    $total = 0;
    for($i = $a; $i > 0; $i--) {
        $total += $i;
    }

    echo "<tr>\n";
    echo "<th colspan='$jml'>Total : $total</th>";
    echo "</tr>\n";

    echo "<tr>\n";
    for ($b = $a; $b > 0; $b--)
    {
        echo "<td>$b</td>";
    }
    
    $sisaKolom = $jml - $a;
    if ($sisaKolom > 0) {
        echo "<td colspan='$sisaKolom'></td>";
    }
    echo "</tr>\n";
}
echo "</table>";

?>