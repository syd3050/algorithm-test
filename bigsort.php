<?php
/**
 * Created by PhpStorm.
 * User: shenyuede
 * Date: 2018/9/21
 * Time: 14:41
 * Email: shenyuede@qianbao.com
 *
 *
 */

include "insert.php";


/**
 * 构建100个数据，模拟超大文件
 * @return array
 */
function _big_create()
{
  $count = 500;
  $r = [];
  while ($count--)
  {
    $tmp = rand(1,10000);
    $num = 1000;//尝试1000次
    while (in_array($tmp,$r) && $num--)
    {
      usleep(2000);
      $tmp = rand(1,10000);
    }
    $r[] = $tmp;
  }
  return $r;
}

//$big数据可认为是存在硬盘中的数据，有1000个数据
$big = _big_create();

$sbSize = 0;          /* 输出缓冲区大小 */
$littleFileSize = 0;  /*小文件的大小，一般简化为内存大小*/
$lbSize = 0;          /* 对应各个小文件的小缓冲区大小 */
$totalFiles = 0;      /* 小文件数量 */

function bigSort($memSize)
{
  global $big;
  global $littleFileSize;

  //排序结果文件
  $sortedFile = [];
  //小文件的大小，一般简化为内存大小
  $littleFileSize = $memSize;
  //大文件总量
  $bigSize = count($big);
  //各个小文件，这里用数组模拟硬盘上的文件
  $littleFile = [];
  //一个有nums个小文件
  $nums = ceil($bigSize/$littleFileSize);
  global $totalFiles;
  $totalFiles = $nums;

  $begin = 0;
  $end = $littleFileSize;
  //
  $tmp = 0;
  $count = 0;
  /**
   * 将大文件切割分成小文件
   */
  for ($i=0;$i<$nums;$i++)
  {
    while ($begin < $end)
    {
      $littleFile[$i][] = $big[$begin];
      $begin++;
    }
    $end = $end + $littleFileSize;
    $end >= $bigSize && $end = $bigSize;
  }
  //die(json_encode($littleFile));
  /**
   * 对每个小文件排序
   */
  for ($i=0; $i<$nums; $i++)
  {
    $littleFile[$i] = insert($littleFile[$i]);
  }
  //die(json_encode($littleFile));
  $pos = []; //保存了各个小文件(也即$littleFile[$i])中当前要对比的元素指针
  for ($i=0; $i<$nums; $i++)
  {
    $pos[$i] = 0; //指针初始化为0
  }

  /**
  内存中分配一个缓冲区，大小$sbSize=$memSize/$nums，用于缓存排序后的结果
  满了后将结果全写到结果文件中，也即$sortedFile中
   */
  $sortedBuffer = [];
  /* 输出缓冲区大小 */
  global $sbSize;
  $sbSize = ceil($memSize/$nums);
  /* 对应各个小文件的小缓冲区大小 */
  global $lbSize;
  $lbSize = floor(($memSize-$sbSize)/$nums);
  /**
   * 内存中再分配$nums个小缓冲区，每个缓冲区大小为($memSize-$sbSize)/$nums
   * 每个小缓冲区对应一个小文件
   */
  $littleBuffer = [];
  /**
   * 将小文件中的数据填充小缓冲区
   */
  for ($i=0;$i<$totalFiles;$i++)
  {
    load($littleFile[$i],$pos[$i],$littleBuffer,$i);
  }

  /**
  2.循环取每个小缓冲区数组的p元素，取其中最小元素，写入数组B
  3.将数组A1指针前移，重复这个过程2-3，如果有某一个数组指针p大于9（每个数组有9个元素），
   * 将该数组对应文件另一块数据加载进对应数组，重复过程2-3；
   */
  $ttt = 0;
  while ($totalFiles)
  {
    $len = count($littleBuffer);
    if($len == 0)
      break;
    //循环取每个小缓冲区数组的p元素，取其中最小元素，写入输出缓冲区$sortedBuffer
    $tmp = $littleBuffer[0][0];
    $index = 0; //记录最小元素属于哪个小缓冲区
    for ($i=0; $i < $len; $i++)
    {
      if($littleBuffer[$i][0] < $tmp)
      {
        $tmp = $littleBuffer[$i][0];  //获取较小元素
        $index = $i; //记录较小元素
      }
    }
    $ttt++;
    //移除小缓冲区的最小元素
    $sortedBuffer[] = array_shift($littleBuffer[$index]);
    if(count($sortedBuffer) == $sbSize) {
      if($ttt > 25) {
        die(json_encode(array_merge($sortedFile,$sortedBuffer)));
      }else{
        var_dump(json_encode($sortedBuffer));
        var_dump(json_encode(array_merge($sortedFile,$sortedBuffer)));
      }

      //将输出缓冲区内容附加到文件中，$sortedFile用于模拟硬盘上的大文件
      $sortedFile = array_merge($sortedFile,$sortedBuffer);
      $sortedBuffer = [];  //清空输出缓冲区
    }

    if(empty($littleBuffer[$index])) {
      $pos[$index] = load($littleFile[$index],$pos[$index],$littleBuffer,$index);
    }

  }

  return $sortedFile;
}

/**
 * 将小缓冲区对应的小文件载入，如果小文件已全部处理，将总数减1
 * @param array $littleFile   小文件
 * @param int   $pos          指针起始
 * @param array $littleBuffers 小缓冲区
 * @param int $index 小缓冲区位置
 * @return int  最终指针停止的地方
 */
function load($littleFile,$pos,&$littleBuffers,$index)
{
  global $lbSize;         /*各个小缓冲区大小*/
  global $littleFileSize; /*小文件的大小，一般简化为内存大小*/
  global $totalFiles;     /* 小文件数量 */
  //本次从小文件读取的截止指针
  $end = $pos + $lbSize;
  if($end >= $littleFileSize )
  {
    $totalFiles--;
    $end = $littleFileSize; //该文件已经读完
  }

  //模拟从硬盘小文件中读取数据进入对应的小缓存
  while ($pos < $end)
  {
    $littleBuffers[$index][] = $littleFile[$pos];
    $pos++;
  }

  if($end == $littleFileSize) {
    $end = -1;
  }
  return $end;
}

/**
 * 将各个小文件载入小缓冲区
 * @param array   $littleFile   各个小文件，存在硬盘上
 * @param array   $littleBuffer 各个小缓冲区，在内存
 * @param int     $nums         小文件个数
 * @param array   $blockP       各个小缓冲区对应的指针
 * @param int     $bsize        各个小缓冲区大小
 * @param int     $lfSize       各个小文件大小
 * @return int    剩余小文件总数
 */
function load2($littleFile,&$littleBuffer,$nums,&$blockP,$bsize,$lfSize)
{
  $total = $nums;
  for ($i=0;$i<$nums;$i++)
  {
    //该文件已经读完
    if($blockP[$i] >= $lfSize)
    {
      $total--;  //剩余小文件总数减少
      continue;
    }
    if(empty($littleBuffer[$i]))
    {
      //本次从小文件读取的截止指针
      $len = $blockP[$i] + $bsize;
      $len > $lfSize && $len = $lfSize; //该文件已经读完
      //模拟从硬盘小文件中读取数据进入对应的小缓存
      while ($blockP[$i] < $len)
      {
        $littleBuffer[$i][] = $littleFile[$i][$blockP[$i]];
        $blockP[$i] = $blockP[$i]+1;
      }
    }
  }

  return $total;
}



die(json_encode(bigSort(50)));