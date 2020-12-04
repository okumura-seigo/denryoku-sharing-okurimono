<?php

// セッションの有無
define( 'SESSION_USED', true );
// セッションIDの固定化
define( 'SESSION_FIXED', false );
// DBの常時接続
define( 'DB_FULL_CONNECT', true );

// 共通モジュール
require_once 'common.php';


// 個別モジュール

class MyPage {

	public function getMyPageData() {
		$login_user_id = $_SESSION[loginUserId];
		$points = $this->getPointDataByUserId($login_user_id);
		$messages = $this->getMessageDataByUserId($login_user_id);
		$projects = $this->getProjectHistoyByUserId($login_user_id);
		$project_lists = $this->getProjectList();

		foreach ($messages as $key => $val) {
			$disp_messages[$key] = $val;
		}
		$mypage_datas['total_point'] = $points['total_point'];
		$mypage_datas['disp_messages'] = $disp_messages;

		foreach ($projects as $key => $val) {
			$disp_project[$key] = $val;
			$disp_project[$key]['project_start_day'] = date('Y-m-d',strtotime($project_lists[$val['project_id']]['start_day']));
			$disp_project[$key]['project_name'] = $project_lists[$val['project_id']]['project_name'];
			$disp_project[$key]['project_history_status'] = $val['project_history_status'];
		}
		$mypage_datas['disp_project'] = $disp_project;

		return $mypage_datas;
	}

	public function getPointHistoryData($page_no = 1) {
		$login_user_id = $_SESSION[loginUserId];
		$points = $this->getPointDataByUserId($login_user_id);
		$ofset = ($page_no-1)*3;
		$point_details = $this->getPointDetailDataById($login_user_id, $ofset);
		$point_division_list = $this->getPointDivisionData();
		$point_count = $this->getPointCount($points['point_id']);

		$point_datas['total_point'] = $points['total_point'];
		$point_datas['count'] = $point_count['count'];

		foreach ($point_details as $key => $val) {
			$point_datas['point_history'][$key] = $val;
			$point_datas['point_history'][$key]['point_division'] = $point_division_list[$val['point_division_id']]['atext'];
			$point_datas['point_history'][$key]['point'] = $val['point'];
		}

		return $point_datas;
	}

	public function getMessageData() {
		$login_user_id = $_SESSION[loginUserId];
		$messages = $this->getMessageDataByUserId($login_user_id);

		foreach ($messages as $key => $val) {
			$disp_messages[$key] = $val;
		}

		return $disp_messages;
	}

	public function getMessageListData($page_no = 1) {
		$login_user_id = $_SESSION[loginUserId];
		$limit_flg = 1;
		$offset = ($page_no-1)*5;
		$messages = $this->getMessageDataByUserId($login_user_id, $limit_flg, $offset);

		foreach ($messages as $key => $val) {
			$disp_messages[$key] = $val;
			$disp_messages[$key]['recieved_date'] = date('Y-m-d',strtotime($val['recieved_date']));
			$disp_messages[$key]['title'] = $val['title'];
		}

		return $disp_messages;
	}

	public function getMessageDetailByMessageId($message_id = null) {
		$login_user_id = $_SESSION[loginUserId];
		$message_detail = $this->getMessageDetailDataByMessageId($login_user_id, $message_id);


		$disp_message_detail = $message_detail;

		return $disp_message_detail;
	}

	public function getProjectHistoryListData($page_no = 1) {
		$login_user_id = $_SESSION[loginUserId];
		$limit_flg = 1;
		$offset = ($page_no-1)*5;

		$project_count = $this->getProjectHistoryCount($login_user_id);
		$projects = $this->getProjectHistoyByUserId($login_user_id, $limit_flg, $offset);
		$project_lists = $this->getProjectList();

		$disp_project['count'] = $project_count['count'];

		foreach ($projects as $key => $val) {
			$disp_project['project_history'][$key] = $val;
			$disp_project['project_history'][$key]['project_start_day'] = date('Y-m-d',strtotime($project_lists[$val['project_id']]['start_day']));
			$disp_project['project_history'][$key]['project_name'] = $project_lists[$val['project_id']]['project_name'];
			$disp_project['project_history'][$key]['project_history_status'] = $val['project_history_status'];
		}

		return $disp_project;
	}

	// DBからユーザーのポイントデータ取得
	public function getPointDataByUserId($login_user_id = null){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}

		$query = 'SELECT * FROM point WHERE user_id = ?';
		$data = $this->dbConnect($query, array($login_user_id));

