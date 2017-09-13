<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->assign('login',U('Snowing/Login/login'));
        $this->display('index');
    }

    public function jion(){
        //var_dump($_POST);
        if($newUser = M('jianli')){
            if($newUser->where("phone={$_POST['phone']}")->select()){
                //简历查重(以电话号码为依据,姓名可能会重复,不能用来查重)
                $this->error('资料已经提交过啦~请耐心等待我们的联系','index',3);
            }elseif($newUser->create()){
                $newUser->time = date('Y-m-d H:m:s',time());
                $newUser->message = str_replace(chr(13),'<br>',$newUser->message);
                $newUser->message = str_replace(chr(32),'&nbsp;',$newUser->message);
                if($newUser->add()){
                    $this->success('资料提交成功,请耐心等待我们的联系!么么哒~','index',3);
                }else{
                    $this->success('不好意思~后台程序开小差了~请联系逗逼程序员~','../Error/jionFailed',7);
                }
            }

        }else{
            $this->error('提交失败,请联系管理员[陈国豪18294062469]','../Error/jionFailed',7);
        }
    }
    
}