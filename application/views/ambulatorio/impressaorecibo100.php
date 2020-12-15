 

<html>
    <head>
        <title>Recibo</title>
        
        <style>
            @media print { 
                .total{
                    background-color: silver; 
                    padding: 50px;
                    border:1px solid black;
                } 
                table{
                    border:1px solid black;
                    
                }
                 @page{
                     
                   size: auto;
                   margin:8mm;
                  } 
                  #div1{
                      height: 40%;
                    
                  }
                  
   table  {width: 100%;height: 100%;} 
            }
           
        </style>
        
    </head>
    <body>
        
        <?php 
        
   if ($empresapermissoes[0]->desativar_personalizacao_impressao == 'f') {  
    if (@file_exists("./upload/operadortimbrado/" . $exame['0']->medico_id . ".png")) {
        $caminho_background = base_url() . "upload/operadortimbrado/" . $exame['0']->medico_id . ".png";
        $timbrado = true;
    } elseif (file_exists("./upload/timbrado/timbrado.png")) {
        $caminho_background = base_url() . 'upload/timbrado/timbrado.png';
        $timbrado = true;
    } else {
        $timbrado = false;
    }
    ?>

    <? if ($timbrado) { ?>
        <div class="teste" style="background-size: contain; height: 70%; width: 100%;background-image: url(<?= $caminho_background ?>);">
        <? } ?>

        <? 
}
        
        
        
        
        @$cpf = $exame[0]->cpf;
        @$cep = $exame[0]->cep;
        @$numero = $exame[0]->numero;
        
        $formapagamento = "";
        $teste = "";
        $teste2 = "";
        $teste3 = "";
        $teste4 = "";
        
//        foreach ($exame as $item) : 
//            if ($item->forma_pagamento != null && $item->formadepagamento != $teste && $item->formadepagamento != $teste2 && $item->formadepagamento != $teste3 && $item->formadepagamento != $teste4) {
//                $teste = $item->formadepagamento;
//                $formapagamento .= $formapagamento . "/" . $item->nome_forma_pagamento1;
//            }
//            if ($item->forma_pagamento2 != null && $item->formadepagamento2 != $teste && $item->formadepagamento2 != $teste2 && $item->formadepagamento2 != $teste3 && $item->formadepagamento2 != $teste4) {
//                $teste2 = $item->formadepagamento2;
//                $formapagamento .= $formapagamento . "/" . $item->nome_forma_pagamento2;
//            }
//            if ($item->forma_pagamento3 != null && $item->formadepagamento3 != $teste && $item->formadepagamento3 != $teste2 && $item->formadepagamento3 != $teste3 && $item->formadepagamento3 != $teste4) {
//                $teste3 = $item->formadepagamento3;
//                $formapagamento .= $formapagamento . "/" . $item->nome_forma_pagamento3;
//            }
//            if ($item->forma_pagamento4 != null && $item->formadepagamento4 != $teste && $item->formadepagamento4 != $teste2 && $item->formadepagamento4 != $teste3 && $item->formadepagamento4 != $teste4) {
//                $teste4 = $item->formadepagamento4;
//                $formapagamento .= $formapagamento . "/" . $item->nome_forma_pagamento4;
//            } 
//        endforeach;
        
