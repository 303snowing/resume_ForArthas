<?php
/**
 * Created by PhpStorm.
 * User: 303snowing
 * Date: 2016/12/8
 * Time: 6:04
 */

namespace Snowing\Controller;


use Think\Controller;

class JianliController extends Controller
{
    public function index(){
        /*检测是否登录*/
        if(!isset($_SESSION['name'])){
//            $this->error('未检测到登录状态','../Login/login',1);
            $this->ajaxReturn(0);
        }
        if($newUsers = M('jianli')){
            if ($data = $newUsers->order('time desc')->select()){
                /*$data是一个二维数组*/
                //var_dump($data);
//                $this->assign('data',$data);
//                $content = $this->fetch('jianli');
//                $this->ajaxReturn($content, 'eval');
                //$this->display('jianli');
                $data[] = '简历';
                $this->ajaxReturn($data);
            }else{
                $this->error('没有记录','../../Home/index',5);
            }
        }else{
            $this->error('数据表实例化失败!请呼叫码畜!','../Error/error',3);
        }


    }
}