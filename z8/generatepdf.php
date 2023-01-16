<?php
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}

//$txtname = "files/" . $_GET['username'] . "_" . $_GET['testname'] . "_" . $_GET['datetime'] . ".txt";

$link = mysqli_connect('', '', '', '');
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD

//require('fpdf185/tfpdf.php');
//$pdf = new tFPDF();
//$pdf->AddPage();
//$row=file($txtname);
//$pdf->SetFont('Arial','B',12);
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//$pdf->SetFont('DejaVu','',10);
//$pdf->Cell(0,10,"Szczegóły Testu",1,1,'C');
//foreach($row as $rowValue) {
//	$data=explode(';',$rowValue);
	
//	foreach($data as $columnValue){
		
//		$pdf->Cell(0,10,substr($columnValue,0,-1),0,0,'');
//		$pdf->SetTextColor(0, 0, 0);
		//$pdf->SetFont('Arial','',12);	
//		$pdf->SetFont('DejaVu','',10);		
//		$pdf->Ln(7);
//	}
	
//	if(array_search($rowValue, $row) % 8 == 1 && array_search($rowValue, $row) != 0){
	//$pdf->Cell(0,10,'-----------------------------------------------------------------------------------------------------',0,0);
	//$pdf->Ln(10);	
//	}

//}

//----------------TCPDF


//pdf
require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setFont('dejavusans', '', 10);


$htmlfile = $_SESSION['htmlfile']; //zawartosc pliku

$directory = '/files/';
$filename = $_GET['username'] . "_" . $_GET['testname'] . "_" . $_GET['datetime'] . ".pdf";
$txtname = $_GET['username'] . "_" . $_GET['testname'] . "_" . $_GET['datetime'] . ".txt";
$file_path = 'files/' . $filename;
$txt_path = 'files/' . $txtname;

$questions = mysqli_query($link, "UPDATE result SET pdf_file='$file_path' WHERE pdf_file='$txt_path';");

$pdf->SetTitle($filename);

$pdf->AddPage();
$pdf->writeHTML($htmlfile, true, false, true, false, '');
$pdf->lastPage();

$pdf->Output();
$pdf->Output(__DIR__ . $directory.$filename,'F');

//---------------------

?>