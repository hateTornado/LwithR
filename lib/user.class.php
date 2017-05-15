<?php
/**
 * user.class.php 用户类
 *
 * @version       v0.02
 * @create time   2017/3/13
 * @update time   2017/3/13
 * @author        dxl jt zzc
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class User{
	public $id = 0;                //用户ID
	public $account = '';          //用户账号
	public $gid  = 0;              //属组ID
	
	
	public function __construct($id=0){
		if(!empty($id)){//若id有值
			$user = self::getInfoById($id);
			if($user){
				$this->id      = $user['id'];
				$this->account = $user['account'];
				$this->gid     = $user['group'];
			}else{
				throw new MyException('用户不存在', 902);  #902需要改
			}
			}
	}
	
	public function login($account, $password, $cookie = 0){
	
		if(empty($account))throw new MyException('账号不能为空', 101);
		if(empty($password))throw new MyException('密码不能为空', 102);
	
		//检查账号
		$userinfo = Table_user::getInfoByAccount($account);
		if($userinfo == 0) {
			//不让用户准确知道是账号错误
			throw new MyException('账号或密码错误', 104);
		}
	
		//验证密码
		$password = self::buildPassword($password, $userinfo['salt']);
		if($password[0] == $userinfo['password']){
			//登录成功
			$this->id         = $userinfo['id'];
			$this->account    = $userinfo['account'];
			$this->gid        = $userinfo['group'];
				
			//设置cookie;
			if($cookie) $this->buildCookie();
	
			//设置session
			self::setSession(1, $this->id);
				
			//记录登陆信息
			$this->updateLoginInfo();
	
			//记录管理员日志log表
			$log = '成功登录!';
			//Adminlog::add($log);
	
			return action_msg('登录成功', 1);//登陆成功
		}else{
			throw new MyException('账号或密码错误', 104);
		}
	}
	
	 
	/**
	 * User::buildCookie()   设置登陆cookie
	 *
	 * @return void
	 */
	private function buildCookie(){
		global $cookie_USERID, $cookie_USERCODE;
	
		$cookie_time = time()+(3600*24*7);//7天
	
		setcookie($cookie_USERID, $this->id, $cookie_time);
		setcookie($cookie_USERCODE, self::getCookieCode($this->id, $this->account, $this->gid), $cookie_time);
	}
	
	//消除cookie
	static private function rebuildCookie(){
		global $cookie_USERID, $cookie_USERCODE;
	
		setcookie($cookie_USERID, '', time()-3600);
		setcookie($cookie_USERCODE, '', time()-3600);
	}
	
	//生成cookie校验码
	static private function getCookieCode($id = 0, $account = '', $group = 0){
		if(empty($id))throw new MyException('ID不能为空', 101);
		if(empty($account))throw new MyException('账号不能为空', 102);
		if(empty($group))throw new MyException('Group不能为空', 103);
	
		return md5(md5($account).md5($group).md5($id));//校验码算法
	}
	
	/**
	 * User::setSession()   设置登陆Session
	 *
	 * @param $type  1--记录Session  2--清除记录
	 * @return void
	 */
	static private function setSession($type, $id = 0){
		global $session_UserID;
	
		if($type == 1){
			if(empty($id))throw new MyException('ID不能为空', 101);
			$_SESSION[$session_UserID]    = $id;
		}else{
			$_SESSION[$session_UserID]    = 0;
		}
	}
	
	/**
	 * User::updateLoginInfo() 更新登陆信息
	 *
	 * @return
	 */
	public function updateLoginInfo(){
		return Table_User::updateLoginInfo($this->id);
	}
	
 
	/**
	 * User::logout()  退出登录
	 *
	 * @return void
	 */
	static public function logout(){
	
		$log = '退出登录!';
		Userlog::add($log);
	
		self::setSession(2);
		self::rebuildCookie();
	
	}
	

	/**
	 * User::getInfo()    用户详细信息
	 *
	 * @param integer $uid  用户ID
	 *
	 * @return
	 */
	static public function getInfoById($id){
		if(empty($id))throw new MyException('ID不能为空', 101);
	
		return Table_user::getInfoById($id);
	}
	
	static public function checkLogin(){
		global $session_UserID;
		global $cookie_UserID, $cookie_UserCODE;
	
		//是否存在session
		if(@$_SESSION[$session_UserID]){
			return true;
		}
	
		//不存在session则检查是否有cookie
		$cid   = $_COOKIE[$cookie_UserID];
		if(empty($cid)){
			return false;
		}
	
		//检查cookie数据是否对应，防止伪造
		$vcode = $_COOKIE[$cookie_UserCODE];
		$user = Table_user::getInfoById($cid);
	
		if(!$user) {
			//cookie数据不正确，清理掉
			self::rebuildCookie();
			return false;
		}
	
		$code = self::getCookieCode($cid, $user['account'], $user['group']);
	
		if($vcode != $code){
			//cookie数据不正确，清理掉
			self::rebuildCookie();
			return false;
		}
	
		//cookie数据正确，重写Session
		self::setSession(1, $cid);
		return true;
	}

	/**
	 * User::getList()   用户列表
	 *
	 * @param integer $group
	 *
	 * @return
	 */
	static public function getList($group = 0){
	
		//$startrow = ($page - 1) * $pagesize;
	
		return Table_user::getList($group);
	}
	
	/**
	 * User::addUser() 添加用户
	 *
	 * @param string  $account   账号
	 * @param string  $password  密码
	 * @param integer $group     群组ID
	 *
	 * @return
	 */
	static public function add($account, $password, $group){
	
		//检查参数
		if(empty($account))throw new MyException('账号不能为空', 101);
		//if(empty($password))throw new MyException('密码不能为空', 103);
		if(empty($group))throw new MyException('用户组不能为空', 102);
	
		if(ParamCheck::is_weakPwd($password)) throw new MyException('密码太弱', 103);
	
		//获取信息//判断帐号是否重复
		$user = Table_user::getInfoByAccount($account);
		if($user) throw new MyException('账号已经存在', 104);
	
		//检查用户组是否存在
/* 
		$usergroup = Table_usergroup::getInfoById($group);
		if(!$usergroup) throw new MyException('用户组不存在', 105);
	
 */	
		//生成用户密码
		$password = self::buildPassword($password);
	
		$rs = Table_user::add($account, $password, $group);
		if($rs > 0){
			//记录用户日志log表
			$msg = '成功添加用户('.$account.')';
			//Userlog::add($msg);
	
			return action_msg($msg, 1);
		}else{
			throw new MyException('注册失败', 106);
		}
	
	}
	/**
	 * User::buildPassword()  生成用户密码
	 *
	 * @param string $pwd   原始密码
	 * @param string $salt  密码Salt
	 * @return
	 */
	static private function buildPassword($pwd, $salt = ''){
	
		if(empty($pwd))throw new MyException('密码不能为空', 101);
		if(empty($salt)) $salt = randcode(10, 4);//生成Salt
	
		$pwd_new = md5(md5($pwd).$salt);//加密算法
	
		return array($pwd_new, $salt);
	}
	/**
	 * User::deleteUser()  删除用户
	 *
	 * @param integer $userId   用户ID
	 *
	 * @return
	 */
	static public function del($userId){
	
		if(empty($userId))throw new MyException('用户ID不能为空', 101);
	
		$rs = Table_user::del($userId);
		if($rs == 1){
			$msg = '删除用户('.$userId.')成功!';
			Userlog::add($msg);
				
			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 102);
		}
	}
	/**
	 * User::edit() 修改用户信息
	 *
	 * @param integer $id      用户ID
	 * @param string  $account 账号
	 * @param integer $group   群组
	 *
	 * @return
	 */
	static public function edit($id, $account, $group){
	
		if(empty($id))throw new MyException('用户ID不能为空', 101);
		if(empty($account))throw new MyException('用户账号不能为空', 102);
		if(empty($group))throw new MyException('用户组不能为空', 103);
	
		//验证ID是否存在
		$user = Table_user::getInfoById($id);
		if(empty($user)) throw new MyException('用户不存在', 104);
	
		//验证账号是否改变，如果改变则需要检查账号的重复性
		if($user['account'] != $account){
			$user2 = Table_user::getInfoByAccount($account);
			if($user2) throw new MyException('账号已经存在', 105);
		}
	
		$rs = Table_user::edit($id, $account, $group);
		if($rs >= 0){
			$msg = '修改用户('.$id.')信息成功!';
			Userlog::add($msg);
	
			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 106);
		}
	}
	/**
	 * User::resetPwd()  重置密码
	 * @param integer  $id   用户ID
	 * @param string  $newpass   新密码
	 *
	 * @return
	 */
	static public function resetPwd($id, $newpass){
	
		if(empty($id))throw new MyException('用户ID不能为空', 101);
		if(empty($newpass))throw new MyException('新的密码不能为空', 102);
	
		if(ParamCheck::is_weakPwd($newpass)) throw new MyException('新密码太弱', 103);
	
		$pass = self::buildPassword($newpass);
	
		$rs = Table_user::updatePwd($id, $pass);
	
		if($rs == 1){
			$msg = '用户('.$id.')密码成功重置为'.$newpass.'。';
			Userlog::add($msg);
	
			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 104);
		}
	}
	
	/**
	 * User::updatePwd()      修改密码
	 *
	 * @param string  $oldpwd   旧密码
	 * @param string  $newpwd   新密码
	 *
	 * @return
	 */
	public function updatePwd($oldpwd, $newpwd){
	
		if(empty($oldpwd))throw new myException('旧密码不能为空', 101);
		if(empty($newpwd))throw new myException('新密码不能为空', 102);
		if(ParamCheck::is_weakPwd($newpwd)) throw new myException('新密码太弱', 104);
	
		$user = self::getInfoById($this->id);
	
		//验证密码是否正确
		$oldpass = self::buildPassword($oldpwd, $user['salt']);
		if($oldpass[0] != $user['password']){
			throw new myException('旧密码错误', 103);
		}
	
		//产生新密码
		$newpass = self::buildPassword($newpwd);
	
		//修改密码
		$rs = Table_user::updatePwd($this->id, $newpass);
		if($rs == 1){
			$msg = '修改密码成功';
	
			Userlog::add($msg);
			return action_msg($msg, 1);
		}else{
			throw new myException('操作失败', 444);
		}
	}
	/**
	 * User::getSession() 获得Session
	 *
	 * @return
	 */
	static public function getSession(){
		global $session_USERID;
	
		return $_SESSION[$session_USERID];
	}
	
	/**
	 * User::getGroupID() 获得管理组
	 *
	 * @return
	 */
	public function getGroupID(){
		return $this->gid;
	}
	
	public function getAccount(){
		return $this->account;
	}
	
	//检查是否拥有权限
	static function checkAuth($powerId, $auth){
		if(empty($powerId))throw new MyException('权限ID不能为空', 101);
		//if(empty($auth))throw new MyException('权限序列不能为空', 102);
	
		$powers = explode('|', $auth);
		if(in_array($powerId, $powers)) {
			return true;
		}else{
			die('无访问权限');
		}
	}
	
	
	
}



?>