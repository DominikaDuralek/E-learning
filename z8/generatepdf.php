<?php
$txtname = "files/" . $_GET['username'] . "_" . $_GET['testname'] . "_" . $_GET['datetime'] . ".txt";

$link = mysqli_connect(); //połączenie z BD
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD

require('fpdf185/tfpdf.php');
$pdf = new tFPDF();
$pdf->AddPage();
$row=file($txtname);
//$pdf->SetFont('Arial','B',12);
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',10);
$pdf->Cell(0,10,"Szczegóły Testu",1,1,'C');
foreach($row as $rowValue) {
	$data=explode(';',$rowValue);
	
	foreach($data as $columnValue){
		
		$pdf->Cell(0,10,substr($columnValue,0,-1),0,0,'');
		$pdf->SetTextColor(0, 0, 0);
		//$pdf->SetFont('Arial','',12);	
		$pdf->SetFont('DejaVu','',10);		
		$pdf->Ln(7);
	}
	
	if(array_search($rowValue, $row) % 8 == 1 && array_search($rowValue, $row) != 0){
	$pdf->Cell(0,10,'-----------------------------------------------------------------------------------------------------',0,0);
	$pdf->Ln(10);	
	}

}

$dir = 'files/';
$filename = $_GET['username'] . "_" . $_GET['testname'] . "_" . $_GET['datetime'] . ".pdf";

$file_path = $dir . $filename;

$questions = mysqli_query($link, "UPDATE result SET pdf_file='$file_path' WHERE pdf_file='$txtname';");

$pdf->Output();
$pdf->Output($dir.$filename,'F');

?>