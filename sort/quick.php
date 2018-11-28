<?php
/**
 * Created by PhpStorm.
 * User: shenyuede
 * Date: 2018/8/27
 * Time: 14:10
 * Email: shenyuede@qianbao.com
 *
 *
 */


function quick_sort($arr) {
  if(count($arr) < 2) {
    return $arr;
  }
  $loe = array();
  $gt = array();
  $pivot_k = key($arr);
  $pivot_v = array_shift($arr);
  foreach ($arr as $k=>$v) {
    if($v <= $pivot_v)
      $loe[$k] = $v;
    if($v > $pivot_v)
      $gt[$k] = $v;
  }
  $arr = array_merge(quick_sort($loe),array($pivot_k=>$pivot_v),quick_sort($gt));
  return $arr;
}

function quick_sort2(&$arr,$low,$high) {
  if($low > $high)
    return;
  $p = partition($arr,$low,$high);
  quick_sort2($arr,$low,$p-1);
  quick_sort2($arr,$p+1,$high);
}

function partition(&$arr,$low,$high) {
  $random = rand($low,$high);
  exchange($arr[$random],$arr[$high]);
  $x = $arr[$high];
  $i = $low-1;
  $j = $low;
  while ($j < $high) {
    if($arr[$j] <= $x) {
      exchange($arr[++$i],$arr[$j]);
    }
    $j++;
  }
  exchange($arr[++$i],$arr[$high]);
  return $i;
}

function exchange(&$a,&$b) {
  $tmp = $a;
  $a = $b;
  $b = $tmp;
}

$my_array = array(30, 10, 12, 25, 7, 54, 1,6,9,8,199);
echo 'Original Array : '.implode(',',$my_array)."\n";
//$my_array = quick_sort($my_array);
$low = 0;
$high = count($my_array)-1;
quick_sort2($my_array,$low,$high);

echo 'Sorted Array : '.implode(',',$my_array);