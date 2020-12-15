<?
$DIA = substr(date("Y-m-d"), 8, 2);
$MES = substr(date("Y-m-d"), 5, 2);

switch ($MES) {
    case 1 : $MES = 'Janeiro';
        break;
    case 2 : $MES = 'Fevereiro';
        break;
    case 3 : $MES = 'Mar&ccedil;o';
        break;
    case 4 : $MES = 'Abril';
        break;
    case 5 : $MES = 'Maio';
        break;
    case 6 : $MES = 'Junho';
        break;
    case 7 : $MES = 'Julho';
        break;
    case 8 : $MES = 'Agosto';
        break;
    case 9 : $MES = 'Setembro';
        break;
    case 10 : $MES = 'Outubro';
        break;
    case 11 : $MES = 'Novembro';
        break;
    case 12 : $MES = 'Dezembro';
        break;
        break;
}
//echo $DIA;
?>
<meta charset="UTF-8">
<!-- <title>Imp.. Receituário Especial</title> -->

<?php 
$medico_id  = $laudo[0]->medico_parecer1;
?>
  
<meta name="keywords" content="fixed table header; non-scroll header; fixed footer; freeze header; CSS tips; print repeating headers; print repeating footers">
 
 
<table style=" width: 100%; height: 100%;  padding:0; margin:0;"  border="0">
    <thead>
    <tr>
        <td colspan="2" style="text-align: center;"> RECEITUARIO DE CONTROLE ESPECIAL</td> 
    </tr>
     
                    <tr>
                        <td  >
                            <div   >
                                <table width="100%" border="1" style="border-collapse: collapse; text-align:left"  >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO EMITENTE</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Nome Completo: <? echo $laudo[0]->medico; ?></td>
                                    </tr>
                                     <tr>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>CRM:<? echo $laudo[0]->conselho; ?></td>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF:<? echo $laudo[0]->estado; ?></td>
                                         <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>No. _____ </td>
                                     </tr>
                                    <tr>
                                        <td  colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>End.: <span  style="font-size: 12px;"><? echo $laudo[0]->endempresa; ?> <? echo $laudo[0]->numeroempresa . '<br>' . $laudo[0]->bairroemp; ?> <? echo $laudo[0]->telempresa; ?> / <? echo $laudo[0]->celularempresa; ?></span></td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Cidade:&nbsp;<? echo $empresa[0]->municipio; ?></td>
                                        <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF: &nbsp;<? echo $empresa[0]->estado; ?></td>
                                    </tr> 
                                </table>
                            </div>
                        </td>
                        <td  >
                             <div  >
                             <table  border="0"  style="border-collapse: collapse" >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>1ª VIA: FARMACIA OU DROGARIA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>2ª VIA: ORIENTA&Ccedil;&Atilde;O AO PACIENTE</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>&nbsp;</td>
                                    </tr>
                                    <? if ($laudo[0]->carimbo == 't') {
                                        ?>
                                        <tr>
                                            <td><?= $laudo[0]->medico_carimbo ?></td>
                                        </tr>
                                    <? }
                                    ?>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>
                                        <!-- <center> <img  style="  width:100px; "  src="<?= base_url() . "upload/1ASSINATURAS/$medico_id" ?>.jpg"> </center> -->
                                        __________________________________________
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><center>Assinatura do médico</center></td>
                                    </tr>
                                </table>
                        </div>

                        </td>
                          
                         
                         
                    </tr>
    </thead>
     
    
</table>


 
<script>
    // set portrait orientation
    jsPrintSetup.setOption('orientation', jsPrintSetup.kLandscapeOrientation);
    // set top margins in millimeters
//   jsPrintSetup.setOption('marginTop', 15);
//   jsPrintSetup.setOption('marginBottom', 15);
//   jsPrintSetup.setOption('marginLeft', 20);
//   jsPrintSetup.setOption('marginRight', 10);
    // set page header
//   jsPrintSetup.setOption('headerStrLeft', 'Receituario Especial');
//   jsPrintSetup.setOption('headerStrCenter', '');
    jsPrintSetup.setOption('headerStrRight', '');
    // set empty page footer
//   jsPrintSetup.setOption('footerStrLeft', '');
//   jsPrintSetup.setOption('footerStrCenter', '');
    jsPrintSetup.setOption('scaling', 77.0);
    jsPrintSetup.setOption('shrinkToFit', 'false');
    // clears user preferences always silent print value
    // to enable using 'printSilent' option
    jsPrintSetup.clearSilentPrint();
    // Suppress print dialog (for this context only)
    jsPrintSetup.setOption('printSilent', 1);
    // Do Print 
    // When print is submitted it is executed asynchronous and
    // script flow continues after print independently of completetion of print process! 
    jsPrintSetup.print();
    // next commands
</script> 