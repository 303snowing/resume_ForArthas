<?php
/**
 * Created by PhpStorm.
 * User: 303snowing
 * Date: 2016/9/7 0007
 * Time: 12:39
 */

namespace Snowing\Controller;
use Think\Controller;


class LoginController extends Controller {
    public function login(){
        if($_SESSION['name']){
            $this->success('正在跳转~',U('Index/index'),3);
        }else{
            $this->success('前往登录页面...',U('Index/index'));
        }

    }

    /*用户名ajax验证*/
    public function usernameCheck(){
        if ($users = M('users')){
            if($user = $users->where("id={$_POST['user']}")->select()){
                $this->ajaxReturn(1);    //当前用户名存在
            }else{
                $this->ajaxReturn(0);    //用户不存在
            }
        }else{
            $this->error('登录失败-数据表实例化出错',U('Error/error'),5);
        }
    }

    public function loginCheck(){
       if($users = M('users')){
           //var_dump($users->select());
           //var_dump($_POST);
           if($user = $users->where("id={$_POST['user']}")->select()){
               /*select()已二维数组返回查出的行(我们id是学号，不重复，只会查出一行)*/
               if($user[0]['password']===$_POST['pass']){
                   /*
                    * 登录后初始化会话信息
                    * id(学号：唯一标识) 姓名 性别 组别 客户端ip/mac
                    * */
                   $mac = new GetMacAddrAndIp(PHP_OS);
                   $adminArr=array(
                       'session_id' => $user[0]['id'],
                       'name' => $user[0]['name'],
                       'sex' => $user[0]['sex'],
                       'groups' => $user[0]['groups'],
                       'ip' => $mac->getIp(),
                       'mac' => $mac->GetMacAddr(PHP_OS),
                   );
                   $_SESSION = $adminArr;
//                   $this->success($user[0]['name'].'，欢迎登录Arthas',U('Index/index'),3);
                   $this->ajaxReturn($user[0]['name']);
               }else{
//                   $this->error('登录失败-密码错误',U('login'),5);
                   $this->ajaxReturn(0);
               }
           }else{
//               $this->error('登录失败-用户名错误',U('login'),5);
               $this->ajaxReturn(-1);
           }
       }else{
           $this->error('登录失败-数据表实例化出错',U('Error/error'),5);
       }

    }

    public function exitLogin(){
        if($_POST['sessionUser'] == $_SESSION['name']){
            session(null);
//        $this->success('注销成功',U('Index/index'),0);
            $this->ajaxReturn(0);
        }

    }
}