<?php
namespace Snowing\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        /*检测是否登录*/
        /*if(!isset($_SESSION['name'])){
            $this->error('未检测到登录状态','../Login/login',1);
        }*/

        $this->display('index');



    }

}