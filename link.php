<?php

class Node {
    public $val;
    public $next;
    
    public function __construct($val) {
        $this->val = $val;
        $this->next = null;
    }
}

function init() {
    $n1 = new Node(1);
    $n2 = new Node(2);
    $n3 = new Node(3);
    $n4 = new Node(4);
    $n5 = new Node(5);
    $n6 = new Node(6);
    $n7 = new Node(7);
    $n1->next = $n2;
    $n2->next = $n3;
    $n3->next = $n4;
    $n4->next = $n5;
    $n5->next = $n6;
    $n6->next = $n7;
    return $n1;
}

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

$list = init();
var_dump(backword_n($list,5));