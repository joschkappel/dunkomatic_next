<?php
   namespace App\Http\Controllers;
   function Cartesian_Product($data)
    {
        //ini_set('memory_limit', '2G');

        // get total
        $dim=1;
        foreach($data as $vec){
          $dim = $dim * count($vec);
        };
        // limit to less than 100.000 combinations
        while ($dim > 200000){
         $dim =1;
         for($i=0; $i < count($data); $i++){
              if (count($data[$i]) >2){
                 array_pop( $data[$i] );
              };
              $dim = $dim * count($data[$i]);
          }
        }

        $result = array(array());

        foreach ($data as $key => $values) {
            $append = array();

            foreach($result as $product) {
                foreach($values as $item) {
                    // $product[$key] = $item;
                    if ($key == 0){
                        $productt[0] = $item;

                    } else {
                        $productt[0] = array_merge($product[0], $item);
                    }
                    $append[] = $productt;
                }
            }

            $result = $append;

        }
        return $result;
    }

  function combos($arr, $k) {
    if ($k == 0) {
      return array(array());
    }

    if (count($arr) == 0) {
      return array();
    }

    $head = $arr[0];

    $combos = array();
    $subcombos = combos($arr, $k-1);
    foreach ($subcombos as $subcombo) {
      array_unshift($subcombo, $head);
      $combos[] = $subcombo;
    }
    array_shift($arr);
    $combos = array_merge($combos, combos($arr, $k));
    return $combos;
  }

  // $arr = array("iced", "jam", "plain","salty");
  $arrA = [1,2,3,4];

  $resultA = combos($arrA, 5);
  $numA = count($resultA);
  echo("$numA combinatins for series A\n");
  $arrB = [1,2,3,4,5,6,7,8];
  $resultB = combos($arrB, 2);
  $numB = count($resultB);
  echo("$numB combinatins for series B\n");

  $data[] = $resultA;
  $data[] = $resultB;
  $resultTot = Cartesian_Product($data);
  $tot = count($resultTot);
  echo("$tot total combinations\n");
  var_dump($resultTot[0]);
  var_dump($resultTot[$tot-1]);

/*   foreach($result as $combo) {
    echo implode(' ', $combo), "<br>";
  }
  $donuts = range(1, 10);
  $test = combos($donuts, 2);
  $num_donut_combos = count($test);
  echo "$num_donut_combos ways to order 15 donuts given 10 types"; */
?>
