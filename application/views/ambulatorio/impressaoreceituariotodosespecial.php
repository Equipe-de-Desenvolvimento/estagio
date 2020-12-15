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
<style>
     #desc{
        font-size: 12px;
    }
</style>
 
    <?php if($permissao[0]->a4_receituario_especial == "t"){ ?>
<table style=" width: 100%;  "  >
    <tr>
        <td style=" ">
            
            <div id="esquerdo"   >
                
                    <?
                    if ($arquivo_existe) {
                        ?>
                        <img width="300px" height="50px" src="<?= base_url() . "upload/operadorLOGO/" . $medico_parecer1 ?>" />
                    <? } ?>
             
                <!-- <div > -->
                
                <!-- </div> -->
                <!-- <br> -->
                
               <!--CABEÇALHO-->
                
                <div  >
                    <table  >
                        <tr >
                            <td width="10px" colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table   >
                        <tr>
                            <td ><span class="endereco_menor"> Paciente:&nbsp; <? echo $laudo[0]->paciente; ?></span></td>
                        </tr>
                        <tr >
                            <td ><span class="endereco_menor">Endere&ccedil;o: &nbsp; <? echo $laudo[0]->logradouro; ?>,&nbsp;<? echo $laudo[0]->numero . ' ' . $laudo[0]->bairro; ?>, <?= $laudo[0]->cidade . ' - ' . $laudo[0]->estado; ?></span> </td>
                        </tr>
                    </table>
                </div>
                <div >
                    <table     >
                        <tr >
                            <td id="desc">Prescri&ccedil;&atilde;o: &nbsp;<? echo $laudo[0]->texto; ?>
                            <br>
               <br>
               <br></td>
                        </tr>
                    </table>
                </div>
                 
            </div>
        </td> 
      
    </tr>
</table>
    <?php }else{?>

<table style="text-align:left; width: 100%;">
    <tr>
        <td style="text-align:left">
            
            <div id="esquerdo" style="float:left; text-align:left;">
                <center>
                    <?
                    if ($arquivo_existe) {
                        ?>
                        <img width="300px" height="50px" src="<?= base_url() . "upload/operadorLOGO/" . $medico_parecer1 ?>" />
                    <? } ?>
                </center>
                <!-- <div > -->
                
                <!-- </div> -->
                <!-- <br> -->
                
               <!--CABEÇALHO-->
                
                <div style="float:left;" >
                    <table >
                        <tr >
                            <td width="10px" colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table style="width: 530px;">
                        <tr>
                            <td ><span class="endereco_menor"> Paciente:&nbsp; <? echo $laudo[0]->paciente; ?></span></td>
                        </tr>
                        <tr >
                            <td ><span class="endereco_menor">Endere&ccedil;o: &nbsp; <? echo $laudo[0]->logradouro; ?>,&nbsp;<? echo $laudo[0]->numero . ' ' . $laudo[0]->bairro; ?>, <?= $laudo[0]->cidade . ' - ' . $laudo[0]->estado; ?></span> </td>
                        </tr>
                    </table>
                </div>
                <div >
                    <table style="width: 530px;">
                        <tr >
                            <td id="desc">Prescri&ccedil;&atilde;o: &nbsp;<? echo $laudo[0]->texto; ?>
                            <br>
               <br>
               <br></td>
                        </tr>
                    </table>
                </div>
                 
            </div>
        </td>
        <td>

            <div id="direito" style="float:right;" >
                <center>
                    <?
                    if ($arquivo_existe) {
                        ?>
                        <img width="300px" height="50px" src="<?= base_url() . "upload/operadorLOGO/" . $medico_parecer1 .'.jpg' ?>" />
                    <? } ?>
                </center>
                
                <div>
                    <table>
                        <tr>
                            <td width="10px" colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div>
                     <table style="width: 530px;">
                        <tr>
                            <td ><span class="endereco_menor"> Paciente:&nbsp; <? echo $laudo[0]->paciente; ?></span></td>
                        </tr>
                        <tr >
                            <td ><span class="endereco_menor">Endere&ccedil;o: &nbsp; <? echo $laudo[0]->logradouro; ?>,&nbsp;<? echo $laudo[0]->numero . ' ' . $laudo[0]->bairro; ?>, <?= $laudo[0]->cidade . ' - ' . $laudo[0]->estado; ?></span> </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table style="width: 530px;">
                        <tr >
                            <td  id="desc">Prescri&ccedil;&atilde;o: &nbsp;<? echo $laudo[0]->texto; ?>
                              <br>
               <br>
               <br></td>
                        </tr>
                    </table>
                </div>
                
                
               
              
            </div>
        </td>
    </tr>
</table>

    <?php }?>

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