<style>
    /* p{
        margin: 0px;
    } */

</style>
<style>
@page {
	
	/* margin-bottom: 2cm;  */
    /* padding-bottom: 2px; */
    /* margin-bottom: 15px; */

}

</style>

<?
$diagnosticonivel = '';
if(isset($laudo[0]->opcoes_diagnostico) && $laudo[0]->opcoes_diagnostico != ''){
    $opcoes = str_replace("_", ' ', $laudo[0]->opcoes_diagnostico);
    $diagnosticonivel .= '<b>'.$opcoes.'</b>';

        if($laudo[0]->nivel1_diagnostico != ''){
            $nivel1 = str_replace("_", ' ', $laudo[0]->nivel1_diagnostico);
            $diagnosticonivel .= '<br><b> Nivel 1 -</b> '.$nivel1;

            if($laudo[0]->nivel2_diagnostico != ''){
                $nivel2 = str_replace("_", ' ', $laudo[0]->nivel2_diagnostico);
                $diagnosticonivel .= '<br><b> Nivel 2 -</b> '.$nivel2;

                    if($laudo[0]->nivel3_diagnostico != ''){
                        $nivel3 = str_replace("_", ' ', $laudo[0]->nivel3_diagnostico);
                        $diagnosticonivel .= '<br><b> Nivel 3 -</b> '.$nivel3;
                    }
            }
        }
} 
if (@$empresapermissoes[0]->desativar_personalizacao_impressao == 'f') {

    if (file_exists("./upload/operadortimbrado/" . $laudo['0']->medico_parecer1 . ".png")) {
        $caminho_background = base_url() . "upload/operadortimbrado/" . $laudo['0']->medico_parecer1 . ".png";
        $timbrado = true;
    } elseif (file_exists("./upload/upload/timbrado/timbrado.png")) {
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
        if (file_exists("./upload/1ASSINATURAS/" . $laudo['0']->medico_parecer1 . ".jpg")) {
            $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $laudo['0']->medico_parecer1 . ".jpg'>";
        } else {
            $assinatura = "";
        }
    }

//echo $assinatura;
    @$corpo = $impressaolaudo[0]->texto;
    @$corpo = str_replace("<p", '<div', @$corpo);
    @$corpo = str_replace("</p>", '</div>', @$corpo);
//    echo($corpo);
//    die;
    $dataFuturo2 = date("Y-m-d");
    $dataAtual2 = $laudo['0']->nascimento;
    $date_time2 = new DateTime($dataAtual2);
    $diff2 = $date_time2->diff(new DateTime($dataFuturo2));

    // $idade = $diff2->format('%Ya %mm %dd');

    $idade = $diff2->format('%Y anos');
    
    $texto = $laudo['0']->texto;
    
    
    $texto = str_replace("<!DOCTYPE html>", '', $texto);
    $texto = str_replace("<head>", '', $texto);
    $texto = str_replace("</head>", '', $texto);
    $texto = str_replace("<html>", '', $texto);
    $texto = str_replace("<body>", '', $texto);
    $texto = str_replace("</html>", '', $texto);
    $texto = str_replace("</body>", '', $texto);
    $texto = str_replace('align="center"', '', $texto);
    $texto = str_replace('align="justify"', '', $texto);
    $texto = str_replace('align="right"', '', $texto);
    $texto = str_replace('align="left"', '', $texto);
  
    $texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $texto);

    $corpo = str_replace("_paciente_", @$laudo['0']->paciente, $corpo);
    $corpo = str_replace("_prontuario_antigo_", @$laudo['0']->prontuario_antigo, $corpo);
    $corpo = str_replace("_nome_mae_", @$laudo['0']->nome_mae, $corpo);
    $corpo = str_replace("_especialidade_", @$laudo['0']->grupo, $corpo);
    $corpo = str_replace("_idade_", $idade, $corpo);
    $corpo = str_replace("_sexo_", $laudo['0']->sexo, $corpo);
    $corpo = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $corpo);
    $corpo = str_replace("_convenio_", $laudo['0']->convenio, $corpo);
    $corpo = str_replace("_sala_", @$laudo['0']->sala, @$corpo);
    $corpo = str_replace("_CPF_", $laudo['0']->cpf, $corpo);
    $corpo = str_replace("_RG_", $laudo['0']->rg, $corpo);
    $corpo = str_replace("_solicitante_", $laudo['0']->solicitante, $corpo);
    $corpo = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $corpo);
    $corpo = str_replace("_hora_", date("H:i:s", strtotime($laudo[0]->data_cadastro)), $corpo);
    $corpo = str_replace("_medico_", $laudo['0']->medico, $corpo);
    $corpo = str_replace("_revisor_", $laudo['0']->medicorevisor, $corpo);
    $corpo = str_replace("_procedimento_", @$laudo['0']->procedimento, $corpo);
    $corpo = str_replace("_laudo_", $texto, $corpo);
    $corpo = str_replace("_nomedolaudo_", @$laudo['0']->cabecalho, $corpo);
    $corpo = str_replace("_queixa_", @$laudo['0']->cabecalho, $corpo);
    $corpo = str_replace("_peso_", @$laudo['0']->peso, $corpo);
    $corpo = str_replace("_altura_", @$laudo['0']->altura, $corpo);
    $corpo = str_replace("_cid1_", @$laudo['0']->cid1, $corpo);
    $corpo = str_replace("_cid2_", @$laudo['0']->cid2, $corpo);
    $corpo = str_replace("_guia_", @$laudo[0]->guia_id, $corpo);
    $operador_id = $this->session->userdata('operador_id');
    $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
    @$corpo = str_replace("_usuario_logado_", @$operador_atual[0]->usuario, $corpo);
    $corpo = str_replace("_prontuario_", $laudo[0]->paciente_id, $corpo);
    $corpo = str_replace("_usuario_salvar_", @$laudo[0]->usuario_salvar, $corpo);
    $corpo = str_replace("_telefone1_", $laudo[0]->telefone, $corpo);
    $corpo = str_replace("_telefone2_", $laudo[0]->celular, $corpo);
    $corpo = str_replace("_whatsapp_", $laudo[0]->whatsapp, $corpo);
    $corpo = str_replace("_carimbo_", @$laudo[0]->carimbo, $corpo);
    $corpo = str_replace("_diagnostico_", $diagnosticonivel, $corpo);
    $corpo = str_replace("_setor_", @$laudo[0]->setor, $corpo);
    $corpo = str_replace("_observacao_", @$laudo[0]->observacoes, $corpo);
//    if($laudo['0']->situacao == "FINALIZADO"){
    $corpo = str_replace("_assinatura_", $assinatura, $corpo);
//    }else{
//        $corpo = str_replace("_assinatura_", '', $corpo);
//    }

    // echo "<style> p {margin-top:0px;margin-bottom:0px;}</style>";
    
    if (@$empresapermissoes[0]->remove_margem_cabecalho_rodape == 't') {
          echo "<div style=' margin-left:7%;width:86%;'>";
          echo $corpo;
          echo "</div>";
    }else{  
          echo $corpo;
    }
    
    ?>
    <? if (@$empresapermissoes[0]->desativar_personalizacao_impressao != 't' && @$timbrado) { ?>
    </div>
<? } ?>

