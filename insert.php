<?php
/**
 * Created by PhpStorm.
 * User: shenyuede
 * Date: 2018/8/28
 * Time: 11:30
 * Email: shenyuede@qianbao.com
 *
 *
 */

function insert($array) {
  $length = count($array);
  for($i=1;$i<$length;$i++) {
    $index = $array[$i];
    //All elements before j are sorted.
    //So when element arr[j-1] is larger than index,
    //it means we should search a position for index backward.So we sub j to look backward.
    //If arr[j-1] is little than index,it means all elements before j are little than index,we stop
    $j = $i;
    while ($j > 0 && $array[$j-1] > $index) {
      //J has been replaces by j-1
      $array[$j] = $array[$j-1];
      $j--;
    }
    //We find a position for index.
    $array[$j] = $index;
  }
  return $array;
}

/*$my_array = array(3, 0, 2, 5, -1, 4, 1);
echo 'Original Array : '.implode(',',$my_array)."\n";
$my_array = insert($my_array);
echo 'Sorted Array : '.implode(',',$my_array);*/