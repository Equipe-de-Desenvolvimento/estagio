<?php 
$medico_id  = $laudo[0]->medico_parecer1; 
$assinaturas = "false";

// echo '<pre>';
// print_r($laudo);
// die;

if(is_array($assinatura)){
    foreach($assinatura as $item){
        if ($item == $medico_id.".jpg") {
                if($laudo[0]->assinatura == 't'){
                    $assinaturas  = "true";
                }
        }
    } 
}   
$encoding = mb_internal_encoding();
?>

<?php if($permissao[0]->a4_receituario_especial == "t"){ ?>
<table border="" width="100%">
    <tr>
        <td colspan="2" style="text-align: center;"> RECEITUARIO DE CONTROLE ESPECIAL</td> 
    </tr>
    
                    <tr>
                        <td>
                            <div  >
                                <table border="1" style="border-collapse: collapse;"  width="100%" >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>IDENTIFICA&Ccedil;&Atilde;O DO EMITENTE</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Nome Completo: <? echo $laudo[0]->medico; ?></td>
                                    </tr>
                                     <tr>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>CRM:<? echo $laudo[0]->conselho; ?></td>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>UF:<? echo $laudo[0]->estado; ?></td>
                                         <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>No. _____ </td>
                                     </tr>
                                    <tr>
                                        <td  colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>End.: <? echo $laudo[0]->endempresa; ?> <? echo $laudo[0]->numeroempresa . '<br>' . $laudo[0]->bairroemp; ?> <? echo $laudo[0]->telempresa; ?> / <? echo $laudo[0]->celularempresa; ?> </td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>Cidade:&nbsp;<? echo $empresa[0]->municipio; ?></td>
                                        <td style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>UF: &nbsp;<? echo $empresa[0]->estado; ?></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td>
                        <td  >
                             <div  >
                             <table   style="border-collapse: collapse" >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>1ª VIA: FARMACIA OU DROGARIA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>2ª VIA: ORIENTA&Ccedil;&Atilde;O AO PACIENTE</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>&nbsp;</td>
                                    </tr>
                                    <? if ($laudo[0]->carimbo == 't') {
                                        ?>
                                        <tr>
                                            <td><?= $laudo[0]->medico_carimbo ?></td>
                                        </tr>
                                    <? }
                                    ?>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'> <center>
                                            <?php if($assinaturas == "true"){?>
                                        <img  style=" transform: rotate(270deg);  width:100px; "  src="<?= base_url() . "upload/1ASSINATURAS/$medico_id" ?>.jpg"> 
                                            <?php }else{
                                               echo "____________________________" ;
                                            }
                                         ?>
                                    </center></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 10pt;'><center>Assinatura do médico</center></td>
                                    </tr>
                                </table>
                        </div> 
                        </td> 
                    </tr>  
 </table> 
<?}else{?>
<table border="0" width="100%">
    <tr>
        <td colspan="2" style="text-align: center;"> RECEITUARIO DE CONTROLE ESPECIAL</td>
         <td colspan="3" style="text-align: center;"> RECEITUARIO DE CONTROLE ESPECIAL</td>
    </tr>
    
                    <tr>
                        <td valign="top" style=" text-align: right">
                            <div style=" text-align: left">
                                <table border="1" style="border-collapse: collapse; text-align:left"   >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO EMITENTE</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Nome Completo: <? echo $laudo[0]->medico; ?></td>
                                    </tr>
                                     <tr>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;width: 90%;'>CRM:<? echo $laudo[0]->conselho; ?></td>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF:<? echo $laudo[0]->estado; ?></td>
                                         <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>No. _____</td>
                                     </tr>
                                    <tr>
                                        <td  colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'> End.: <? echo mb_strtoupper($laudo[0]->endempresa, $encoding); ?> <? echo $laudo[0]->numeroempresa . '<br>' . mb_strtoupper($laudo[0]->bairroemp, $encoding); ?> <? echo mb_strtoupper($laudo[0]->telempresa, $encoding); ?> / <? echo $laudo[0]->celularempresa; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Cidade:&nbsp;<? echo mb_strtoupper($empresa[0]->municipio, $encoding); ?></td>
                                        <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF: &nbsp;<? echo $empresa[0]->estado; ?></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td>
                        <td  >
                             <div  >
                             <table  border="0"  style="border-collapse: collapse" >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 6.5pt;text-align: left;'>1ª VIA: FARMACIA OU DROGARIA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 6.5pt;'>2ª VIA: ORIENTA&Ccedil;&Atilde;O AO PACIENTE</td>
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
                                    <? }  ?>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>
                                    <center>
                                            <?php if($assinaturas == "true"){?>
                                        <img  style=" transform: rotate(270deg);  width:100px; "  src="<?= base_url() . "upload/1ASSINATURAS/$medico_id" ?>.jpg"> 
                                            <?php }elseif($laudo[0]->medico != ""){
                                                echo "<u><b style='font-size:6pt;'>".@$laudo[0]->descricao."<br>".@$laudo[0]->medico."</b></u>";
                                            }else{
                                               echo " ______________" ;
                                            }
                                         ?>
                                    </center>
                                  </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><center>Assinatura do médico</center></td>
                                    </tr>
                                </table>
                        </div>

                        </td>
                        <td style="text-align: right;" valign="top" > 
                            <div  >
                                <table border="1" style="border-collapse: collapse;   "  >
                                    <tr >
                                        <th  colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>IDENTIFICA&Ccedil;&Atilde;O DO EMITENTE</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Nome Completo: <? echo $laudo[0]->medico; ?></td>
                                    </tr>
                                     <tr>
                                         <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt; width: 76%;'>CRM:<? echo $laudo[0]->conselho; ?>  <br></td>
                                        <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF:<? echo $laudo[0]->estado; ?></td>
                                         <td colspan="1" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>No._____</td>
                                     </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;' width="100px"> End.: <? echo mb_strtoupper($laudo[0]->endempresa, $encoding); ?> <? echo $laudo[0]->numeroempresa . '<br>' . mb_strtoupper($laudo[0]->bairroemp, $encoding); ?> <? echo $laudo[0]->telempresa; ?> / <? echo $laudo[0]->celularempresa; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="2" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>Cidade:&nbsp;<? echo mb_strtoupper($empresa[0]->municipio, $encoding); ?></td>
                                        <td style='vertical-align: bottom; font-family: serif; font-size: 8pt;'>UF: &nbsp;<? echo $empresa[0]->estado; ?></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td>
                        <td style="text-align: left;" width="">
                            <div >
                                <table  border="0"  style="border-collapse: collapse" >
                                    <tr >
                                        <th colspan="3" style='vertical-align: bottom; font-family: serif; font-size:  6.5pt;'>1ª VIA: FARMACIA OU DROGARIA</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size:  6.5pt;'>2ª VIA: ORIENTA&Ccedil;&Atilde;O AO PACIENTE</td>
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
                                    <center>
                                            <?php if($assinaturas == "true"){?>
                                        <img  style=" transform: rotate(270deg);  width:100px; "  src="<?= base_url() . "upload/1ASSINATURAS/$medico_id" ?>.jpg"> 
                                            <?php }elseif($laudo[0]->medico != ""){
                                                    echo "<u><b style='font-size:6pt;'>".@$laudo[0]->descricao."<br>".@$laudo[0]->medico."</b></u>";
                                                  }else{
                                                    echo " ______________" ;
                                                  }
                                         ?>
                                    </center>
                                
                                </td>
                                   
                                    </tr>
                                    <tr>
                                        <td colspan="3" style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><center>Assinatura do médico</center></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        </td>
                    </tr>  
 </table>
 

<?php }?>


