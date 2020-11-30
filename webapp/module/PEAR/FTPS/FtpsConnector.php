<?php
class FtpsConnector{
	
	//変数を定義
	private $host;
	private $user;
	private $pass;
	private $port;
	private $conn_id;
	
	function __construct($host,$user,$pass,$port = 21){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->port = $port;
		
	}

	function connect(){
		$this->conn_id = ftp_ssl_connect($this->host,$this->port);
		return $this->conn_id;
	}
	
	//login
	function login(){
		//return ftp_login($this->conn_id, $this->user, $this->pass);
		//ftp_pasv($this->conn_id, true);
		return ftp_login($this->conn_id, $this->user, $this->pass);
		
	}
	function setPasv(){
		return ftp_pasv($this->conn_id, true);
	}
	
	//close
	function close(){
			return ftp_close($this->conn_id);		
	}
	
	function getFileNameList($remote_target_dir){
		
		ftp_chdir($this->conn_id, $remote_target_dir);
		return ftp_nlist($this->conn_id, '.');

		
	}
	

	/**
	 * ファイル名を変更
	 * @param String $base_name
	 * @param String $dest_name
	 */
	function rename($oldname,$newname){		
//		bool ftp_rename ( resource $ftp_stream , string $oldname , string  )
		return ftp_rename($this->conn_id, $oldname, $newname);
	}
	
	/**
	 * ファイル名に_finをつける。
	 * @param String $oldname
	 * @return boolean
	 */
	function renameFin($oldname){
		$newname = sprintf('%s_fin',$oldname);	
		return $this->rename($oldname, $newname);
	}
	
	/**
	 * バイナリモードでファイルダウンロード
	 * @param String $local_file
	 * @param String $remote_file
	 * @return boolean
	 */
	function get($local_file,$remote_file){
		return ftp_get ( $this->conn_id , $local_file , $remote_file , FTP_BINARY);	
	}
	
	/**
	 * バイナリモードでファイルダウンロード
	 * @param String $local_file
	 * @param String $remote_file
	 * @return boolean
	 */
	function put($local_file,$remote_file){
		return ftp_put ( $this->conn_id ,  $remote_file ,$local_file, FTP_BINARY);
	}
	
	function isConnect(){
		
		if(!empty($this->conn_id)){
			return true;
		}else{
			return false;
		}
	}
	

}