<?php
include_once '../vendor/autoload.php';
include_once '../database/interface.php';
include_once 'token.php';
include_once 'navigation.php';


session_start();
$token = decode_token_or_quit($_SESSION['token']);


$lezione = get_lezione_expanded($_POST['id_lezione']);

$pdf = new \tFPDF();
$pdf->AddPage();

$pdf->AddFont('Arial','', 'DejaVuSans.ttf',true);
$pdf->AddFont('Arial','B', 'DejaVuSans-Bold.ttf',true);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $lezione['titolo'], 1, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 6, 'Docente:', 0);
$pdf->Cell(90, 6, $lezione['docente'], 0);
$pdf->Cell(20, 6, 'Data:', 0, 0);
$pdf->Cell(0, 6, $lezione['data'], 0, 1);

$pdf->Cell(20, 6, 'Classe:', 0);
$pdf->Cell(90, 6, $lezione['classe'], 0);
$pdf->Cell(20, 6, 'Ora:', 0, 0);
$pdf->Cell(0, 6, $lezione['ora'], 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Argomenti svolti', 0, 1);
$pdf->SetFont('Arial', '', 11);

foreach (get_argomenti_svolti($lezione['id']) as $a) {
  $pdf->Cell(4, 6, '-', 0, 0);
  $pdf->MultiCell(0, 6, $a['argomento'], 0, 1);
}
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Studenti presenti', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, '                         Nome e cognome', 1, 0);
$pdf->Cell(-80, 10, 'Firma', 1, 1, 'C');

$pdf->SetFont('Arial', '', 11);
foreach (get_presenze_expanded($lezione['id']) as $p) {
  if ($p['presente'] == 1) {
    $pdf->Cell(0, 8, $p['studente'], 1, 0);
    $pdf->Cell(-80, 8, '', 1, 1, 'R');
  }
}
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Firma del docente: ___________________________', 0, 1, 'R');

$pdf->Output($lezione['titolo'] . '.pdf', 'I');