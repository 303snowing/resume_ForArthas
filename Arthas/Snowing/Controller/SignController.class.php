<?php
/**
 * Created by PhpStorm.
 * User: 303snowing
 * Date: 2016/9/13 0013
 * Time: 11:52
 */

namespace Snowing\Controller;
use Think\Controller;


class SignController extends Controller{
    public function index(){
        /*检测是否登录*/
        if(!isset($_SESSION['name'])){
            $this->error('未检测到登录状态','../Index/index',10);
        }
        /*
         * ip验证失败返回 0
         * 通过ajax返回由前台js接收判断,确定页面转向
        */
        if($this->ipCheck() && $this->macCheck()){
            if(1)
                $this->ajaxReturn($this->signDo($_POST['zhouci']));
            else
                $this->ajaxReturn(-1);    //签到出错
        }else{
            $this->ajaxReturn(0);
        }
    }

    public function ipCheck(){
        if($ip = session('ip')){
            //echo $ip;
            $preg = "/^(59.76.49|127.0.0)\.[12][0-9]{0,2}$/";  //ip匹配正则
            if(preg_match($preg,$ip,$match)){
                //var_dump($match);
                return true;
            }else{
                //echo $ip.'不是指定区域的IP地址';
                return false;
            }
        }
    }
    public function macCheck(){
        if(session('mac') == '68-F7-28-55-6E-E0' || session('mac') == '2C-33-7A-16-3D-19'){
            return true;
        }else{
            return false;
        }
    }

    public function signDo($zhouci){
        /*
         * 接收前台提交过来的签到信息
         * id 姓名 组别 时间 ip 地点 （图片）
        */
        $sql = "CREATE TABLE IF NOT EXISTS `arthas`.`arthas_sign_{$zhouci}` ( `id` BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT , `num` BIGINT(10) NOT NULL DEFAULT '2014051102' , `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '陈国豪' , `groups` INT(1) NOT NULL DEFAULT '1' , `zhouci` INT(2) NOT NULL , `ip` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `time` DATE NOT NULL , `counts` INT(1) NOT NULL DEFAULT '0' ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci";
        if(M('', '', 'DB_CONFIG_ACTIVE')->execute($sql) === false)
            return false;

        if($signInfo = M('sign_'.$zhouci)) {
            //判断当天是否已经签到过
            $signed = $signInfo->where('num='.session('session_id'))->order('id desc')->find();
            $num = $signed['time'];
            return $num;

            if ($signed) {
//              当前签到时间(日期格式)在数据库中存在视为重复签到
                if ($signed['time'] == date('Y-m-d', time()) || $signed['counts'] >= 7) {
                    return false;
                }
            }
            if($signInfo->create()){

                $signInfo->num = $_SESSION['session_id'];
                $signInfo->name = $_SESSION['name'];
                $signInfo->groups = $_SESSION['groups'];
                $signInfo->zhouci = $zhouci;
                $signInfo->ip = $_SESSION['ip'];
                $signInfo->time = date('Y-m-d',time());
                $signInfo->counts = ++$signed['counts'];    //签到次数+1
                if($signInfo->add()){
                    return true;
                }
            }else
                return false;
        }else
            return false;
    }
}