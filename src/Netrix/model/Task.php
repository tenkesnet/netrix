<?php

namespace Netrix\model;


class Task {
    public string $name;
    public int $updated_on;
    public int $comments_count;
    
    public function __construct(string $name, int $updated_on, int $comments_count) {
        $this->name = $name;
        $this->updated_on =$updated_on;
        $this->comments_count= $comments_count;
    }
    static function descSort(Task $a, Task $b)
    {
        return strtolower($b->updated_on) <=> strtolower($a->updated_on);
    }
}
