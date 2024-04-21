<?php

namespace bonanoo\Lootbags;

class Lootbag{
    public string $name;
    public Array $obtainable;
    public int $chance;
    public int $reward_count;
    public Array $rewards;

    public function __construct(Array $data){
        $this->name = $data["name"];
        $this->obtainable = $data["obtainable"];
        $this->chance = $data["chance"];
        $this->reward_count = $data["reward-count"];
        $this->rewards = $data["rewards"];

    }
}