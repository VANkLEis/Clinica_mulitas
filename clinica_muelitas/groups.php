<?php
$content = "asd";
    require_once "vista/pdf/html2pdf/html2pdf.class.php";
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('groups.pdf');

?>
