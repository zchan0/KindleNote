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

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</head>
<body>


<?php
require('chinese.php');
require('func.php');

$db          = connectDB();
$sql_bname   = "SELECT DISTINCT bookname FROM note";
$result      = $db->query($sql_bname);

$bname_content = array();

while ($row  = $result->fetch_assoc()) {
    $bname   = $row['bookname'];
    $content_array = array();
    $flag    = false;
    $sql     = "SELECT * FROM note WHERE bookname LIKE '%".$bname."%'";
    $res     = $db->query($sql);
    $title   = null;
    while ($list = $res->fetch_assoc()) {
        if (!$flag) {
            //echo "<strong>".$bname.", ".$list['author']."</strong><br>";
            $title = $bname.", ".$list['author'];
            $flag = true;
        }
        //echo $list['content']."<br>Loc. ".$list['location']."<br><br>"; 
        $content = $list['content']."Loc. ".$list['location']."\n";
        $content_array[] = $content;
        
    }
    $bname_content[$title] = $content_array;
}

class PDF extends PDF_Chinese
{
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','B',10);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF;
$pdf->AddGBFont('sinfang','仿宋_GB2312'); 
$pdf->AddGBFont('simkai','楷体_GB2312'); 
$pdf->AliasNbPages();
$pdf->AddPage();
foreach ($bname_content as $title => $content_array) {
    $pdf->SetFont('simkai','U',15);
    $pdf->Write(10, iconv("utf-8", "gbk", $title."\n"));
    //foreach ($content_array as $content) {
    for ($i=0; $i < count($content_array); $i++) { 
        $content = $content_array[$i];
        $pdf->SetFont('sinfang','',11); 
        $pdf->Write(8, iconv("utf-8", "gbk", "(".($i+1).")\t".$content));
    }
    $pdf->Write(3, "\n");   // 两本书之间的空格
}

$pdf->Output();




?>


</body>
</html>