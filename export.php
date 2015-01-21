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
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

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
            <li><a href="export.php">Note</a></li>
            <li><a href="about.php">About</a></li>     
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <!-- Begin page content -->
    <div class="container">

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
        
        echo "<span class='label label-success'><a href='topdf.php'>导出为PDF</a></span>";

        //  从数据库中读取数据并显示
        showNote();       
    ?>

	</div><!-- container-->

	<div class="footer">
      <div class="container">
        <p class="text-center">Copyright © 2015 <a href="https://github.com/zchan0">Zchan</a></p>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</html>