<?php

namespace Task\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function _initialize()
    {
        if(!IS_CLI)
            exit('not cli');
    }
}