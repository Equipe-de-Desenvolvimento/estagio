<meta charset="UTF-8">
<?
$sexo = $exame[0]->sexo;
if ($sexo == "M") {
    $sexopaciente = "Masculino";
} elseif ($sexo == "F") {
    $sexopaciente = "Feminino";
} else {
    $sexopaciente = "Outro";
}
$dataFuturo = date("Y-m-d");
$dataAtual = $paciente['0']->nascimento;
$date_time = new DateTime($dataAtual);
$diff = $date_time->diff(new DateTime($dataFuturo));
$teste = $diff->format('%Ya %mm %dd');
$exame_id = $exame[0]->agenda_exames_id;
$dataatualizacao = $exame[0]->data_autorizacao;
$inicio = $exame[0]->inicio;
$agenda = $exame[0]->agenda;
$data = $exame[0]->data;
$MES = substr($exame[0]->data_emissao, 5, 2);

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


if ($empresapermissoes[0]->desativar_personalizacao_impressao == 'f') {  
    if (file_exists("./upload/operadortimbrado/" . $exame['0']->operador_id . ".png")) {
        $caminho_background = base_url() . "upload/operadortimbrado/" . $exame['0']->operador_id . ".png";
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
?>
<?
if (@$modelo[0]->cabecalho == 't') {
    if (@$empresa[0]->cabecalho_config == 't') {
        echo @$cabecalho[0]->cabecalho;
    } else {
        if ($empresa[0]->impressao_declaracao == 1) {
            ?>
            <p style="text-align: center"><img align = 'center'  width='400px' height='200px' src="<?= base_url() . "img/cabecalho.jpg" ?>"></p>   
        <? } else {
            ?>
            <p style="text-align: center"><img align = 'center'  width='1000px' height='300px' src="<?= base_url() . "img/cabecalho.jpg" ?>"></p> 
            <?
        }
    }
}

//print_r($empresa[0]->cabecalho_config );die;
?> 

 
<?
$corpo = @$modelo[0]->texto;
$corpo = str_replace("_paciente_", $exames['0']->paciente, $corpo);
$corpo = str_replace("_sexo_", $exames['0']->sexo, $corpo);
$corpo = str_replace("_nascimento_", date("d/m/Y", strtotime($exames['0']->nascimento)), $corpo);
$corpo = str_replace("_convenio_", $exames['0']->convenio, $corpo);
//$corpo = str_replace("_sala_", $exames['0']->sala, $corpo);
$corpo = str_replace("_CPF_", $exames['0']->cpf, $corpo);
//$corpo = str_replace("_solicitante_", $exames['0']->solicitante, $corpo);
$corpo = str_replace("_data_", date("d/m/Y", strtotime($exames['0']->data)), $corpo);
//$corpo = str_replace("_medico_", $exames['0']->medico, $corpo);
//$corpo = str_replace("_revisor_", $exames['0']->medicorevisor, $corpo);
$corpo = str_replace("_procedimento_", $exames['0']->procedimento, $corpo);
$corpo = str_replace("_carimbo_", $exames['0']->carimbo, $corpo);
$corpo = str_replace("_dataextenso_", $exame[0]->municipio."-".$UF.", ".substr($exame[0]->data_emissao, 8, 2) . " de " . $MES . " de " . substr($exame[0]->data_emissao, 0, 4), $corpo);
$corpo = str_replace("_horaemissao_", date('H:i:s',strtotime($exame['0']->data_emissao)), $corpo);

if(isset($exame['0']->data_finalizacao_laudo) && $exame['0']->data_finalizacao_laudo != ""){
  $corpo = str_replace("_horafinalizacao_", date('H:i:s',strtotime($exame['0']->data_finalizacao_laudo)), $corpo);
}
$corpo = str_replace("_grupo_", $exame['0']->grupo, $corpo);


//$corpo = str_replace("_laudo_", $exames['0']->texto, $corpo);
//$corpo = str_replace("_nomedolaudo_", $exames['0']->cabecalho, $corpo);
//$corpo = str_replace("_queixa_", $exames['0']->cabecalho, $corpo);
//$corpo = str_replace("_peso_", $exames['0']->peso, $corpo);
//$corpo = str_replace("_altura_", $exames['0']->altura, $corpo);
$corpo = str_replace("_assinatura_", "<img src='" . base_url() . "upload/1ASSINATURAS/" . @$exames['0']->operador_id . ".jpg' >", $corpo);
?>
<? echo $corpo ?>

<style>
    footer {
        position: absolute; 
        bottom: 0px; 
        width: 100%; 
        /*height: 60px;*/ 
        /*background-color: green;*/
    }
    html {
        position: relative;
        min-height: 100%;
    }
    body {
        margin: 0 0 200px; /* altura do seu footer */
    }

</style>

<footer>
    <p style="text-align: center">

        <? //        $exame[0]->municipio  ?> 
        <? //        $exame[0]->estado  ?>
        <? //        substr($exame[0]->data, 8, 2) . " de " . $MES . " de " . substr($exame[0]->data, 0, 4);  ?> 
        <? //        date("H:i:s") ?>
 
        <?
        @$cabecalho = @$cabecalho[0]->rodape;
        @$cabecalho = str_replace("_assinatura_", '', @$cabecalho);
        if (@$modelo[0]->rodape == 't') {
            if ($empresa[0]->rodape_config == 't') {
                echo @$cabecalho;
            } else {
                if ($empresa[0]->impressao_declaracao == 1) {
                    ?>
                <p style="text-align: center"><img align = 'center'  width='1000px' height='100px' src="<?= base_url() . "img/rodape.jpg" ?>"></p>    
            <? } else {
                ?>
                <p style="text-align: center"><img align = 'center'  width='1000px' height='300px' src="<?= base_url() . "img/rodape.jpg" ?>"></p> 
                <?
            }
        }
    }
    $perfil_id = $this->session->userdata('perfil_id');
    ?>


</p>
<style>
    @media print {
        button { 
            display: none; 
        }
    }
</style>


</footer>

<? if ($empresapermissoes[0]->desativar_personalizacao_impressao != 't' && $timbrado) { ?></div><? } ?>


<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
