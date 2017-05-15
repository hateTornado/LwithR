<?php

/**
 * table_user.class.php 数据库表:用户
*
* @version       $Id$ v0.01
* @createtime    2014/9/3
* @updatetime    2016/2/27
* @author        dxl
* @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
*/

class Table_user extends Table{



	static protected function struct($data){
		$r = array();

		$r['id']         = $data['user_id'];
		$r['name']       = $data['user_name'];
		$r['account']    = $data['user_account'];
		$r['password']   = $data['user_password'];
		$r['salt']       = $data['user_salt'];
		$r['group']      = $data['user_group'];
		$r['loginip']    = $data['user_lastloginip'];
		$r['logintime']  = $data['user_lastlogintime'];
		$r['logincount'] = $data['user_logincount'];
		$r['addtime']    = $data['user_addtime'];

		return $r;
	}

	/**
	 * Table_user::getInfoByAccount() 根据账号获取详情
	 *
	 * @param string $acount 账号
	 *
	 * @return
	 */
	static public function getInfoByAccount($account){
		global $mypdo;

		$account = $mypdo->sql_check_input(array('string', $account));

		$sql = "select * from ".$mypdo->prefix."user where user_account = $account limit 1";

		$rs = $mypdo->sqlQuery($sql);
		if($rs){
			$r = array();
			foreach($rs as $key => $val){
				$r[$key] = self::struct($val);
			}
			return $r[0];
		}else{
			return 0;
		}
	}

	/**
	 * Table_user::getInfoById() 根据ID获取详情
	 *
	 * @param Integer $userId  用户ID
	 *
	 * @return
	 */
	static public function getInfoById($userId){
		global $mypdo;

		$userId = $mypdo->sql_check_input(array('number', $userId));

		$sql = "select * from ".$mypdo->prefix."user where user_id = $userId limit 1";

		$rs = $mypdo->sqlQuery($sql);
		if($rs){
			$r = array();
			foreach($rs as $key => $val){
				$r[$key] = self::struct($val);
			}
			return $r[0];
		}else{
			return 0;
		}
	}

	/**
	 * Table_user::updateLoginInfo() 登录时更新用户信息
	 *
	 * @param Integer $id 用户ID
	 *
	 * @return void
	 */
	static public function updateLoginInfo($id){

		global $mypdo;

		$ip = Env::getIP();
		$param = array(
				'user_lastloginip'   => array('string',$ip),
				'user_lastlogintime' => array('number',time()),
				'user_logincount'    => array('expression','user_logincount+1'),
		);
		$where = array('user_id'=>array('number',$id));

		return $mypdo->sqlupdate($mypdo->prefix.'user', $param, $where);
	}

	/**
	 * Table_user::edit() 修改用户账号和组信息
	 *
	 * @param Integer $id         用户ID
	 * @param string  $account    账号
	 * @param Integer $group      用户所属组
	 *
	 * @return
	 */
	static public function edit($id, $account, $group){

		global $mypdo;

		//参数
		$param = array (
				'user_account'   => array('string', $account),
				'user_group'     => array('number', $group)
		);
		$where = array('user_id'=> array('number', $id));

		return $mypdo->sqlupdate($mypdo->prefix.'user', $param, $where);
	}

	/**
	 * Table_user::add()  添加用户
	 *
	 * @param string  $account   用户账号
	 * @param array   $password  密码及salt
	 * @param Integer $group     用户组
	 *
	 * @return
	 */
	static public function add($account, $password, $group = 1){

		global $mypdo;

		//写入数据库
		$param = array (
				'user_account'   => array('string', $account),
				'user_password'  => array('string', $password[0]),
				'user_salt'      => array('string', $password[1]),
				'user_group'     => array('number', $group),
				'user_addtime'   => array('number', time())
		);
		return $mypdo->sqlinsert($mypdo->prefix.'user', $param);
	}
	 
	/**
	 * Table_user::del() 删除用户
	 *
	 * @param Integer $userId   用户ID
	 *
	 * @return
	 */
	static public function del($userId){

		global $mypdo;

		$where = array(
				"user_id" => array('number', $userId)
		);

		return $mypdo->sqldelete($mypdo->prefix.'user', $where);
	}

	/**
	 * Table_user::getList() 用户列表
	 *
	 * @param mixed $group      用户组类型
	 *
	 * @return
	 */
	static public function getList($group = 0){

		global $mypdo;
		$group   = $mypdo->sql_check_input(array('number', $group));

		$sql = "select * from ".$mypdo->prefix."user";
		if($group){
			$sql .= ' and user_group = '.$group;
		}

		$sql .=" order by user_id desc";
		$rs = $mypdo->sqlQuery($sql);
		if($rs){
			$r = array();
			foreach($rs as $key => $val){
				$r[$key] = self::struct($val);
			}
			return $r;
		}else{
			return 0;
		}
	}

	/**
	 * Table_user::updatePwd() 修改密码
	 *
	 * @param Integer $id        用户ID
	 * @param array   $newpass   新密码及salt
	 *
	 * @return
	 */
	static public function updatePwd($id, $newpass){

		global $mypdo;

		//修改参数
		$param = array(
				"user_password" => array('string', $newpass[0]),
				"user_salt"     => array('string', $newpass[1])
		);
		//where条件
		$where = array(
				"user_id" => array('number', $id)
		);
		//返回结果
		$r = $mypdo->sqlupdate($mypdo->prefix.'user', $param, $where);
		return $r;
	}

}
?>