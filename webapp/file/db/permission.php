<?php
function orm_permission() {
return array(
	"0" => array("param_column" => "permission_id", "param_type" => "1"),
	"1" => array("param_column" => "permmison_name", "param_type" => "4"),
	"2" => array("param_column" => "contents", "param_type" => "6"),
	"3" => array("param_column" => "insert_datetime", "param_type" => "10"),
	"4" => array("param_column" => "update_datetime", "param_type" => "10"),
	"5" => array("param_column" => "stop_flg", "param_type" => "1"),
	"6" => array("param_column" => "delete_flg", "param_type" => "1"),
);
}
