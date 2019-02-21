<?php //echo $content_for_layout;  //exit;
App::import('Vendor', 'pdfcrowd');
$api = new \Pdfcrowd\HtmlToPdfClient("itishree", "bc8df73dbc7744e4909fec7c7d53f1a0");
$api->convertUrlToFile("http://210.212.2.52/uganda_dev/prisoners/view_pdf/1", "document.pdf");
echo $content_for_layout;
	//error_reporting(0); 
	// App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
	// $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); 
	// $pdf->SetCreator(PDF_CREATOR);
	// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	// // set auto page breaks
	// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// //$pdf->SetFont('helvetica', '', 10);
	// //$pdf->SetTextColor(80, 80, 80);
	// // set image scale factor
	// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// $pdf->AddPage();

	// $html = $content_for_layout;

	// //$html  = '<style>.span6{width:40%; float:left;color:green;}</style><div class="span6">itishree</div><div class="span6">debasmita</div>';
	// //echo $html; exit;
	// $pdf->writeHTML($html, true, false, true, false, '');

	// $pdf->SetFillColor(255,255,0);
	 
	// $pdf->lastPage();
	// $file_name = 'text.pdf';
	// ob_end_clean();
 //    $pdf->Output('example_061.pdf', 'I');
	// $pdf->Output(APP . 'webroot/files/pdf' . DS . $file_name, 'I');
 //    $pathName   = 'files/pdf/'.$file_name;
 //    $buffer   = file_get_contents($pathName);
 //    header("Content-Type: application/force-download");
 //    header("Content-Type: application/octet-stream");
 //    header("Content-Type: application/download");
 //    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 //    header("Content-Type: h(pdf");
 //    header("Content-Transfer-Encoding: binary");
 //    header("Content-Length: " .strlen($buffer));
 //    header("Content-Disposition: attachment; filename =".h($file_name));
 //    echo $buffer;
 ?>