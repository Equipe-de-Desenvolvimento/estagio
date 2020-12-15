<meta charset="utf-8">
<?php 

//echo "<pre>";

foreach($relatorioProducaoImp as $valueImp){
  array_push($relatorio, json_decode(json_encode(array("medico_parecer1"=>$valueImp->medico_id,
      "procedimento_tuss_id"=>$valueImp->procedimento_tuss_id,
      "convenio_id"=>$valueImp->convenio_id,
      "quantidade"=>$valueImp->quantidade,
      "desconto_seguro"=>$valueImp->desconto_seguro,
      "tipo_desc_seguro"=>$valueImp->tipo_desc_seguro,
      "valor_base"=>$valueImp->valor_base,
      "ir"=>$valueImp->ir,
      "pis"=>$valueImp->pis,
      "csll"=>$valueImp->csll,
      "cofins"=>$valueImp->cofins,
      "iss"=>$valueImp->iss,
      "cpf"=>$valueImp->cpf,
      "medico"=>$valueImp->medico,
      "valor_total"=>$valueImp->valor_total,
      "percentual_medico"=>$valueImp->percentual_medico,
      "valor_medico"=>$valueImp->valor_medico,
      "agenda_exames_id"=>-1,
      "valor"=>$valueImp->valor_total,
      "procedimento"=>$valueImp->procedimento,
      "convenio"=>$valueImp->convenio))));  
}


$convenios = Array(); 
$todos_medico = Array();
$todos_procedimento_medico = Array();
$todos_medicos_convenio= Array();