		return $data[0];
	}

	// DBからユーザーのポイント詳細(履歴)データ取得
	public function getPointDetailDataById($point_id = null, $offset = 0){
		if(is_null($point_id)){
			echo 'error';
			return;
		}

		$query = 'SELECT * FROM point_detail WHERE user_id = ? ORDER BY point_detail_id DESC LIMIT 3 OFFSET '.$offset;
		$data = $this->dbConnect($query, array($point_id));

		return $data;
	}

	// DBからポイントの種別データ取得
	public function getPointDivisionData(){

		$query = 'SELECT * FROM point_division';
		$data = $this->dbConnect($query);

		foreach ($data as $key => $val) {
			$list[$val['point_division_id']] = $val['point_division']; 
		}

		return $list;
	}

	// DBからユーザーのポイント詳細(履歴)データの総カウント取得
	public function getPointCount($point_id = null){
		if(is_null($point_id)){
			echo 'error';
			return;
		}
		$query = 'SELECT COUNT(*) as count FROM point_detail WHERE user_id = ?';
		$data = $this->dbConnect($query, array($point_id));

		return $data[0];
	}

	// DBからユーザーのメッセージ(受信ボックス)の総カウント取得
	public function getMessageCount(){
		$login_user_id = $_SESSION[loginUserId];
		$query = 'SELECT COUNT(*) as count FROM message WHERE user_id = ?';
		$data = $this->dbConnect($query, array($login_user_id));

		return $data[0];
	}

	// DBからユーザーのプロジェクト履歴データの総カウント取得
	public function getProjectHistoryCount($login_user_id = null){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}
		$query = 'SELECT COUNT(*) as count FROM project_history WHERE user_id = ?';
		$data = $this->dbConnect($query, array($login_user_id));

		return $data[0];
	}

	// DBからユーザーのメッセージ(受信ボックス)データ取得
	public function getMessageDataByUserId($login_user_id = null, $limit_flg = 0, $offset = 0){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}

		if($limit_flg == 0) {
			$query = 'SELECT * FROM message WHERE user_id = ? ORDER BY message_id DESC LIMIT 3';
			$data = $this->dbConnect($query, array($login_user_id));
		} else {
			$query = 'SELECT * FROM message WHERE user_id = ? ORDER BY message_id DESC LIMIT 5 OFFSET '.$offset;
			$data = $this->dbConnect($query, array($login_user_id));
		}

		return $data;
	}

	// DBからメッセージ詳細データ取得
	public function getMessageDetailDataByMessageId($login_user_id = null, $message_id = null){
		if(is_null($login_user_id) || is_null($message_id)){
			echo 'error';
			return;
		}

		$query = 'SELECT * FROM message WHERE user_id = ? AND message_id = ?';
		$data = $this->dbConnect($query, array($login_user_id, $message_id));

		return $data[0];
	}

	// DBからプロジェクトデータ取得
	public function getProjectList(){

		$query = 'SELECT * FROM project';
		$data = $this->dbConnect($query, array());

		foreach ($data as $key => $val) {
			$data_lists[$val['project_id']] = $val;
		}

		return $data_lists;
	}

	// DBからプロジェクトデータ取得
	public function getProjectHistoyByUserId($login_user_id = null, $limit_flg = 0, $offset = 0){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}

		if($limit_flg == 0) {
			$query = 'SELECT * FROM project_history WHERE user_id = ? ORDER BY project_history_id DESC LIMIT 3 OFFSET '.$offset;
			$data = $this->dbConnect($query, array($login_user_id));
		}else{
			$query = 'SELECT * FROM project_history WHERE user_id = ? ORDER BY project_history_id DESC LIMIT 5 OFFSET '.$offset;
			$data = $this->dbConnect($query, array($login_user_id));
		}

		return $data;
	}

	/**
	 * @param storing $query
	 * @param array $datas
	 * @return array
	 */
	public function dbConnect($query = '', $datas = array()) {
		try {
			// DB接続
			$pdo = new PDO(
				'mysql:dbname='.DB_NAME.';host=localhost;charset=utf8mb4',
				DB_USERNAME,
				DB_PASSWORD,
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				]
			);
			// DBからデータ取得
			$stmt = $pdo->prepare($query);
			$stmt->execute($datas);
			$row = $stmt->fetchAll();

			return $row;

		} catch (PDOException $e) {
			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage()); 
		}
	}


}


class EditProfile {

	public function getProfile($edit_datas = array()) {
		$login_user_id = $_SESSION[loginUserId];
		$user_data = $this->getUserDataByUserId($login_user_id);

		if (!empty($edit_datas)) {
			foreach ($edit_datas as $key => $val) {
				$user_data[$key] = $val;
			}
		}

		return $user_data;
	}

	public function upDateProfile($edit_datas = array()) {
		$login_user_id = $_SESSION[loginUserId];
		$datas = $edit_datas;
		foreach ($datas as $key => $val) {
			if(!$val) {
				unset($edit_datas[$key]);
			}
		}
		$user_data = $this->upDateUserDataByUserId($login_user_id, $edit_datas);

		return $user_data;
	}

	// DBからユーザデータ取得
	public function getUserDataByUserId($login_user_id = null){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}

		$query = 'SELECT * FROM user WHERE user_id = ?';
		$data = $this->dbConnect($query, array($login_user_id));

		return $data[0];
	}

	// ユーザデータ更新
	public function upDateUserDataByUserId($login_user_id = null, $edit_datas = array()){
		if(is_null($login_user_id)){
			echo 'error';
			return;
		}

		$query = 'UPDATE user SET ';
		$count = count($edit_datas);
		$i = 1;
		foreach ($edit_datas as $key => $val) {
			$query .= $key.' = ?';
			$count > $i ? $query .= ', ' : '';
			$i++;
		}
		$query .= ' WHERE user_id = ?';

		$change = implode(',',$edit_datas);
		$change_data = explode(',',$change);
		$change_data[] = $login_user_id;

		$this->dbConnect($query, $change_data);

		return;
	}

	/**
	 * @param storing $query
	 * @param array $datas
	 * @return array
	 */
	public function dbConnect($query = '', $datas = array()) {
		try {
			// DB接続
			$pdo = new PDO(
				'mysql:dbname='.DB_NAME.';host=localhost;charset=utf8mb4',
				DB_USERNAME,
				DB_PASSWORD,
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				]
			);
			// DBからデータ取得
			$stmt = $pdo->prepare($query);
			$stmt->execute($datas);
			$row = $stmt->fetchAll();

			return $row;

		} catch (PDOException $e) {
			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage()); 
		}
	}
}
