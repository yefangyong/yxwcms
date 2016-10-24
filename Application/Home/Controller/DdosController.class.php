<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class DdosController extends CommonController{
    private $visit_ip;
    private $now_time;
    private $up_count = array();
    private $block_type = array();
    private $lock_time = array();
    private $up_time = array();
//

   public function protect() {
       $result = M('ddos')->find();
       $this->up_count['ddos'] = $result['ddosupcount'];
       $this->block_type['ddos'] = $result['ddostype'];
       $this->up_time['ddos'] = $result['ddosuptime'];
       $this->lock_time['ddos'] = $result['ddoslocktime'];
       $this->visit_ip = GetIP();
       $this->now_time = time();
       $this->del_logs();
       $this->check_lock();
       $this->ddos_filter();
   }


    public function ddos_filter() {
        $this->add_visit_log('ddos');
        $visit_logs = M('visit_logs');
        $count = $visit_logs->where("IP = '{$this->visit_ip}' AND TYPE = 'ddos'")->count();

        if($count > $this->up_count['ddos']) {
//            if(isset($_SESSION['kws_code_entered']))
//                unset($_SESSION['kws_code_entered']);
            $this->add_lock('ddos', '1001', '请不要重复刷新页面');
        }
    }

    protected function del_logs() {
        $visit_logs = M('visit_logs');
        $ddos_start_time = $this->now_time - $this->up_time['ddos'];
        $visit_logs->where("TIME < '{$ddos_start_time}' AND TYPE = 'ddos'")->delete();
        $lock_logs = M('lock_logs');
        $lock_logs->where("{$this->now_time} > ENDTIME")->delete();
    }

    public function check_lock() {
        $lock_logs = M('lock_logs');
        $rel = $lock_logs->where("IP='{$this->visit_ip}' AND ENDTIME > '{$this->now_time}'")->find();
        if (!empty($rel)) {
            if ($rel['type'] == 'ddos') {
                if ($this->block_type['ddos'] == 'lock') {
                    $this->assign('bianhao',$rel['no']);
                    $this->assign('info','请不要连续攻击本网站');
                    $this->display("Public/dispatch_jump");
                    exit();
                }
                else {
                    if(!isset($_SESSION['kws_code_entered'])) {
                        if(isset($_POST['kws_code'])) {
                            if(strtolower($_POST['kws_code'])==strtolower($_SESSION['kws_vcode'])) {
                                $_SESSION['kws_code_entered'] = true;
                                $lock_logs->where("IP='{$this->visit_ip}' AND TYPE = 'ddos'")->del();
                            }
                            else {
                                header('Content-Type:text/html;charset=utf-8');
                                MessageBox('验证码错误', '-1');
                            }
                        } else {
                            $this->enter_vcode();
                        }
                    }
                }
            }
            else if($rel['TYPE']=='sql'&&$this->block_type['sql']=='lock') {
                $this->show_error($rel['NO'], $rel['MSG']);
            }
            else if($rel['TYPE']=='file'&&$this->block_type['file']=='lock') {
                $this->show_error($rel['NO'], $rel['MSG']);
                }
            }
        }



    public function add_visit_log($type) {
        $visit_logs = M('visit_logs');
        $data=array(
            'IP'=>$this->visit_ip,
            'TIME'=>time(),
            'TYPE'=>$type,
        );
        $visit_logs->add($data);
    }

    public function add_lock($type, $no, $msg='') {
        $this->now_time=time();
        $lock_logs = M('lock_logs');
        $this->now_time = $this->now_time+($this->lock_time[$type])*60;
        $data = array(
            'IP'=>$this->visit_ip,
            'TYPE'=>$type,
            'ENDTIME'=>$this->now_time,
            'NO'=>$no,
            'MSG'=>$msg,
        );
        $lock_logs->add($data);

        if($this->block_type[$type]=='lock')
            $this->add_log($type, '锁定IP', $msg);
        else if($this->block_type[$type]=='vcode')
            $this->add_log($type, '输入验证码', $msg);
    }

    public function add_log($type, $result='', $other='') {
            $logs = M('logs');
            $data = array(
                'TIME'=>date('Y-m-d H:i:s',$this->now_time),
                'IP'=>$this->visit_ip,
                'TYPE'=>$type,
                'RESULT'=>$result,
                'OTHER'=>$other,
            );
            $logs->add($data);
    }

    public function add(){
        if($_POST){
            if(!$_POST['DDosUpTime']){
                return show(0,'时间设置不能为空');
            }
            if(!$_POST['DDosUpCount']){
                return show(0,'单位时间访问次数不能为空');
            }
            if(!$_POST['DDoslockTime']){
                return show(0,'单次IP锁定时间不能为空');
            }
            $id = $_POST['id'];
            unset($_POST['id']);
            $data=$_POST;
            $rel = M('ddos')->Where('id='.$id)->save($data);

            if($rel == false) {
                return show(0, '配置失败');
            }else{
                return show(1,'配置成功!');
            }
        }else{
            return show(0,'没有提交的数据');
        }
    }

    public function logs() {
        $rel = D('logs')->where("TYPE='ddos'")->select();
        $this->assign('result',$rel);
        $this->display();
    }
    /**
     * 缓存处理
     */
    public function cache(){
        $this->assign('type',2);
        $this->display();
    }
}
?>