if (count($relatorio) > 0) {
$convenio_passado = 0;
foreach($relatorio as $item){ 
    $convenios[$item->convenio_id] = Array('convenio_id'=>$item->convenio_id,"convenio"=>$item->convenio); 
}  
$cont_c = 0;
foreach ($convenios as $conv){
$cont_c++;
$procedimentos = Array();
$tot = Array();
$totalprocedimento = Array();
$medicos = Array();
$descontoTotal = 0;
$descontoAtual = 0;
$totalperc=0;
$convenio = Array();
$medicos_convenio = Array();
$i = 0;
 $perc= 0 ;
 
foreach($relatorio as $value){
     
        
    if ($value->convenio_id == $conv['convenio_id']) {
        
        if ($value->convenio_id != $convenio_passado) {
             $totalpercm = Array(); 
             $totalMedicoCirurgico = 0;
             $totaliss  = 0;
             $totalcofins = 0;
             $totalcsll = 0;
             $totalpis = 0;
             $totalrrf = 0;
             $totalperc = 0;
             $pis= 0;
             $csll= 0;
             $cofins= 0;
             $iss= 0;
        } 
         $convenio_passado = $conv['convenio_id'];
       
        
                   if ($empresa_permissao[0]->faturamento_novo == 't') {
                        $descontoForma = $this->guia->listardescontoTotal($value->agenda_exames_id);
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                        }
                         if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $valor_total = ($value->valor * $value->quantidade) - @$descontoAtual;                             
                         } else {
                             $valor_total = $value->valor_total;
                         }
                           if ($empresa_permissao[0]->promotor_medico == 't') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO                                 
                                if ($value->percentual_promotor == "t") {                                
                                    $valorpercentualpromotor = $value->valor_promotor;
                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    
                                    $valorpercentualpromotor = $value->valor_promotor;
                                    $percpromotor = $valorpercentualpromotor *  $value->quantidade;
                                }
                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($value->percentual_medico == "t") {
                                    $simbolopercebtual = " %";
                                    $valorpercentualmedico = $value->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    if ($value->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $value->valor_medico;
                                    $perc = $valorpercentualmedico * $value->quantidade;
                                    if ($value->valor_promotor != null) { 
                                        $perc = $perc - $percpromotor;
                                    }
                                }
                                $totalperc += $perc;
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES                            
                                if ($value->percentual_medico == "t") {                               
                                    $valorpercentualmedico = $value->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                } else {                                       
                                    $valorpercentualmedico = $value->valor_medico; 
                                    $perc = $valorpercentualmedico * $value->quantidade;
                                }
                                 $totalperc += $perc;
                                 @$totalpercm{$value->medico_parecer1} += $perc;
          
                            }                    
    @$v_procedimento[$value->procedimento_tuss_id."".$value->convenio_id."".$value->medico_parecer1] += $perc; 
    @$totalprocedimento[$value->procedimento_tuss_id] += $value->valor_total;
    // echo '<BR><pre>';
    // print_r($totalprocedimento);
    // die;
    @$totalprocedimento_medico[$value->medico_parecer1."".$value->procedimento_tuss_id."".$value->convenio_id] += $perc;
    @$totalprocedimento_medico2[$value->procedimento_tuss_id] += $perc;
    // echo '<BR><pre>';
    //  print_r($totalprocedimento_medico2);

    //  die;
    @$tot[$value->procedimento_tuss_id] +=  $value->quantidade;
    $procedimentos[$value->procedimento_tuss_id] =  Array("nome"=>$value->procedimento,"quantidade"=>$tot[$value->procedimento_tuss_id],"valor_total"=>$totalprocedimento[$value->procedimento_tuss_id],"convenio_id"=>$value->convenio_id,"procedimento_tuss_id"=>$value->procedimento_tuss_id,"medico_id"=>$value->medico_parecer1);   
    // echo '<pre>';
    // print_r($procedimentos);
    // die;
    $medicos[$value->medico_parecer1] =  Array("medico"=>$value->medico,"cpf"=>$value->cpf,"medico_id"=>$value->medico_parecer1,"valor_base"=>$value->valor_base,"ir"=>$value->ir,"pis"=>$value->pis,"csll"=>$value->csll,"cofins"=>$value->cofins,"iss"=>$value->iss);
    @$todos_medicos[$value->medico_parecer1] =  Array("medico"=>$value->medico,"cpf"=>$value->cpf,"medico_id"=>$value->medico_parecer1,"valor_base"=>$value->valor_base,"ir"=>$value->ir,"pis"=>$value->pis,"csll"=>$value->csll,"cofins"=>$value->cofins,"iss"=>$value->iss);
    $convenio[$value->medico_parecer1][$value->convenio_id]  = Array("convenio"=>$value->convenio,"medico"=>$value->medico,"convenio_id"=>$value->convenio_id,"medico_id"=>$value->medico_parecer1);
    $valor_procedimento[$value->procedimento_tuss_id."".$value->convenio_id."".$value->medico_parecer1] = Array("procedimento_tuss_id"=>$value->procedimento_tuss_id,"procedimento"=>$value->procedimento,"convenio_id"=>$value->convenio_id,"valor"=>$v_procedimento[$value->procedimento_tuss_id."".$value->convenio_id."".$value->medico_parecer1],"medico_id"=>$value->medico_parecer1);
    $medicos_convenio[$value->medico_parecer1."".$value->convenio_id] = Array("convenio"=>$value->convenio,"medico"=>$value->medico,"convenio_id"=>$value->convenio_id,"medico_id"=>$value->medico_parecer1);
    $todos_medicos_convenio[$value->medico_parecer1."".$value->convenio_id] = Array("convenio"=>$value->convenio,"medico"=>$value->medico,"convenio_id"=>$value->convenio_id,"medico_id"=>$value->medico_parecer1);
    $procedimento_medico[$value->medico_parecer1."".$value->procedimento_tuss_id] =  Array("nome"=>$value->procedimento,"quantidade"=>$tot[$value->procedimento_tuss_id],"valor_total"=>$totalprocedimento_medico[$value->medico_parecer1."".$value->procedimento_tuss_id."".$value->convenio_id],"convenio_id"=>$value->convenio_id,"procedimento_tuss_id"=>$value->procedimento_tuss_id,"medico_id"=>$value->medico_parecer1,"desconto_seguro"=>$value->desconto_seguro,"tipo_desc_seguro"=>$value->tipo_desc_seguro);
    $todos_procedimento_medico[$value->medico_parecer1."".$value->procedimento_tuss_id] =  Array("nome"=>$value->procedimento,"quantidade"=>$tot[$value->procedimento_tuss_id],"valor_total"=>$totalprocedimento_medico[$value->medico_parecer1."".$value->procedimento_tuss_id."".$value->convenio_id],"convenio_id"=>$value->convenio_id,"procedimento_tuss_id"=>$value->procedimento_tuss_id,"medico_id"=>$value->medico_parecer1,"desconto_seguro"=>$value->desconto_seguro,"tipo_desc_seguro"=>$value->tipo_desc_seguro);
    $todos_procedimento_medico2[$value->medico_parecer1."".$value->procedimento_tuss_id] =  Array("nome"=>$value->procedimento,"quantidade"=>$tot[$value->procedimento_tuss_id],"valor_total"=>$totalprocedimento_medico2[$value->procedimento_tuss_id],"convenio_id"=>$value->convenio_id,"procedimento_tuss_id"=>$value->procedimento_tuss_id,"medico_id"=>$value->medico_parecer1,"desconto_seguro"=>$value->desconto_seguro,"tipo_desc_seguro"=>$value->tipo_desc_seguro);
    $i++;     
                    $valor = 0;  
                    $totalperchome = 0;
                    $totalgeralhome = 0;
                    $perchome = 0;
 
     
  } 
}
//  echo "<pre>";
//  print_r($procedimento_medico);
   
        $totalrrf = 0;
        $totalcofins = 0;
        $totalcsll = 0;
        $totaliss = 0;
        $totalpis = 0;
         $medico_passado = 0;
 foreach($medicos as $item){
        $medico =   $this->operador_m->listarCada($item['medico_id']);
        $valor_total = 0;
        $valorpercentualmedico= 0;
        $perc=0;
        $totalperchome= 0;       
        $totalgeralhome=0;
             
        $resultado = 0;      
        $perchome = 0;
        $pis= 0;
        $csll= 0;
        $cofins=0;
        $iss=0;
        $irpf = 0;
//        foreach ($relatorioProducaoImp as $item2) :
//            if ($item['medico_id'] == $item2->medico_id) {                 
//                 $valor_total = $item2->valor_total;
//                if ($item2->percentual_medico == "t") {
//                    $valorpercentualmedico = $item2->valor_medico;
//                    $perc = $valor_total * ($valorpercentualmedico / 100);
//                    $totalperchome = $totalperchome + $perc;
//                    $totalgeralhome = $totalgeralhome + $valor_total;
//                 } else {               
//                    $valorpercentualmedico = $item2->valor_medico;
//                    $perc = $valorpercentualmedico;
//                    $totalperchome = $totalperchome + $perchome;
//                    $totalgeralhome = $totalgeralhome + $valor_total;                
//                 }
//              }
//        endforeach;
        
        $relatoriocirurgico = $this->guia->relatoriocirurgicoextratomedico($item['medico_id'],$conv['convenio_id'],$data_inicial,$data_final,$grupo);
        
        if (count($relatoriocirurgico) == 0) {
             $totalMedicoCirurgico = 0;
             $totaliss  = 0;
             $totalcofins = 0;
             $totalcsll = 0;
             $totalpis = 0;
             $totalrrf = 0;
        } 
        
         foreach ($relatoriocirurgico as $itens) :  
                if ($medico_passado != $itens->medico_id) {
                   $totalMedicoCirurgico = 0; 
                }                
                $totalMedicoCirurgico += (float) $itens->valor_medico;  
            $medico_passado = $itens->medico_id;
         endforeach; 
      
         $totalperc += $totalMedicoCirurgico;
            
         $totalpercm{$item['medico_id']} += $totalMedicoCirurgico;
        
//        echo $item['medico_id']." - ";
//        echo $totalpercm{$item['medico_id']};
//        echo "<br>";  
             if ($totalperchome != 0) {
                   $totalperc = $totalperc + $totalperchome;
                   $totalpercm{$item['medico_id']} += $totalperchome;   
             }
             if ($totalpercm{$item['medico_id']} >= $medico[0]->valor_base) {   
                 $irpf = $totalpercm{$item['medico_id']} * ($medico[0]->ir / 100);
                 $resultado = @$totalpercm{$item['medico_id']} - @$irpf - @$taxaAdministracao;
                
             }             
             if ($totalpercm{$item['medico_id']} > 215) {
                 $pis = $totalpercm{$item['medico_id']} * ($medico[0]->pis  / 100);
                 $csll = $totalpercm{$item['medico_id']} * ($medico[0]->csll   / 100);
                 $cofins = $totalpercm{$item['medico_id']} * ($medico[0]->cofins  / 100);
                 $resultado = $resultado - $pis - $csll - $cofins;
                 $iss = $totalpercm{$item['medico_id']} * ($medico[0]->iss  / 100);
                 $resultado = $resultado - $iss; 
             }
             $totaliss += $iss;
             $totalcofins += $cofins;
             $totalcsll += $csll;
             $totalpis += $pis;
             $totalrrf += 0;   
     }    
?>
<html>
    <head>
         <meta charset="utf-8">
        <!--<title>Relatório</title>-->
        <style>
            td{
                font-family: arial;
            }
            #ladodireto{
                text-align: right;
            }#cabecalho{
                font-size: 12px;
                font-weight: normal;
               
            }.colunas{
                font-size:13px;
                padding: 10px;
                font-weight: bold;
            }
            th{
                background-color: silver;
                font-family: arial;
            }
        </style>       
    </head>
    <body>
        <table border='0'  width="100%" >
                <?
                if ($cont_c <= 1) {
                ?>
             <tr>
                <td style="border-bottom: 1px solid black;" >EXTRATO DA PRODUTIVIDADE MÉDICA</td>
                <td style="border-bottom: 1px solid black;" colspan="3">DT-HORA DO EXTRATO: <?   date_default_timezone_set('America/Fortaleza'); echo date('d/m/Y H:i'); ?></td>
             </tr> 
             <tr>
                 <td style="border-left: none;" colspan="4">Médico:</td>                
            </tr>
            <? }?>
        </table>
               <table   width="100%" style=" border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;" >
            <?php 
             if (count($medicos) == 1) {
                 foreach($medicos as $value){
                     $medico_id = $value['medico_id'];
                     ?>       
             <tr>
                <td> <?= $value['medico']; ?></td> 
                <td  style="text-align: right;">CPF: </td> 
                <td width="200px" > <?= @ereg_replace("([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})","\\1.\\2.\\3-\\4",$value['cpf']); ?>  &nbsp;</td> 
             </tr>
             <tr>
                   <td>Unidade: <?= $conv['convenio']; ?></td> 
                   <td style="text-align: right;"> PERÍODO:</td> 
                   <td width="200px" >  <?= $data_inicial; ?> à <?= $data_final; ?> &nbsp; </td> 
             </tr>
            <? if($impressao != "true"){?> 
             <tr>
                   <td> </td> 
                   <td> </td>                    
                   <td  style="text-align: right;">
                       <form method="post" action="<?= base_url()?>ambulatorio/guia/atualizarmedicoprocedimento" target="_blank">
                        <?php 
                        $empresa = $this->operador_m->listarempresasoperador($medico_id); ?>
                       <select name="empresa" id="empresa" class="size2">
                             <? foreach ($empresa as $value) : ?>
                                 <option value="<?= $value->empresa_id; ?>" ><?php echo $value->nome; ?></option>
                             <? endforeach; ?>                            
                       </select>
                        <br>
                        <input type="submit" value="Enviar">
                        <input type="hidden" name="txtdata_inicio" id="txtdata_inicio" value="<?= $data_inicial; ?>" >
                        <input type="hidden" name="txtdata_fim" id="txtdata_fim" value="<?= $data_final; ?>">                        
                        <input type="hidden" name="medico_id" id="medico_id" value="<?= $medico_id; ?>">
                        <input type="hidden" name="convenio" id="convenio" value="<?=  $conv['convenio_id']; ; ?>">
                        <input type="hidden" name="grupo" id="grupo" value="<?= @$grupo; ?>">
                    </form>
                   </td>                 
             </tr>
           <? } ?>
                <?
               }
             }else{
                 $i = 0; 
                 if (@in_array("0", $_POST['medicos']) || @count($_POST['medicos']) == 0) {
                     ?>
                    <tr>
                        <td> TODOS</td>                    
                        <td style="text-align: right;"> PERÍODO: <?= $data_inicial; ?> à <?= $data_final; ?></td>  
                    </tr>
                    <tr>
                        <td>Unidade: <?= $conv['convenio']; ?></td>
                        <td> </td>
                    </tr>
                     <?
                 }else{
                    foreach($medicos as $value){?>
                    <tr>
                        <td> <?= $value['medico']; ?></td>
                                 <?php 
                                        if ($i == 1) {
                                            echo "<td> </td>";
                                        }
                                ?>
                                <?php 
                   if ($i < 1) {
                                 ?>
                        <td style="text-align: right;"> PERÍODO: <?= $data_inicial; ?> à <?= $data_final; ?></td> 
                                        
                           <?
                           $i++;
                        }
                        ?>
                    </tr> 
            <?php 
             }                     
            } 
           }            
            ?>  
            </table>
               
         <table border="0" width="100%">
            <tr>
                <td colspan="4"  ><center>Descrição</center></td>
            </tr>
          </table>
           
                    <table border="0"  style=" padding:20px;border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;" width="100%">
            <tr>
                <td style="border-bottom: 1px solid black;">Itens</td>
                <td style="text-align: right;border-bottom: 1px solid black;">Qtde</td>
                <td colspan="2" style="border-bottom: 1px solid black;">&nbsp; Vl-Movimento</td>
            </tr>
            
            <?php 
            $totalgeral= 0;
            foreach($procedimentos as $value){
                $totalgeral += $value['valor_total'];
               
                ?>
            <tr>
                <td ><?= $value['nome'] ?></td>
                <td style="text-align: right;"><?= $value['quantidade'] ?></td>
                <td  style="text-align: right;">R$</td> 
                <td  style="text-align: right;"> <?=   number_format($value['valor_total'], 2, ",", "."); ?></td>
            </tr>            
            <?                
            }
            ?>
           
            
        </table>
        <table e border="0"  cellspacing="0"  rules="none" style="padding:7px;"    width="100%">
            <tr>
                <td colspan="4"><center>Totalizações</center></td>
            </tr>
        </table>
       
        <table border="0"  cellspacing="0"  rules="none" style="padding:7px;border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"    width="100%">         
                    <tr>
                        <td width="70px"  id="ladodireto"  >IRRF : </td>
                        <td>R$ <?= number_format($totalrrf, 2, ",", "."); ?></td>
                        <td   id="ladodireto"  >Valor Faturado:  <b style="padding: 20px; font-weight: normal;">R$ <?= number_format($totalgeral, 2, ",", "."); ?> </b>  </td>
                    </tr>
                    <tr>
                        <td   id="ladodireto" >COFINS :</td>
                        <td>R$ <?= number_format($totalcofins, 2, ",", "."); ?> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td  id="ladodireto"  >CSLL :</td>
                        <td>R$ <?= number_format($totalcsll, 2, ",", "."); ?> </td>
                        <td></td>
                    </tr>
                     <tr>
                        <td  id="ladodireto"  >ISS :</td>
                        <td>R$ <?= number_format($totaliss, 2, ",", "."); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td  id="ladodireto"  >PIS :</td>
                        <td>R$ <?= number_format($totalpis, 2, ",", "."); ?></td>
                        <td></td>
                    </tr>
          
                      
        </table>  <br>
        
         <?php if($impressao != "true"){?>        
        <foot>            
            <form  method="post" action="<?= base_url()?>ambulatorio/guia/impressaorelatorioextratomedico" target="_blank">
                <button type="submit" >IMPRIMIR</button>  
                <input type="text" name="txtdata_inicio" value="<?= $data_inicial; ?>" hidden="" > <br>
                <input type="text" name="txtdata_fim" value="<?= $data_final; ?>" hidden="" ><br>
                <textarea type="text" name="medicos" hidden=""  ><?=  json_encode($medico_ids) ; ?></textarea><br>
                <input type="text" name="grupo" value="<?= $grupo; ?>" hidden="" ><br>
                <input type="text" name="convenio" value="<?= $conv['convenio_id']; ?>" hidden=""  ><br>
                <input type="text" name="demostrativo" value="<?= $demostrativo; ?>"  hidden="" ><br>   
            </form>             
        </foot>  
     <?php } ?>
    
 <?php }?>  
        
        
        <?php 
            if ($_POST['demostrativo'] == "SIM") { 
            $j =0; 
            if (count($todos_medicos) > 0 ) {
                
            }
          foreach ($todos_medicos as $item){                
        ?>
           <?php       
                    foreach ($todos_procedimento_medico as $value){     
                        if ($value['medico_id']==$item['medico_id']) {
                             @$conlspan_procedimento{$value['medico_id']}++;
                             }
                        } 
                          ?>
        <table border="1"  cellspacing="0" style="border:1px solid white;"  > 
            <tr>
                <td colspan="2" id=""><center><b id="cabecalho">
                <?= $empresa_permissao[0]->razao_social; ?><br>
                 <?= $empresa_permissao[0]->logradouro; ?> - <?= $empresa_permissao[0]->numero; ?> <br>
                CNPJ: <?= $empresa_permissao[0]->cnpj; ?></b>
                </center></td>
                <td colspan="<?=  $conlspan_procedimento{$item['medico_id']}+1   ; ?>" id="">
                   <center> 
                       DEMOSTRATIVOS DOS PROCEDIMENTOS MÉDICOS                    
                   </center>
                </td>                          
            <tr>
                <td colspan="<?= $conlspan_procedimento{$item['medico_id']}+3 ; ?>" style="border-left:none;border-right: none;"  >&nbsp;  </td>
            </tr>
            <tr>
                <th class="colunas"><center>NOME</center></th>
                <th class="colunas"><center>UNIDADE</center></th>
                <?php 
                
               
                    $colspan_td = 0;
                    foreach ($todos_procedimento_medico as $value){
                        if ($value['medico_id']==$item['medico_id']) { 
                            $colspan_td++;
                             $conlspan_procedimento ++;
                            
                            ?>
                <th class="colunas"><center><?= $value['nome']; ?></center></th>                
                         <?
                           }
                           
                        } 
                          ?>
        <th class="colunas"><center><b>TOTAL</b></center></th>
            </tr>
                <?php  
                foreach($todos_medicos_convenio as $item2){
                    if ($item2['medico_id'] == $item['medico_id']) {
                        
                   
                ?>
            <tr>
                <td><?= $item2['medico'];?></td>
                <td><center><?= $item2['convenio'];?></center></td>
               <?php  
              $testeprocedimento = Array();
                foreach($relatorio as $proce){
                    
                    if ($proce->convenio_id == $item2['convenio_id'] && $proce->medico_parecer1 == $item2['medico_id']) {
                         if ($empresa_permissao[0]->faturamento_novo == 't') {
                        $descontoForma = $this->guia->listardescontoTotal($proce->agenda_exames_id);
                      
                            if (count($descontoForma) > 0) {
                                $descontoTotal += $descontoForma[0]->desconto;
                                $descontoAtual = $descontoForma[0]->desconto;
                            }
                        }
                         if ($empresa_permissao[0]->faturamento_novo == 't') {
                            $valor_total = ($proce->valor * $proce->quantidade) - @$descontoAtual;                             
                         } else {
                             $valor_total = $proce->valor_total;
                         }
                           if ($empresa_permissao[0]->promotor_medico == 't') {
                                // MESMAS REGRAS ABAIXO PARA O PROMOTOR ABAIXO
                                 
                                if ($proce->percentual_promotor == "t") {
                                
                                    $valorpercentualpromotor = $proce->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valor_total * ($valorpercentualpromotor / 100);
                                } else {
                                    
                                    $valorpercentualpromotor = $proce->valor_promotor/* - ((float) $item->valor_promotor * ((float) $item->taxa_administracao / 100)) */;

                                    $percpromotor = $valorpercentualpromotor *  $proce->quantidade;
                                }

                                // SE FOR PERCENTUAL, ELE CALCULA O TOTAL PELO PERCENTUAL
                                if ($proce->percentual_medico == "t") {
                                    $simbolopercebtual = " %";

                                    $valorpercentualmedico = $proce->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                    if ($proce->valor_promotor != null) {
//                                        echo '<pre>';
                                        $perc = $perc - $percpromotor;
                                    }
                                } else {
                                    // SE FOR VALOR, É O VALOR * A QUANTIDADE
                                    $simbolopercebtual = "";
                                    $valorpercentualmedico = $proce->valor_medico;

                                    $perc = $valorpercentualmedico * $proce->quantidade;
                                    if ($proce->valor_promotor != null) {
 
                                        $perc = $perc - $percpromotor;
                                    }
                                }
                                $totalperc += $perc;
                            } else {
                                // SENÃO, VAI CONTINUAR DA FORMA QUE ERA ANTES
                            
                                if ($proce->percentual_medico == "t") {
                               
                                    $valorpercentualmedico = $proce->valor_medico;
                                    $perc = $valor_total * ($valorpercentualmedico / 100);
                                } else {
                                       
                                    $valorpercentualmedico = $proce->valor_medico;
 
                                    $perc = $valorpercentualmedico * $proce->quantidade;
                                }
                                 $totalperc += $perc;
          
                            }
                        
                        @$valor_por_procedimento{$proce->procedimento_tuss_id}{$proce->medico_parecer1} += $value->valor_total;
                    //    echo '<pre>'; 
                    //     print_r($todos_procedimento_medico);
                    //     die;
            $totalbruto = 0;
        foreach ($todos_procedimento_medico as $value){ 
             if ($value['medico_id']==$item2['medico_id']) {    
            $p  = $value['procedimento_tuss_id'];
            if (@$c{$p.$proce->convenio_id.$proce->medico_parecer1} == 0) { 
                    @$valorPmedico{$proce->convenio_id.$proce->medico_parecer1}  += @$valor_procedimento[$p."".$proce->convenio_id."".$proce->medico_parecer1]['valor'];                   
                    echo "<td id='ladodireto'>R$".number_format(@$valor_procedimento[$p."".$proce->convenio_id."".$proce->medico_parecer1]['valor'], 2, ",", ".")."</td>";
                    // print_r(@$valor_procedimento[$p."".$proce->convenio_id."".$proce->medico_parecer1]['valor']);
                    // echo '<br>';
                    // $totalbruto = $totalbruto + @$valor_procedimento[$p."".$proce->convenio_id."".$proce->medico_parecer1]['valor'];
                    @$c{$p.$proce->convenio_id.$proce->medico_parecer1}++;                    
               }                 
             }  
            }
           }                    
          }            
        echo "<td id='ladodireto'><b>R$".number_format($valorPmedico{$item2['convenio_id'].$item2['medico_id']}, 2, ",", ".")."</b></td>";   
                ?>               
            </tr>
                <?php }}?>
            <tr>
                <!--<td colspan="<?= 3+$colspan_td; ?>">&nbsp;</td>--> 
                    <?php for($i=0;$i<3+$colspan_td;$i++){
                        echo "<td>&nbsp;</td>";
                    }?>
            </tr>
             <tr>
               <th colspan="2" ><center><b>TOTAL BRUTO</b></center></th>                    
        <?php     
            // foreach($totalprocedimento_medico2 as $key=>$value){
            //        echo "{$key} => {$value} ";
            //        echo '<br>';  
            // } 
        $valorBruto = 0;
        // echo '<pre>';
        // print_r($todos_procedimento_medico);
        // die;
        foreach($todos_procedimento_medico2 as $proce){
            if ($proce['medico_id'] == $item['medico_id'] ) {                
                $valorBruto += $proce['valor_total'];            
            ?>    
        <th id='ladodireto'><b>R$ <?= number_format($proce['valor_total'], 2, ",", "."); ?></b></th>
        <?php }else{
               
             }
             ?>
               
           
              <?php }?>
               
               <th id='ladodireto'><b>R$ <?=  number_format($valorBruto, 2, ",", "."); ?></b></th>
                       
           </tr>
           <tr>
               <td colspan="<?= 3+$colspan_td; ?>">&nbsp;</td>
           </tr>
           <tr>
               <th colspan="2" ><center><b>DEDUÇÕES</b></center></th>  
            <?php for($i = 0; $i <  1+$colspan_td; $i++){
                echo "<td></td>";
            }
               ?>
    
           </tr>
           <tr>
               <td colspan="2"><center>DEDUÇÕES</center></td>            
                  <?php   
                    $seguro_desconto = 0;
                    $simbolo = "";
                    foreach($todos_procedimento_medico2 as $proce){
                         if ($proce['medico_id'] == $item['medico_id']  ) {       
                           
                            ?>
           <td id='ladodireto'><? if($proce['valor_total'] > 0 && $proce['desconto_seguro'] > 0){
              
                    if ($proce['tipo_desc_seguro'] == "percentual") {   
                         $porcentagem = (($proce['desconto_seguro']/100) * $proce['valor_total']);
                           $seguro_desconto += $porcentagem;
                         echo "R$ -".number_format($porcentagem, 2, ",", "."); 
                    }else{     
                           $seguro_desconto += $proce['desconto_seguro'];
                         echo "R$ -".number_format($proce['desconto_seguro'], 2, ",", ".");
                    } 
                  }else{
                       echo "R$ 0,00";  
                  }
                   ?>
           </td>     
                         <?
                        }   
                           
                      } 
                     ?>
           <td><?
                  if ($seguro_desconto > 0) {                       
                       echo "R$ -" . number_format($seguro_desconto, 2, ",", ".");                       
                   } else {
                       echo "R$ 0,00";
                   }
                   ?></td>
        
           
           </tr>
           <tr>
               <td colspan="1">&nbsp;</td>
               <?php for($i = 0; $i <  2+$colspan_td; $i++){
                echo "<td></td>";
            }
               ?>
           </tr>
           <tr>
               <th colspan="2"><center><b>VALOR LÍQUIDO</b></center></th>
            <?php   
                $desconto_liquido = 0;
                    foreach($todos_procedimento_medico2 as $proce){
                         if ($proce['medico_id'] == $item['medico_id'] ) {                                  
                            ?>
                                <th id='ladodireto'> 
                             <? if($proce['valor_total'] > 0 && $proce['desconto_seguro'] > 0){
                                    if ($proce['tipo_desc_seguro'] == "percentual") {                                          
                                         $porcentagem = (($proce['desconto_seguro']/100) * $proce['valor_total']);
                                         $desconto_liquido += $proce['valor_total'] - $porcentagem;
                                          echo "<b>R$ ".number_format(($proce['valor_total'] - $porcentagem), 2, ",", ".")."</b>";
                                    }else{ 
                                         $desconto_liquido += $proce['valor_total'] - $proce['desconto_seguro'];
                                         echo "<b>R$ ".number_format(($proce['valor_total'] - $proce['desconto_seguro']), 2, ",", ".")."</b>";
                                    } 
                                }elseif($proce['valor_total'] > 0){        
                                     $desconto_liquido += $proce['valor_total']  ;
                                     echo "<b>R$ ".number_format(($proce['valor_total']), 2, ",", ".")."</b>";
                                }else{
                                     echo "<b>R$ 0,00</b>";
                                }
                                   ?>     
                                </th>     
                         <?
                        }   
                           
                      } 
                     ?>
                   <th><b>R$ <?= number_format($desconto_liquido, 2, ",", ".");?></b></th>
           </tr>            
        </table>
                <? 
                $j++;
                    if ($j != count($todos_medicos)) {
                       echo "<br>";
                   }
                ?>
        
            <?}
            
         }?>   
      
    </body>
</html>
    <?php }else{
        echo "<h4>Não há resultados para esta consulta.</h4>";
        
    }
?>
