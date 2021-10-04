<?php

namespace App\Example\Models;

use Makiavelo\Flex\Flex;

class Tag extends Flex
{
    public $id;
    public $name;
    
    public function __construct()
    {
        parent::__construct();
        $this->meta()->add('table', 'tag');
        $this->relations()->add([
            'name' => 'Users',
            'key' => 'tag_id',
            'table' => 'user',
            'external_key' => 'user_id',
            'relation_table' => 'user_tag',
            'class' => 'App\\Example\\Models\\User',
            'type' => 'HasAndBelongs'
        ]);
    }
}