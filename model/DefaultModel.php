<?php
namespace model;

class DefaultModel extends Model
{
    protected $table = 'tests';

    public function __construct()
    {
        parent::__construct($this);
    }
    // public function test()
    // {
    //     echo 'MODEL TEST';
    // }
}