<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Recibo</title>
<?php 

//echo "<pre>";
foreach ($formapagamento as $value) {
    $data[$value->nome] = 0;
    $datacredito[$value->nome] = 0;
    $dataequipe[$value->nome] = 0;
    $numerocredito[$value->nome] = 0;
    $descontocredito[$value->nome] = 0;
    $numeroequipe[$value->nome] = 0;
    $descontoequipe[$value->nome] = 0;
    $numero[$value->nome] = 0;
    $desconto[$value->nome] = 0;
  }
    $valor = 0;
    $valortotal = 0;
    $valorequipetotal = 0;
    $valor_total = 0;
      foreach ($recibo as $item) { 
   $participacao = $this->solicitacirurgia_m->listarprocedimentoorcamentoconveniofuncaocaixa($item->agenda_exames_id);
   
                     foreach ($participacao as $value) { 
                                $valor = $valor + $value->valor;
                                $valortotal = $valortotal + $value->valor;
                                ?>  
                                <? 
                                foreach ($formapagamento as $item2) {
                                    if ($item->forma_pagamento == $item2->nome) {
                                        $dataequipe[$item2->nome] = $dataequipe[$item2->nome] + $item->valor1;
                                        $numeroequipe[$item2->nome] ++;
                                    }
                                }
                                foreach ($formapagamento as $item2) {
                                    if ($item->forma_pagamento_2 == $item2->nome) {
                                        $dataequipe[$item2->nome] = $dataequipe[$item2->nome] + $item->valor2;
                                        $numeroequipe[$item2->nome] ++;
                                    }
                                }
                                foreach ($formapagamento as $item2) {
                                    if ($item->forma_pagamento_3 == $item2->nome) {
                                        $dataequipe[$item2->nome] = $dataequipe[$item2->nome] + $item->valor3;
                                        $numeroequipe[$item2->nome] ++;
                                    }
                                }
                                foreach ($formapagamento as $item2) {
                                    if ($item->forma_pagamento_4 == $item2->nome) {
                                        $dataequipe[$item2->nome] = $dataequipe[$item2->nome] + $item->valor4;
                                        $numeroequipe[$item2->nome] ++;
                                    }
                                }
                     }  
//                     if ($item->dinheiro == 't') {
                                $u = 0;
                                foreach ($formapagamento as $value) {
                                    if ($item->forma_pagamento == $value->nome) { 
                                        $data[$value->nome] = $data[$value->nome] + $item->valor1;
                                        $valor_total = $valor_total+$item->valor1;
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
                                    if ($item->forma_pagamento_2 == $value->nome) {
                                        $data[$value->nome] = $data[$value->nome] + $item->valor2;
                                        $valor_total = $valor_total+$item->valor2;
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
                                    if ($item->forma_pagamento_3 == $value->nome) {
                                        $data[$value->nome] = $data[$value->nome] + $item->valor3;
                                         $valor_total = $valor_total+$item->valor3;
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
                                    if ($item->forma_pagamento_4 == $value->nome) {
                                        $data[$value->nome] = $data[$value->nome] + $item->valor4;
                                        $valor_total = $valor_total + $item->valor4;
                                        $numero[$value->nome] ++;
                                        if ($u == 0) { 
                                            $desconto[$value->nome] = $desconto[$value->nome] + $item->desconto;
                                        }
                                        if ($item->desconto != '') {
                                            $u++;
                                        }
                                    }
                                }
//                            }
                            
                            if ($item->forma_pagamento == "" && $item->dinheiro == 't') {
                                $OUTROS = $OUTROS + $item->valor_total;
                                $NUMEROOUTROS++;
                            }
                             
                     
                     
                     
      }
           
//             print_r($dataequipe);
            
             
  $valor = number_format($valor_total, 2, ',', '.'); 
        if ($valor == '0,00') {
            $extenso = 'ZERO';
        } else {

            $valoreditado = str_replace(",", "", str_replace(".", "", $valor)); 
            $extenso = GExtenso::moeda($valoreditado);
            
        }
  ?>    
                      
                
 
<p><center><u><?= $empresa[0]->razao_social; ?></u></center></p>
<p><center><?= $empresa[0]->logradouro; ?> - <?= $empresa[0]->numero; ?> - <?= $empresa[0]->bairro; ?></center></p>
<p><center>Fone: (85) <?= $empresa[0]->telefone; ?> - (85) <?= $empresa[0]->celular; ?></center></p>
<p>
<p><center>Recibo</center></p>
<p>
<p><center>N&SmallCircle; PEDIDO:<?= $recibo[0]->agenda_exames_id; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VALOR:# <?= $valor; ?> &nbsp;#</center></p>
<p>
<p>Recebi de <?= $recibo['0']->paciente; ?>, a importancia de <?= $valor; ?> (<?= $extenso; ?>)  referente
    a   <? 
    $p = 0;
    foreach($recibo as $item){
        echo $item->procedimento;
        $p ++;
        if ($p > 0 && $p < count($recibo)) {
           echo " / ";
        }
    }
    ?></p>
  
<p>Recebimento atraves de: <?
$i = 0;
foreach($data as $f => $value){
    if ($value != "") {
        $i ++;
        if ($i > 1 && $i < count($data)) {
           echo " / ";
        }
       echo $f; 
        
    } 
   
   
} 
?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Categoria: <?= $recibo[0]->convenio; ?></p><p align="right"><?= $empresa[0]->municipio ?> </p>
<p>Atendente: <?= substr($recibo[0]->atendente, 0, 13); ?></p>

<br>
<h4><center>___________________________________________</center></h4>
<h4><center>Raz&atilde;o Social: <?= $empresa[0]->razao_social; ?></center></h4>
<h4><center>CNPJ: <?= $empresa[0]->cnpj; ?></center></h4>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
//    window.print();


</script>


