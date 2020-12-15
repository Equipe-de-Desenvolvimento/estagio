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
  
<style>
    body{
       padding:0; margin:0;
    }
    #desc{
        font-size: 12px;
    }
</style>
 
<table border="0" style=" width: 100%; height: 100%;  "  >
    
    <tr>
        <td style="text-align:left" colspan="1" valign="top" style="width: 50%;  ">
            
            <div id="esquerdo" style="  text-align:left;">
                
                    <?
                    if ($arquivo_existe) {
                        ?> 
                        <img width="300px" height="50px" src="<?= base_url() . "upload/operadorLOGO/" . $medico_parecer1 ?>" />
                    <? } ?>
               
           
                
               <!--CABEÇALHO-->
                
                    <table  >
                        <tr>
                            <td ><span class="endereco_menor"> Paciente:&nbsp; <? echo $laudo[0]->paciente; ?></span></td>
                        </tr>
                        <tr >
                            <td ><span class="endereco_menor">Endere&ccedil;o: &nbsp; <? echo strtoupper($laudo[0]->logradouro); ?>,&nbsp;<? echo strtoupper($laudo[0]->numero) . ' ' . strtoupper($laudo[0]->bairro); ?>, <?= strtoupper($laudo[0]->cidade . ' - ' . $laudo[0]->estado); ?></span> </td>
                        </tr>
                    </table>
               
               
               <div id="content">
                    <table style="width: 50%;  "  >
                        <tr valign="top" >
                            <td  id="desc">Prescri&ccedil;&atilde;o: &nbsp;<? echo $laudo[0]->texto; ?> 
                            </td>
                        </tr>
                    </table>
                    <div style=" width: 530px;height: 100px;">
                        
                    </div>
                </div>
                  
            </div>
        </td> 
        <td colspan="1" valign="top" style="text-align: left;" >  
                  <?
                    if ($arquivo_existe) {
                        ?>
                     <center>
                        <img width="300px"  height="50px" src="<?= base_url() . "upload/operadorLOGO/" . $medico_parecer1 .'.jpg' ?>" />
                     </center>
                    <? } ?> 
                <div>
                     <table style="width: 491px;"  >
                        <tr>
                            <td ><span class="endereco_menor"> Paciente:&nbsp; <? echo $laudo[0]->paciente; ?></span></td>
                        </tr>
                        <tr >
                            <td ><span class="endereco_menor">Endere&ccedil;o: &nbsp; <? echo strtoupper($laudo[0]->logradouro); ?>,&nbsp;<? echo $laudo[0]->numero . ' ' .  strtoupper($laudo[0]->bairro); ?>, <?=  strtoupper($laudo[0]->cidade) . ' - ' .  strtoupper($laudo[0]->estado); ?></span> </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table style="width: 491px; " >
                        <tr valign="top">
                            <td id="desc" >Prescri&ccedil;&atilde;o: &nbsp;<? echo $laudo[0]->texto; ?> 
                            </td>
                        </tr> 
                    </table>
                    <div style=" width: 530px;height: 100px;"> 
                    </div>
                </div>  
        </td>
    </tr>
    <!--Rodape-->
     
</table>



<style>
    .endereco_menor{
        font-size: 12px;
    }
</style>
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
