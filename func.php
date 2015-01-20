<?php	

function connectDB()
{
	$db = new mysqli('localhost', 'root', '1a2b', 'KindleNote');
	date_default_timezone_set('PRC');
	if(mysqli_connect_error()){
		echo "Error:Could not connect to database.";
		exit;
	}
	return $db;
}

/*
 *	按格式分割数据，存入数据库	
 */
function extractSave($fp)
{
	$db = connectDB();

	$note           = '- Note';
	$bookmark       = '- Bookmark';
	$loc_delimiter  = 'Loc.';
	$time_delimiter = '| Added on ';

    $row_cnt = 0;
	//	读取文件内容
    //  可能要除掉文件最后一行的回车
	while (!feof($fp)) {
		$line = fgetss($fp);
        switch ($row_cnt) {
            case 0:
                $row_cnt ++;
                $first    = explode('(', $line);
                $bname    = $first[0];
                $author   = $first[1];
                $author   = explode(')', $author);
                $author   = $author[0];
                break;
            case '1':
                $row_cnt ++;
                $second   = explode($time_delimiter, $line);
                $time     = $second[1];
                $type_loc = explode($loc_delimiter, $second[0]);
                $type     = $type_loc[0];
                $loc      = $type_loc[1];
                break;
            case '2':
                $row_cnt ++;
                break;
            case '3':
                $row_cnt ++;
                $content  = $line;
                break;
            case '4':
                $row_cnt = 0;
                $link = "INSERT INTO note (time, location, content, type, bookname, author) VALUES ('".$time."', '".$loc."', '".$content."', '".$type."', '".$bname."', '".$author."' )";
                $res  = $db->query($link);
                /*
                if (!$res) {
                    echo "书名: ".$bname."<br>"." 作者: ".$author."<br>";
                    echo "类型: ".$type."<br>"." 位置: ".$loc."<br>"." 时间: ".$time."<br>";
                    echo "内容:".$content."<br>";
                    echo "<br><br>";
                    echo "插入失败<br>";
                }*/
                break;
            
            default:
                break;
        }
	}
}

/*
 *	从数据库中提取数据
 */



?>