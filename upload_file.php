<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content=""> 

    <title>KindleNote</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <link rel="stylesheet" href="css/jquery.fileupload-ui.css">

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</head>
<body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">KindleNote</a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">

          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="export.php">Export</a></li>
            <li><a href="about.php">About</a></li>     
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <!-- Begin page content -->
    <div class="container">

      <div class="page-header">
        <h1>Upload Here</h1>
      </div>
      
      <blockquote>Your My Clippings.txt can be found in....
      </blockquote>
		<?php
			include 'conn.php';
			include_once 'func.php';

			clearFile();


		    /* 设定上传目录 */
		    $dest_dir='uploads';
		 
		    /* 检测上传目录是否存在 */
		    if( !is_dir($dest_dir) || !is_writeable($dest_dir) )
		    {
		        die("上传目录 ".$dest_dir." 不存在或无法写入");
		    }
		 
		    /* 设置允许上传文件的类型 */
		    $type=array("rar","zip","txt","c");
		    
		    /* 获取上传文件信息 */
		    $upfile= $_FILES['file'];
		 
		    /* 获取文件后缀名函数 */
		    function fileext($filename)
		    {	
		        return substr(strrchr($filename, '.'), 1);
		    }

		    /* 判断上传文件类型 */
		    if( !in_array( strtolower( fileext($upfile['name'] ) ),$type) )
		     {
		        $text=implode(",",$type);
		        echo "对不起，您只能上传以下类型文件: ",$text,"<br>";
		     }
		     else
		     {
		        /* 设置文件名为"日期_用户名_文件名" */
		        $dest=$dest_dir.'/'.date("ymdHis")."_".$upfile['name'];
		 
		        /* 移动上传文件到指定文件夹 */
		        $state=move_uploaded_file($upfile['tmp_name'],$dest);
		 
		        if ($state)
		        {

		        	$db   = connectDB();

		        	$filename = date("ymdHis")."_".$upfile['name'];
		        	$sql = "INSERT INTO file VALUES ('".$filename."')";
		            $result = $db->query($sql);
		            if ($result) {
		            	print("存入数据库成功!");
		?>
		            <a href=export.php>开始导出</a>
		<?php
		            }else{
		            	print("存入数据库失败!");
		            }
		
		            //print("文件名：".$dest."<br>");
		            //print("上传的文件大小：".( round($upfile['size'] / 1024,2) )." KB<br>");
		        }
		        else
		        {
		            /* 处理错误信息 */
		            switch($upfile['error'])
		            {
		                case 1 : die("上传文件大小超出 php.ini:upload_max_filesize 限制<br>");
		                case 2 : die("上传文件大小超出 MAX_FILE_SIZE 限制<br>");
		                case 3 : die("文件仅被部分上传<br>");
		                case 4 : die("没有文件被上传<br>");
		                case 5 : die("找不到临时文件夹<br>");
		                case 6 : die("文件写入失败<br>");
		            }
		        }
		     }
		 
		?>
	</div><!-- container-->

	<div class="footer">
      <div class="container">
        <p class="text-center">Copyright @ Zchan</p>
      </div>
    </div>


</body>
</html>