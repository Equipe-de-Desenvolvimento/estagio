<html>
    <head>
        <meta charset="utf-8">
        <title>Relatório</title>
    </head>
        <body>
            <?php  
//               echo "<pre>";
//               print_r($relatorio);
               $ArrayEmpresas = Array();
               $ArrayConvenio = Array();
               $ArrayMedico = Array();
               
              foreach($relatorio as $value){
                  $ArrayEmpresas[$value->empresa_id] = Array("nome"=>$value->nome_empresa,"cnpj"=>$value->cnpj);
                  $ArrayConvenio[$value->convenio_id] = Array("nome"=>$value->convenio,"convenio_id"=>$value->convenio_id);
                  $ArrayMedico[$value->medico_parecer1] = Array("nome"=>$value->medico,"medico_parecer1"=>$value->medico_parecer1,"desconto_seguro"=>$value->desconto_seguro,"tipo_desc_seguro"=>$value->tipo_desc_seguro,"taxa_administracao"=>$value->taxa_administracao);
              }
             
            ?>
             
            <style>
                  tr:nth-child(even) {
                    background-color: #dfe4ea;
                     
                  }
                  tr{
                      font-family: arial;
                  }
                  th{
                     background-color: #57606f;
                     color:white;
                     font-family: arial;
                     font-size: 13px;
                  }
                  .paradireita{
                      text-align: right;
                  }
                  .td_especiais{
                      border-right: 1px solid silver;
                  }
                  /*RULES=rows*/
            </style>
            <table border=1 cellspacing=0 cellpadding=2   width="100%" RULES=rows >
                <tr>
                    <th>EMPRESA: <?
                                    if ($_POST['empresa'] <= 0) {
                                        echo "TODOS";
                                    }else{
                                        echo $empresa_permissao[0]->nome_empresa;
                                    }
                     ?> </th>                    
                        <?php 
                        foreach($ArrayConvenio as $convenio){                             
                            echo "<th colspan=2>".$convenio['nome']."</th>";                              
                        }
                        ?>
                    <th colspan=2>TOTAL</th>
                    <th width="140px" class="td_especiais">OUTROS CRÉDITOS E OUTROS DÉBITOS</th>
                    <th class="td_especiais">IMPOSTOS + TAXA ADMINISTRATIVA</th>
                    <th>DESCONTO SEGURO MÉDICO</th>
                    <th colspan="2">VALOR LÍQUIDO</th>
                </tr>
                 <tr>
                       <td colspan="<?= count($ArrayConvenio)*2 + 4;?>"  class="td_especiais" >CNPJ: <?
                       if ($_POST['empresa'] <= 0) {
                           echo "TODOS";
                       }else{
                           echo   preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5",$empresa_permissao[0]->cnpj);
                       }
                       
                       ?> </td>    
                       <td style="border-bottom:1px solid white;background-color: white;" ></td>
                       <td style="border-bottom:1px solid white;background-color: white;" ></td>
                       <td style="border-bottom:1px solid white;background-color: white;" ></td>
                       <td style="border-bottom:1px solid white;background-color: white;" ></td>
                </tr>
                <tr>
                    <td  colspan="<?= count($ArrayConvenio)*2 +  4;?>"  class="td_especiais" style="border-top: none;">&nbsp;</td>
                </tr>
                
               <?php 
               foreach($ArrayMedico as $medico){
                 ?>
                <tr>
                    <td><?= $medico['nome']?></td>
                           <?php 
                           $total_convenio = Array(); 
                           $totalgeralconvenio = 0;
                          
                        foreach($ArrayConvenio as $convenio){  
                           
                              $total = 0.00;
                              $total_convenio[$convenio['convenio_id']]['total'] = 0.00;
                              $valorpercentualmedico = 0;
                              $perc= 0;
                              $valor_total= 0;
                              $percpromotor= 0;
                              $valorpercentualpromotor= 0;
                              $simbolopercebtualpromotor= 0;
                              $totalconvenio=0;
                              $taxa_administracao=0;
                              
                             foreach($relatorio as $item){
                               
                                 if ($item->convenio_id == $convenio['convenio_id'] && $medico['medico_parecer1'] == $item->medico_parecer1 ) {
                                     
                         if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $valor_total = ($item->valor * $item->quantidade) - @$descontoAtual;                             
                         } else {
                             $valor_total = $item->valor_total;
                         }
                                     
                       if ($empresa_permissao[0]->promotor_medico == 't') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO
                                 
                                if ($item->percentual_promotor == "t") {
                                
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    
                                    $valorpercentualpromotor = $item->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valorpercentualpromotor * $item->quantidade;
                                }

                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($item->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $item->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    if ($item->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $item->valor_medico;

                                    $perc = $valorpercentualmedico * $item->quantidade;
                                    if ($item->valor_promotor != null) {
 
                                        $perc = $perc - $percpromotor;
                                    }
                                }
 
 
 
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES
                            
                                if ($item->percentual_medico == "t") {
                               
                                    $valorpercentualmedico = $item->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                } else {
                                       
                                    $valorpercentualmedico = $item->valor_medico;
 
                                    $perc = $valorpercentualmedico * $item->quantidade;
                                }
  
                                 
                            }
                                $totalconvenio += $perc;   
                                $taxa_administracao += $item->taxa_administracao;
                                $total_convenio[$item->convenio_id] = Array("total"=>$totalconvenio);
                                $totalgeralconvenio += $perc;
                             }  
                            }
                            echo "<td style='border-right: none;'>R$&nbsp;</td>";
                            echo "<td style='border-right: none;' class='paradireita'>". number_format($total_convenio[$convenio['convenio_id']]['total'], 2, ",", ".")."&nbsp;&nbsp;</td>";
                            
                        }
                        ?>
                    <td style="border-right: none;">R$</td>
                    <td style="border-left: none;" class='paradireita'><?= number_format($totalgeralconvenio, 2, ",", "."); ?></td>
                     <td  class="td_especiais"></td>
                     <td class="paradireita td_especiais"><?
                     
                            if ($medico['taxa_administracao'] == 0 || $medico['taxa_administracao'] == "") {
                                echo "";
                            }else{
                                echo "-".number_format($medico['taxa_administracao'], 2, ",", "."); 
                            }
                     ?></td>
                     <td class="td_especiais" ><center><?
                            $simbol = "";
                            
                            if ( $medico['desconto_seguro'] == 0 || $medico['desconto_seguro'] == "") {
                                echo "";
                            }else{
                            if ($medico['tipo_desc_seguro'] == "fixo") {                                            
                                 echo "-".number_format($medico['desconto_seguro'], 2, ",", ".");
                            }else{                                
                                $simbol = "%";  
                                echo "-".number_format((($medico['desconto_seguro']/100)*$totalgeralconvenio ), 2, ",", ".");
                            }     
                            }
                            ?>
                
                </center>
                    </td>
                     <td style="border-right: none;background-color: white;" >R$</td>                     
                     <td class="paradireita"  style="background-color: white; "><?= number_format($totalgeralconvenio-($medico['desconto_seguro'])-(($medico['desconto_seguro']/100)*$totalgeralconvenio ), 2, ",", ".");?></td>                     
                </tr>
              <? }?>                 
            </table>
                
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        </body>
</html>
