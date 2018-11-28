<?php

class Node {
    public $val;
    public $next;
    
    public function __construct($val) {
        $this->val = $val;
        $this->next = null;
    }
}

/**
 * 构建n个节点的单项链表，每个节点的值从1-n递增
 * @param $n
 * @return Node|null 节点头指针
 */
function init($n) {
  $head = new Node(-1);
  $current = $head;
  $v = $n;
  while ($n) {
    $tmp = new Node($v-($n-1));
    $tmp->next = null;
    $current->next = $tmp;
    $current = $tmp;
    $n--;
  }
  $head = $head->next;
  return $head;
}

/**
 * 寻找链表中倒数第n个节点
 * @param Node $link 链表
 * @param int $n     第n个节点
 * @return Node | null
 */
function backword_n($link,$n) {
    $i = 0;
    $target = $link;
    while($link != null) {
        $link = $link->next;
        $i++;
        if($i > $n) {
            $target = $target->next;
            $i = $n;
        }
    }
    return $target;
}

/**
 * 翻转链表
 * 要点：只在两个节点间操作，不操作3个节点
 * @param Node $head
 * @return Node
 */
function revert(Node $head) {
  $pre = null;
  while ($head != null) {
    //先保存该节点的下一个节点
    $next = $head->next;
    //翻转该节点的next指针，指向前节点pre
    $head->next = $pre;
    //前节点pre迁移到当前节点位置
    $pre = $head;
    //当前节点迁移到它下一个节点位置
    $head = $next;
  }
  return $pre;
}

/**
 * 删除指定值的节点
 * @param Node $link
 * @param $value
 * @return Node|null 返回被删除的节点，返回的节点next已经处理为null
 */
function delete(Node &$link,$value) {
  $current = $link;
  $pre = null;
  while ($current != null) {
    if($current->val == $value) {
      //要删除头结点
      if($pre == null) {
        $link = $link->next;
        $current->next = null;
        return $current;
      }
      $pre->next = $current->next;
      $current->next = null;
      return $current;
    }
    $pre = $current;
    $current = $current->next;
  }
  return null;
}

/**
 * 打印整个链表的节点值及指针指向
 * @param Node $link
 * @return string
 */
function print_link(Node $link) {
  $str = '';
  while ($link != null) {
    $str .= "{$link->val}->";
    $link = $link->next;
  }
  $str .= "NULL \n";
  return $str;
}

/**
 * 打印单个节点的值
 * @param Node $node
 * @return string
 */
function print_node(Node $node) {
  if($node != null)
    return "{$node->val} \n";
  return "NULL \n";
}

$list = init(7);
echo "链表：".print_link($list);
echo "链表倒数第3个节点：".print_node(backword_n($list,3));
$list = revert($list);
echo "链表翻转后：".print_link($list);
delete($list,3);
echo "删除值为3的节点后，链表为：".print_link($list);