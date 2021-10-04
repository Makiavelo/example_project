<?php

namespace App\Example\Models;

use Makiavelo\Flex\Flex;

class User extends Flex
{
    public $id;
    public $name;
    public $last_name;
    
    public function __construct()
    {
        parent::__construct();
        $this->meta()->add('table', 'user');
        $this->relations()->add([
            'name' => 'Tags',
            'table' => 'tag',
            'relation_table' => 'user_tag',
            'table_alias' => '',
            'class' => 'App\\Example\\Models\\Tag',
            'key' => 'user_id',
            'external_key' => 'tag_id',
            'type' => 'HasAndBelongs',
        ]);
    }
}