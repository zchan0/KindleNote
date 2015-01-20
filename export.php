<?php
    include 'func.php';
?>

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

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

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
    <div class="container-fluid">

    <?php
        include_once 'func.php';
        $db = connectDB();

        //  从文件中读取数据并分割，然后存入数据库
        $dest_dir = 'uploads';
    	$sql      = "SELECT * FROM file";
        $result   = $db->query($sql);
        if ($result) {
        	$row  = $result->fetch_assoc();   	
            $path = $dest_dir.'/'.$row['filename'];
        	$fp   = fopen($path, 'rb');
        	if (!$fp) {
        		echo "open file error";
        		exit;
        	}
            //extractSave($fp);
                       
        }else
        	echo "select query error.";
        
        //  从数据库中读取数据并显示       
    ?>

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        书名<br>
                        by 作者
                    </div>
                    <div class="col-md-4 hidden-xs">
                        时间<br>
                        位置
                    </div>
                </div>
            </div><!--heading-->
            <div class="panel-body">
             内容   
            </div><!--body-->
        </div>
    </div>

	</div><!-- container-->

	<div class="footer">
      <div class="container">
        <p class="text-center">Copyright @ Zchan</p>
      </div>
    </div>

</body>
</html>