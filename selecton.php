<?php
/**
 * Created by PhpStorm.
 * User: shenyuede
 * Date: 2018/8/28
 * Time: 13:55
 * Email: shenyuede@qianbao.com
 *
 *
 */
function selection($arr) {
  $i = count($arr);
  while ($i) {
    $bp = $i-1;
    //The position from 0 to i-1, we find the biggest one and mark it as bp
    for ($j=0;$j<$i;$j++) {
      $arr[$j] > $arr[$bp] && $bp = $j;
    }
    //Switch biggest one and the right one(that is i-1).
    list($arr[$bp],$arr[$i-1]) = array($arr[$i-1],$arr[$bp]);
    //Move the right point backward.
    $i--;
  }
  return $arr;
}

$my_array = array(3, 0, 2, 5, -1, -2, 1);
echo 'Original Array : '.implode(',',$my_array)."\n";
$my_array = selection($my_array);
echo 'Sorted Array : '.implode(',',$my_array);