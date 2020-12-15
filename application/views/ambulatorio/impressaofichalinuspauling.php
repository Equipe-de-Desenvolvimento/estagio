<html>
    <head>
        <title></title>
        <style>
 @media print {
    @page  { 
            /*size: auto;    auto is the initial value */  
            /* this affects the margin in the printer settings */ 
              margin: 4mm 4mm 0mm 4mm;   
    } 

.fieldset-border {
  border: 1px groove black !important;
  padding: 0 0.5em 0.5em 0.5em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px black;
  /*box-shadow: 0px 0px 0px 0px black;*/
}

.fieldset-border .legend-border {
  font-size: 1.2em !important;
  text-align: left !important; 
  padding: 0 10px;
  border-bottom: none; 
}
 

} 
 </style>
    </head>
            <?php 
                $dataFuturo2 = date("Y-m-d");
                $dataAtual2 = $exame[0]->nascimento;
                $date_time2 = new DateTime($dataAtual2);
                $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
                if ($retirar_ordem_medico == 'f' && $retirar_medico_cadastro == 'f') {
                    $teste2 = $diff2->format('%Ya %mm %dd');
                } else {
                    $teste2 = $diff2->format('%Y');
                }
            ?>
    <body>
        <table  width="100%"  > 
            <tbody>
                <tr>
                    <td  style="text-align: center;border-bottom: 1px solid black;">
                          <img  width="80px" height="80px"  src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta[0]}" ?>">
                    </td> 
                </tr>
                <tr>
                    <td style="text-align: center;"><b>COMPROVANTE DE COLETA</b></td>  
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;"> </td>  
                 </tr>
                <tr>
                    <td style="text-align: center;"><u><b>SEU PROTOCOLO</b></u></td>  
                </tr>
                <tr>
                    <td style="text-align: center;"><b><?= $exame[0]->paciente_id; ?></b></td>  
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;"> </td>  
                </tr>
                <tr>
                    <td>Você foi atendido por: <?= $exame[0]->operador_cadastro; ?></td>  
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;"> </td>  
                </tr>
                <tr>
                    <td>Cliente: <?= $exame[0]->paciente; ?></td>  
                </tr>
                <tr>
                    <td>End:  <?= $exame[0]->logradouro_paciente; ?> - <?= $exame[0]->municipio_paciente; ?></td>  
                </tr>
                <tr>
                    <td>Sexo/Idade:  <?= $exame[0]->sexo; ?> / <?= $teste2; ?> Ano(s)</td>  
                </tr>
                <tr>
                    <td>Data coleta: <?= date('d/m/Y')?></td>  
                </tr>
                <tr>
                    <td>Data Prevista: <?
                    
                    if($exame[0]->data_entrega != "" && $exame[0]->data_entrega != "1969-12-31"){
                      echo  date('d/m/Y',strtotime($exame[0]->data_entrega)); 
                    } 
                    
                   
                    ?></td>  
                </tr>
                <tr>
                    <td>Convênio:   <?= $exame[0]->convenio; ?> </td>  
                </tr>
                <tr>
                    <td>CARTÃO FIDEL: <?= $exame[0]->convenionumero; ?></td>  
                </tr> 
               
            </tbody>
        </table>
        <fieldset class="fieldset-border">
            <legend class="legend-border"><b>Resultado On-Line</b></legend>
           <table>
               <tr>
                   <td>Acesse: <?= $exame[0]->link_sistema_paciente; ?></td>
               </tr>
               <tr>
                   <td>Chave: <?= $exame[0]->paciente_id; ?></td>
               </tr>
               <tr>
                   <td>Senha: <?= $exame[0]->agenda_exames_id; ?></td>
               </tr>
           </table>
        </fieldset>
        <table   width="100%">
            <tr>
                <td>Observações: 
                    <?php 
                    $cont_i = 0;
                    foreach ($examesgrupo as $item) : ?>
                         
                        <?
                        $cont_i++;
                        if(count($examesgrupo) != $cont_i){
                           echo $item->observacoes.", ";
                        }else{
                           echo $item->observacoes;
                        } 
                        ?>
                    
                        
                        
                      <?php endforeach;?>

                </td>
            </tr>
            <tr>
                <td><b><u>Exames</u></b>  </td>
            </tr>
            <tr>
                <td> &nbsp;&nbsp;
                    <?php
                  
                    
                    $valor_total_ficha = 0;
                    $desconto_total = 0;
                    $cartao_total = 0;
                    $cont_exames = 0;
                    foreach ($formapagamento as $value) {
//                        var_dump($formapagamento);die;
                        $data[$value->nome] = 0;
                        $datacredito[$value->nome] = 0;
                        $numerocredito[$value->nome] = 0;
                        $descontocredito[$value->nome] = 0;
                        $numero[$value->nome] = 0;
                        $desconto[$value->nome] = 0;
                    } 
                    foreach ($examesgrupo as $item) :
                        $cont_exames++;
                        $u = 0;  
                        // if ($item->grupo == $exame[0]->grupo) {
                            foreach ($formapagamento as $value) {
                                if ($item->formadepagamento == $value->nome) {
                                     $data[$value->nome] = $data[$value->nome] + $item->valor1;
                                    $numero[$value->nome] ++;
                                    if ($u == 0) {
                                        $desconto[$value->nome] = $desconto[$value->nome] + $item->desconto;
                                    }
                                    if ($item->desconto != '') {
                                        $u++;
                                    }
                                }
                            }
                            foreach ($formapagamento as $value) {
                                if ($item->formadepagamento2 == $value->nome) {
                                    $data[$value->nome] = $data[$value->nome] + $item->valor2;
                                    $numero[$value->nome] ++;
                                    if ($u == 0) {

                                        $desconto[$value->nome] = $desconto[$value->nome] + $item->desconto;
                                    }
                                    if ($item->desconto != '') {
                                        $u++;
                                    }
                                }
                            }
                            foreach ($formapagamento as $value) {
                                if ($item->formadepagamento3 == $value->nome) {
                                    $data[$value->nome] = $data[$value->nome] + $item->valor3;
                                    $numero[$value->nome] ++;
                                    if ($u == 0) {

                                        $desconto[$value->nome] = $desconto[$value->nome] + $item->desconto;
                                    }
                                    if ($item->desconto != '') {
                                        $u++;
                                    }
                                }
                            }
                            foreach ($formapagamento as $value) {
                                if ($item->formadepagamento4 == $value->nome) {
                                    $data[$value->nome] = $data[$value->nome] + $item->valor4;
                                    $numero[$value->nome] ++;
                                    if ($u == 0) {

                                        $desconto[$value->nome] = $desconto[$value->nome] + $item->desconto;
                                    }
                                    if ($item->desconto != '') {
                                        $u++;
                                    }
                                }
                            }
 
                            $valor_total_ficha = $valor_total_ficha + ($item->valor_total * $item->quantidade);
                            $desconto_total = $desconto_total + $item->desconto; 
                           
                        ?>
                    
                    
                       <b><?= $item->procedimento; ?></b> <br>
                      
                      
                    <?php  endforeach;  ?>
                </td>
            </tr>
            <tr>
                  <td style="border-bottom: 1px solid black;"> </td>  
            </tr>
            <tr>
                <td>Exames(s) QTD: <?= $cont_exames; ?></td>
            </tr>
            <tr>
                <td>Desconto: <?=  number_format($desconto_total, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Total a pagar: <?=  number_format($valor_total_ficha, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Deve: <?= number_format($examesgrupo[0]->valortotal - $desconto_total - $valor_total_ficha, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Total pago: <?= number_format($valor_total_ficha, 2, ",", "."); ?> </td>
            </tr>
            <tr>
                   <td style="border-bottom: 1px solid black;"> </td>  
            </tr>
            <tr>
                 <td style="text-align: center;"><b>ATENÇÃO!</b></td>
            </tr>
            <tr>
                <td><b>Imprescidível a apresentação deste comprovante ou o RG para entrega dos resultados</b></td>
            </tr>
            <tr>
                    <td style="border-bottom: 1px solid black;"> </td>  
             </tr>
             <tr>
                <td style="text-align: center;"><b>NOSSOS ENDEREÇOS</b></td>
            </tr>
            
            <?php foreach($todasempresa as $item){
             $codigoUF = $this->utilitario->codigo_uf($item->codigo_ibge);
                ?>
             <tr>
                 <td><?= $item->nome; ?></td>
             </tr> 
             <tr>
                 <td><?= $item->logradouro; ?>  <?= $item->numero; ?> - <?= $item->bairro; ?> </td>
             </tr> 
             <tr>
                 <td><?= $item->municipio; ?> - <?= $codigoUF; ?> | CEP:  <?= $item->cep; ?> </td>
             </tr> 
            <?php }?>
             
              <tr>
                <td style="text-align: center;">Telefone: <?= $examesgrupo[0]->telefone; ?> </td>
             </tr> 
             <tr>
                <td style="text-align: center;">Whatsapp: <?= $examesgrupo[0]->celular; ?> </td>
             </tr>
             <tr>
                <td style="text-align: center;"> <u> <?= $exame[0]->link_sistema_paciente; ?></u> </td>
             </tr>  
            
        </table>

    </body>
</html>
