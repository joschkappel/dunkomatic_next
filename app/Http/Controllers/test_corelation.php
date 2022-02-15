<?php

function pearson_correlation(array $x, array $y): float
{
    if(count($x)!==count($y)){return -1;}
    $x=array_values($x);
    $y=array_values($y);
    $xs=array_sum($x)/count($x);
    $ys=array_sum($y)/count($y);
    $a=0;$bx=0;$by=0;
    for($i=0;$i<count($x);$i++){
        $xr=$x[$i]-$xs;
        $yr=$y[$i]-$ys;
        $a+=$xr*$yr;
        $bx+=pow($xr,2);
        $by+=pow($yr,2);
    }
    $b = sqrt($bx*$by);
    return $a/$b;
}

$array_x = array(5,3,6,7,4,2,9,5);
$array_y = array(4,3,4,8,3,2,10,5);
// $array_y = array(1,2,1,2,1,3,1,22);
$pearson = pearson_correlation($array_x,$array_y);
echo $pearson;
?>
