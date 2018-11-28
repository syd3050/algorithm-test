<?php
/**
 * Created by PhpStorm.
 * User: shenyuede
 * Date: 2018/8/28
 * Time: 11:55
 * Email: shenyuede@qianbao.com
 *
 *
 */

function bubble($arr) {
  $length = count($arr);
  $i = $length;

  while ($i) {
    //Every time we begin,elements from 0 to i-1 is disorder.
    for ($j=0;$j < $i-1;$j++) {
      //We need to put the bigger one to right.
      if ($arr[$j] > $arr[$j+1]) {
        list($arr[$j],$arr[$j+1]) = array($arr[$j+1],$arr[$j]);
      }
    }
    $i--;
  }
  return $arr;
}

$my_array = array(3, 0, 2, 5, -1, -2, 1);
echo 'Original Array : '.implode(',',$my_array)."\n";
$my_array = bubble($my_array);
echo 'Sorted Array : '.implode(',',$my_array);