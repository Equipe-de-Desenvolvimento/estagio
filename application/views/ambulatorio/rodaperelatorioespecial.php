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


<?php if($permissao[0]->a4_receituario_especial == "t"){ ?>
<table   width="100%"    >
    <tr>
        <td>
             <div >
                <? if(isset($semdata)){
                    
                    }else{?>
                    <table width="100%"  >  
                        <tr>
                            <td   style='vertical-align: bottom; font-family: serif; font-size: 10pt;'> Data: <?= date('d/m/Y', strtotime($laudo[0]->data_cadastro)); ?> </td>
                        </tr>
                    </table>
                    <?}?>
                </div>
            <div>
                    <table border="1" style="border-collapse: collapse" cellpadding="5px" width="100%"   >
                        <tr >
                            <th width="400px" colspan="2"   style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>IDENTIFICA&Ccedil;&Atilde;O DO COMPRADOR</th>
                            <th width="400px" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>IDENTIFICA&Ccedil;&Atilde;O DO FORNECEDOR</th>
                        </tr>
                        <tr  >
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 10pt; '>Nome:</td>
                            <td rowspan="4" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'></td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Ident.:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Órgão Emissor:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>End.:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Munic&iacute;pio:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>UF:</td>
                             <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'> </td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Telefone:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Ass. do Farmac&ecirc;utico - Data:</td>
                        </tr>
                    </table> 
                </div>
        </td>
        
    </tr>
</table>


<?}else{?>
<table  width="100%">
    <tr>
        <td>
             <div >
                    <table>
 
                        
                        <tr>
                            <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><center>Data:<?= date("d/m/Y"); ?></center></td>
                        </tr>
                    </table>
                </div>
              <div>
                    <table border="1" style="border-collapse: collapse" cellpadding="5px">
                        <tr >
                            <th colspan="2" width="270px" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO COMPRADOR</th><th width="270px" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO FORNECEDOR</th>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Nome:</td>
                            <td rowspan="4" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'></td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Ident.:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Órgão Emissor:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>End.:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Munic&iacute;pio:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Telefone:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Ass. do Farmac&ecirc;utico - Data:</td>
                        </tr>
                    </table> 
                </div>
        </td>
        <td style="text-align: right;">
             <div>
                    <table>  
                        <tr>
                            <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><center>Data:<?= date("d/m/Y"); ?></center></td>
                        </tr>
                    </table>
                </div>
              <div >
                    <table border="1" style="border-collapse: collapse" cellpadding="5px">
                        <tr >
                            <th colspan="2" width="270px" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO COMPRADOR</th><th width="270px" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO FORNECEDOR</th>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Nome:</td>
                            <td rowspan="4" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'></td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Ident.:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Órgão Emissor:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>End.:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Munic&iacute;pio:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF:</td>
                        </tr>
                        <tr>
                            <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Telefone:</td>
                            <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Ass. do Farmac&ecirc;utico - Data:</td>
                        </tr>
                    </table>
                   
                </div>
        </td>
    </tr>
</table>
<?php }?>