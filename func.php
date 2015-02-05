<?php	
error_reporting(E_ALL^E_WARNING);

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

function clearFile()
{
	$db   = connectDB();

	// 如果已经上传过文件，删除对应的文件
	$dest_dir = 'uploads';
	$sql      = "SELECT * FROM file";
    $result   = $db->query($sql);
    if ($result) {
    	$row  = $result->fetch_assoc();   	
        $path = $dest_dir.'/'.$row['filename'];
        unlink($path);

        $link = "TRUNCATE file";	// 清空表file
		$res  = $db->query($link);
    }
	
}


function clearNote()	
{
	$db   = connectDB();
	$link = "TRUNCATE note";
	$res  = $db->query($link);
}

/*
 *	按格式分割数据，存入数据库	
 */
function extractSave($fp)
{
	$db = connectDB();

	$note        	   = 'Note';
	$note_zh           = '笔记';
	$bookmark    	   = "Bookmark";
	$bookmark_zh	   = '书签';
	$loc_delimiter     = 'Loc.';
	$loc_delimiter_zh  = '#';
	$type_delimiter	   = '的';
	$time_delimiter    = '| Added on ';
	$time_delimiter_zh = '| 添加于 ';
	$bname_note		   = array();

    $row_cnt = 0;
	//	读取文件内容
	while (!feof($fp)) {
		$line = fgetss($fp);
        switch ($row_cnt) {
            case 0:
                $row_cnt ++;
                $first    = explode('(', $line);
                $bname    = $first[0];
                $author   = $first[1];
                $author   = explode(')', $author);
                $author   = trim($author[0]);
                break;
            case '1':
                $row_cnt ++;
                if( !strpos($line, $time_delimiter_zh)){
                	$second   = explode($time_delimiter, $line);
	                $time     = $second[1];
	                $type_loc = explode($loc_delimiter, $second[0]);
	                $type     = trim($type_loc[0], '-');$type = trim($type);	// 清除字符串中的空格和'-'
	                $loc      = trim($type_loc[1]);
	            }else{
	            	$second_  = explode($time_delimiter_zh, $line);
	            	$time  	  = $second_[1];
	            	$type_loc = explode($loc_delimiter_zh, $second_[0]);
	            	$typeloc  = $type_loc[1];
	            	$tmp      = explode($type_delimiter, $typeloc);
	            	$loc  	  = trim($tmp[0]);
	            	$type     = trim($tmp[1]);
	            }
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
                if (($type == $note) || ($type == $note_zh)) {
                	$note_link = "SELECT * FROM note WHERE bookname LIKE '%".$bname."%'";
                	$note_res  = $db->query($note_link);
                	while ($row = $note_res->fetch_assoc()) {
                		$db_loc = $row['location'];
                		$tmp_loc= explode('-', $db_loc);
                		$location= $tmp_loc[0];
                		if ($loc - $location <= 1) {
                			if ($row['note']){
                				$content = $row['note']."\n".$content;
                			}
                			$note_sql = "UPDATE note SET note = '".addslashes($content)."' 
                			WHERE bookname LIKE '%".$bname."%' AND location LIKE '%".$db_loc."%'";
                			//var_dump($note_sql);
                			$nres = $db->query($note_sql);
                		}
                	}
                }elseif (($type != $bookmark) && ($type != $bookmark_zh)) {
                	$link = "INSERT INTO note (time, location, content, type, bookname, author) VALUES ('".$time."', '".$loc."', '".$content."', '".$type."', '".$bname."', '".$author."' )";
	                $res  = $db->query($link);
                }
                
                break;
            
            default:
                break;
        }
	}
}


/*
 *	从数据库中提取数据并显示
 */

function showNote()
{
	$db  		 = connectDB();

	$sql_bname   = "SELECT DISTINCT bookname FROM note";
	$result      = $db->query($sql_bname);

	while ($row  = $result->fetch_assoc()) {
		$bname   = $row['bookname'];
		$flag	 = false;
		$sql 	 = "SELECT * FROM note WHERE bookname LIKE '%".$bname."%'";
		$res     = $db->query($sql);
		while ($list = $res->fetch_assoc()) {
			echo "<div class='col-md-3'>";
					if (!$flag) {
						echo "<h3><span class='label label-default'>".$bname."</span></h3>";
						$flag = true;
					}
			echo   "</div>";

			echo "<div class='col-md-8'>";
				echo	"<div class='panel panel-default'>";

			echo "<div class='panel-heading'>
                	<div class='row'>
                    	<div class='col-md-8 col-sm-12'>";
                        	echo "".$list['bookname']."<br>";
                        	echo "by ".$list['author']."<br>
                		</div>
                	</div>
		          </div><!--heading-->
		          <div class='panel-body'>
		                <div class='row'>
		                    <p class = 'text-right'><em>";
		                    	echo "时间 ".$list['time']."<br>位置 ".$list['location']."</em></p>
		                </div>
		                <div class='row'>
		                    <blockquote>
		                        <p>".$list['content']."<br></p>
		                    </blockquote>
		                </div>";

		               if ($list['note']) {
		                	echo "<div class='col-md-12'>
				                	<div class='hrtitle'> Notes</div>
				                	<hr>
				                	<em>".$list['note']."</em>		              
				                 </div>";
		                }
		                


		    echo    "</div><!--body-->
		        </div><!--panel-->
		    </div><!--col-->";
		}
	}


}

?>