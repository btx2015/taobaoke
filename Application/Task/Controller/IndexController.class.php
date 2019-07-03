<?php

namespace Task\Controller;

class IndexController extends CommonController
{
    public function index(){
        S('match_settle_lock',time());
        writeLog('start match','exec','DEBUG');
    }
}