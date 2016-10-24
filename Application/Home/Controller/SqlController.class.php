<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class SqlController extends CommonController{
    private $visit_ip;
    private $now_time;
    private $up_count = array();
    private $block_type = array();
    private $lock_time = array();
    private $filter = array(
        'get' => "\\<.+javascript:window\\[.{1}\\\\x|<.*=(&#\\d+?;?)+?>|<.*(data|src)=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\()|<[a-z]+?\\b[^>]*?\\bon([a-z]{4,})\s*?=|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)",
        'post' => "<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)",
        'cookie' => "benchmark\s*?\\(\d+?|sleep\s*?\\([\d\.]+?\\)|load_file\s*?\\(|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT(\\(.+\\)|\\s+?.+?)|UPDATE(\\(.+\\)|\\s+?.+?)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?)FROM(\\(.+\\)|\\s+?.+?)|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)"
    );
    private $up_time = array();
//

   public function protect() {
       $result = M('sql')->find();
       $this->up_count['sql'] = $result['sqlupcount'];
       $this->block_type['sql'] = $result['sqltype'];
       $this->up_time['sql'] = $result['sqluptime'];
       $this->lock_time['sql'] = $result['sqllocktime'];
       $this->visit_ip = GetIP();
       $this->now_time = time();
       $this->del_logs();
       $this->check_lock();
       $this->sql_filter();
   }

    protected function sql_filter() {
        $rel = false;
        $info = '';

        foreach($_GET as $key=>$value) {
            if($this->param_scan($key, $value, $this->filter['get'], "GET")) {
                $rel = true;
                $info = "GET:".serialize($_GET);
            }
        }
        foreach($_POST as $key=>$value) {
            if($this->param_scan($key, $value, $this->filter['post'], "POST")) {
                $rel = true;
                $info = "POST:".serialize($_POST);
            }
        }
        foreach($_COOKIE as $key=>$value) {
            if($this->param_scan($key, $value, $this->filter['cookie'], "COOKIE")) {
                $rel = true;
                $info = "COOKIE:".serialize($_COOKIE);
            }
        }
        $info = (!get_magic_quotes_gpc ()) ? addslashes ($info) : $info;
        if($rel) {
            $this->add_visit_log('sql');
            $this->add_log('sql', '拦截成功', $info);
            $this->assign('bianhao',2001);
            $this->assign('info','请不要尝试输入非法参数');
            $this->display("Public/dispatch_jump");
            exit();
        }
        $visit_logs = M('visit_logs');
        $count = $visit_logs->where("IP = '{$this->visit_ip}' AND TYPE = 'sql'")->count();

        if($count > $this->up_count['sql']) {
            $this->add_lock('sql', '2001', '您的IP已被锁定，请稍后访问');
        }
    }

    protected function param_scan($key, $val, $req,$type) {
        $val = $this->param_tree($val);
        if (preg_match("/".$req."/is",$val)==1) {
            return true;
        }
        if (preg_match("/".$req."/is",$key)==1) {
            return true;
        }
        return false;
    }

    protected function param_tree($arr) {
        static $str;
        static $keystr;
        if (!is_array($arr)) {
            return $arr;
        }
        foreach ($arr as $key => $val ) {
            $keystr=$keystr.$key;
            if (is_array($val)) {
                $this->param_tree($val);
            }
            else {
                $str[] = $val.$keystr;
            }
        }
        return implode($str);
    }


    public function index(){
        $result = M('sql')->find();
        $this->assign('vo',$result);
        $this->display();
    }



    protected function del_logs() {
        $visit_logs = M('visit_logs');
        $sql_start_time = $this->now_time - $this->up_time['sql'];
        $visit_logs->where("TIME < '{$sql_start_time}' AND TYPE = 'sql'")->delete();
        $lock_logs = M('lock_logs');
        $lock_logs->where("{$this->now_time} > ENDTIME")->delete();
    }

    public function check_lock() {
        $lock_logs = M('lock_logs');
        $rel = $lock_logs->where("IP='{$this->visit_ip}' AND ENDTIME > '{$this->now_time}'")->find();
        if (!empty($rel)) {
            if ($rel['type'] == 'ddos') {
                if ($this->block_type['ddos'] == 'lock') {
                    $this->error($rel['NO'], $rel['MSG']);
                }
            }
            else if($rel['TYPE']=='sql'&&$this->block_type['sql']=='lock') {
                $this->error($rel['NO'], $rel['MSG']);
            }
            else if($rel['TYPE']=='file'&&$this->block_type['file']=='lock') {
                $this->error($rel['NO'], $rel['MSG']);
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
            if(!$_POST['sqluptime']){
                return show(0,'时间设置不能为空');
            }
            if(!$_POST['sqlupcount']){
                return show(0,'单位时间访问次数不能为空');
            }
            if(!$_POST['sqllocktime']){
                return show(0,'单次IP锁定时间不能为空');
            }
            $id = $_POST['id'];
            unset($_POST['id']);
            $data=$_POST;
            $rel = M('sql')->Where('id='.$id)->save($data);

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
        $rel = D('logs')->select();
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