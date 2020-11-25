<?php

require_once 'cms.php';

function getdirtree($col){
	$dir = $col[0];
   if(!is_dir($dir))return false;
   $tree = array();//戻り値用の配列

    if($handle=opendir($dir)){
        while(false !== $file = readdir($handle)){
            // 自分自身と上位階層のディレクトリを除外 (index.phpファイルも除外)
            if ($file != "." && $file != ".." && $file != "executor" && $file != "webapp"){
                if(is_dir($dir."/".$file)) {
                    // ディレクトリならば再帰呼出 
					$is_php = false;
                    list($tree[$file], $is_php) = getdirtree( array($dir."/".$file, $is_php) );
					if ($is_php == false) {
						unset($tree[$file]);
					}
                } else {
                    // ファイルならばパスを格納 
					if( preg_match( "/.*(\.php)$/i", $file ) ) {
                   	 $tree[ $file ] = $dir."/".$file; 
					 $is_php = true;
					}
				}
            }
        }
        closedir( $handle ); 
        @uasort($tree,"strcmp");// uasort() でないと添え字が失われます 
    }

    return @array($tree, $is_php);
} 

function showdirtree( $tree, $type = 0 ) 
{ 
    if( !is_array( $tree ) ) {   // 配列でなければ false を返す
        return false; 
	}
	
    static $count = 0;    // インデントの階層の深さ 
    $indent = ( $count ) ? str_repeat( "&nbsp;&nbsp;", $count ) : ""; 

    $count++; 
    foreach( $tree as $key => $value ) 
    { 
        if( is_array($value) ) 
        { 
            // 配列の場合ディレクトリ名を表示し再帰呼出 
						if ($type == 0) {
            	print( "<li><a href=\"#\">".$indent."+ " . $key . "</a></li>\n" ); 
            } else {
							print( "<li><a href=\"file.php?dir=".$key."\">".$indent."+ " . $key . "</a></li>\n" ); 
						}
            showdirtree( $value ); 
        } 
        else
        { 
            //pngのみアンカーをつけてファイル名を表示 
            print( "<li><a href=\"html.php?file=" . $value . "\">".$indent."- " . $key . "</a></li>\n" ); 
        } 
    } 
    $count--; 
    return true; 
}

?>