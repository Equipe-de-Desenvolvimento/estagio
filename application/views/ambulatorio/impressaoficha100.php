<html>
    <head>
<? $dataFuturo2 = date("Y-m-d");
$dataAtual2 = $exame[0]->nascimento;
$date_time2 = new DateTime($dataAtual2);
$diff2 = $date_time2->diff(new DateTime($dataFuturo2));
$teste2 = $diff2->format('%Y');
?>
        <title>Ficha</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            .fontpequena{
                font-size:10px;
            }
            
             @media print { 
               
                 @page{
                     
                  size: auto;
                   /*margin: 1mm;*/
                  }
                  
            }
        </style>
    </head>
    <body>
        <table width="100%"  >
            <tr>
                <td  style="text-align: center; width:33.33%;"><img  width="90px" height="90px"   src="<?= base_url() . "/upload/logomarca/{$empresa_id}/{$arquivo_pasta[0]}" ?>"></td>
                <td     style="text-align: center; width:33.33%;"><b>Prontuário Médico</b></td>
                <td  valign="top" class="fontpequena"  style="text-align: center; width:33.33%;">USUÁRIO...: <?= $operadorlista[0]->nome; ?><br> IMPRESSÃO: <?= date('d/m/Y H:i:s')?> <br> EMPRESA...: <?= $empresa[0]->nome; ?><br>
                 AGENDAMENTO:
                 <? foreach ($exame as $item){ ?>
                    <?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?> <?= $item->inicio?>
                 <?}?>
                </td>
            </tr>
            <tr>
                <td colspan="3"><hr></td>
            </tr>
            <tr>
                <td>Código do Paciente: <b><?= $exame[0]->paciente_id; ?></b> </td>
                <td colspan="2">Nome: <b><?= $exame[0]->paciente; ?></b></td>
            </tr>
            <tr>
                <td colspan="3"> <br></td>
            </tr>
            <tr>
                <td style="width: 400px;">Data de Nascimento: <?= date('d/m/Y',strtotime($exame[0]->nascimento)); ?></td> 
                <td>Sexo: <?= $exame[0]->sexo; ?>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Idade: <?=$teste2?> anos   </td>
                <td>Profissão: <?= $exame[0]->profissaos; ?></td>
            </tr>
            <tr>
                <td colspan="3"><br> </td>
            </tr>
            <tr>
                <td colspan="3">Telefone: Res.:  <?= $exame[0]->telefone; ?>   Cel.: <?= $exame[0]->celular; ?></td>
            </tr>
            <tr>
                <td colspan="3"><br> </td>
            </tr>
            <tr>
                <td colspan="3">Endereço(Logradouro, nº, Complemente,Bairro): <?= $exame[0]->logradouro_paciente.", ".$exame[0]->numero_paciente.", ".$exame[0]->complemento_paciente.", ".$exame[0]->bairro_paciente; ?></td>
            </tr>
            <tr>
                <td colspan="3"><br> </td>
            </tr>
            <tr>
                <td>Cidade: <?= $exame[0]->municipio_paciente; ?></td>
                <td>Estado: <?= $exame[0]->estado_paciente; ?></td>
                <td>CEP: <?= $exame[0]->cep; ?></td>
            </tr>
            <tr>
                <td colspan="3"><br> </td>
            </tr>
            <tr>
                <td colspan="3">E-mail: <?= $exame[0]->email_paciente; ?></td>
            </tr>
            <tr>
                <td colspan="3"><hr></td>
            </tr>
            <tr>
                <td colspan="1">Médico: <b><?= $exame[0]->medico;?> </b></td>
                <td colspan="1">Data/Hora Atendimento: <? 
                if($exame[0]->data_autorizacao != '') {
                  echo date('d/m/Y H:i:s',strtotime($exame[0]->data_autorizacao));
                }elseif($exame[0]->data_atualizacao != ''){
                  echo date('d/m/Y H:i:s',strtotime($exame[0]->data_atualizacao));
                }
                ?></td>
                <td>Tipo Atendimento:<b><?= $exame[0]->procedimento; ?></b></td>
            </tr>
              <tr>
                  <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top" class="anaminese">Anaminese:</td>
                <td colspan="2" style=" border:1px solid black; height: 200px; width: 800px;"> </td>
            </tr>
        </table>

    </body>
</html>
