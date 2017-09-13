<?php
/**
 * Created by PhpStorm.
 * User: 303snowing
 * Date: 2016/9/15 0015
 * Time: 11:20
 */

namespace Snowing\Controller;
use Think\Controller;

class ErrorController extends Controller{
    public function error404(){
        $this->display("");
    }
}