switch (date("m")) {
        case "01":    $mes = "Janeiro";     break;
        case "02":    $mes = "Fevereiro";   break;
        case "03":    $mes = "Março";       break;
        case "04":    $mes = "Abril";       break;
        case "05":    $mes = "Maio";        break;
        case "06":    $mes = "Junho";       break;
        case "07":    $mes = "Julho";       break;
        case "08":    $mes = "Agosto";      break;
        case "09":    $mes = "Setembro";    break;
        case "10":    $mes = "Outubro";     break;
        case "11":    $mes = "Novembro";    break;
        case "12":    $mes = "Dezembro";    break; 
 } 
   ?> 
        <div id="div1">  
        <table  style="" >
            <tr>
                <td style="text-align: center; width:33.33%;" >
               <img  width="90px" height="90px"   src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta[0]}" ?>">
                </td>
                <td style="text-align: center; width:33.33%; " ><b>RECIBO Nº 
                        <?php  echo @$examesgrupo[0]->agenda_exames_id;  ?>
                    </b> </td>
                <td style="text-align: center; width:33.33%;  "><div class="total"  > <b>R$ <?= $valor ?> &nbsp;&nbsp;&nbsp;</b> </div></td>
            </tr>
            <tr>
                <td colspan="3">Recebemos de <b><?= @$exame[0]->paciente; ?></b></td>
            </tr> 
            <tr>
                <td  colspan="3">A importancia de: <b class="total"><?= $extenso; ?> </b> </td>
            </tr> 
            <tr>
                <td colspan="3" style="height: 140px;" valign="top" ><?
                
               if(isset($examesgrupo)){
                       if($empresapermissoes[0]->faturamento_novo == "t"){   
                               foreach($examesgrupo as $item){
                                   echo $item->procedimento." - ". number_format(($item->valor_pago), 2, ',', '.')." ".$item->medico.", "; 
                               }
                       }else{
                               foreach($examesgrupo as $item){
                                   echo $item->procedimento." - ". number_format(($item->valor1 +  $item->valor2 + $item->valor3 + $item->valor4), 2, ',', '.')." ".$item->medico.", "; 
                               } 
                       }
                
                    }
                
                ?></td>
            </tr>
            
            <tr>
                <td style="height: 100px;" colspan="3" > </td>
            </tr>
            <tr>
                <td><b>CPF:</b> <?= preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf); ?> </td>
                <td><b>CEP:</b>  <?= $cep; ?></td>
                <td><b>Nº:</b>  <?= $numero; ?></td>
            </tr>
            
            <tr >
                <td style="border-top:1px solid black;"><b>Forma de Pagamento</b> </td>
                  <td style="border-top:1px solid black;"><b>Observações</b> </td>
                <td style="border-top:1px solid black; text-align: center;font-size: 12px;"><b><?= $empresa[0]->municipio; ?>, <?= date('d'); ?> DE <?= strtoupper($mes); ?> DE <?= date('Y')?> </b></td>
         
            </tr>
            
            <tr>
                <td style="font-size: 12px;"><?= $forma_pagamento; ?></td>
                <td style="font-size: 11px;"><?
                  if(isset($examesgrupo)){
                  foreach ($examesgrupo as $item) {
                        if($item->grupo = 'RETORNO' && $item->retorno_dias > 0){
                            echo "Retorno até ".$item->retorno_dias." dias.";
                        }  
                    }
                  }
                    ?> </td>
                  <td style=" text-align: center;font-size: 12px;"><b><?= $empresa[0]->nome; ?> <br> CNPJ: <?= preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empresa[0]->cnpj);  ?> </b></td>
            </tr>  
        </table>  
       </div>
        <br>
        
         <table>
            <tr>
                <td style="text-align: center; width:33.33%;" >
                <img  width="90px" height="90px"   src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta[0]}" ?>"></td>
                <td style="text-align: center; width:33.33%;" ><b>RECIBO Nº   <?php  echo @$examesgrupo[0]->agenda_exames_id;  ?></b> </td>
                <td style="text-align: center; width:33.33%;  "><div class="total"  > <b>R$ <?= $valor ?> &nbsp;&nbsp;&nbsp;</b> </div></td>
            </tr>
            <tr>
                <td colspan="3">Recebemos de <b><?= @$exame[0]->paciente; ?></b></td>
            </tr> 
            <tr>
                <td  colspan="3">A importancia de: <b class="total"><?= $extenso; ?> </b> </td>
            </tr> 
            <tr>
                <td colspan="3"  style="height: 140px;" valign="top"><? 
                if(isset($examesgrupo)){
                    if($empresapermissoes[0]->faturamento_novo == "t"){   
                            foreach($examesgrupo as $item){
                                echo $item->procedimento." - ". number_format(($item->valor_pago), 2, ',', '.')." ".$item->medico.", "; 
                            }
                    }else{
                            foreach($examesgrupo as $item){
                                echo $item->procedimento." - ". number_format(($item->valor1 +  $item->valor2 + $item->valor3 + $item->valor4), 2, ',', '.')." ".$item->medico.", "; 
                            } 
                    } 
                 } 
                ?></td>
            </tr>
            <tr>
                <td style="height: 100px;" colspan="3" > </td>
            </tr>
            <tr>
                <td><b>CPF:</b> <?= preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf); ?> </td>
                <td><b>CEP:</b>  <?= $cep; ?></td>
                <td><b>Nº:</b>  <?= $numero; ?></td>
            </tr>
            
            <tr>
                <td style="border-top:1px solid black;"><b>Forma de Pagamento</b> </td>
                <td style="border-top:1px solid black;"> <b>Observações</b></td>
                <td style="border-top:1px solid black; text-align: center;font-size: 12px;"><b><?= $empresa[0]->municipio; ?>, <?= date('d'); ?> DE <?= strtoupper($mes); ?> DE <?= date('Y')?> </b></td>
            </tr>
           
            <tr>
                <td style="font-size: 12px;"><?= $forma_pagamento; ?></td>
                <td style="font-size: 11px;"> <?
                    if(isset($examesgrupo)){
                      foreach ($examesgrupo as $item) {
                            if($item->grupo = 'RETORNO' && $item->retorno_dias > 0){
                                echo "Retorno até ".$item->retorno_dias." dias.";
                            }  
                        }
                    } 
                    ?></td>
                  <td style=" text-align: center;font-size: 12px;"><b><?= $empresa[0]->nome; ?> <br> CNPJ: <?= preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empresa[0]->cnpj);  ?> </b></td>
            </tr>  
        </table>
        
        
    <? if ($empresapermissoes[0]->desativar_personalizacao_impressao != 't' && $timbrado) { ?></div><? } ?>
    
        
    </body>
</html>
