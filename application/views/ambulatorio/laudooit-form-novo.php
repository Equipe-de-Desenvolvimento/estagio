<div class="content ficha_oit">
<style>
input[type="radio"] {
    -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
    -moz-appearance: checkbox;    /* Firefox */
    -ms-appearance: checkbox;     /* not currently supported */
}
.linhas td, .linhas tr, .linhas th {
    border: 1px solid black !important;
}
.linhas th, .linhas td{
    text-align: center !important;
}
.linhas_2{
    border: 1px solid black !important; 
}
.menor td{
    width: 25%  !important; 
    text-align: center !important;
}
.containerr{
    width: 100%;
    }
.box { 
    width: 49%  !important;
    }
.box2 { 
    width: 69%  !important;
    }
.box3 { 
    width: 30%  !important;
    }
 .left{ 
     float: left !important; 
     }
.right{ 
    float: right !important; 
}

</style>
    <form name="form_paciente" id="form_paciente" action="<?= base_url() ?>ambulatorio/laudo/gravaroit" method="post">
    <fieldset>
            <legend>LAUDO OIT</legend>

        <table>
            <tr>
                <td colspan='2'>
                    <label><b>NOME</b></label>                      
                    <input type="text" id="txtNome" name="nome" class="texto10"  value="<?= @$obj->_nome; ?>" readonly/>
                    <input type="hidden" id="ambulatorio_laudooit_id" name="ambulatorio_laudooit_id" class="texto10"  value="<?= @$obj->_ambulatorio_laudooit_id; ?>"/>
                </td>
                <td>
                    <label><b>DATA DO RX</b></label>
                    <input type="text" name="datarx" id="datarx" class="texto02" value="<?php echo substr(@$obj->_data_cadastro, 8, 2) . '/' . substr(@$obj->_data_cadastro, 5, 2) . '/' . substr(@$obj->_data_cadastro, 0, 4); ?>" readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    <label><b>Nº DO RX</b></label>
                    <input type="text" name="guia" id="guia" class="texto06" value="<?= @$obj->_guia_id; ?>" readonly/>
                    <input type="hidden" name="exame" id="exame" class="texto06" value="<?= @$obj->_exame_id; ?>" readonly/>
                    <input type="hidden" name="laudo_id"  class="texto06" value="<?= @$obj->_laudo_id; ?>" readonly/>
                </td>
                <td>
                    <label><b>LEITOR</b></label>
                    <select name="medico_parecer" id="" class="">
                    <? foreach ($operadores as $value){ ?>
                        <option value="<?= $value->operador_id; ?>"
                        <?
                            if ($medico_atendimento[0]->medico_parecer1 == $value->operador_id){
                                echo 'selected';
                            }
                        ?>
                        ><?= $value->nome; ?></option>
                            <? }  ?>
                </select>
                </td>
                <td>
                    <label><b>RX DIGITAL</b></label>
                    <input type="radio" name="rx_digital" id="rx_digital" value="t" <? if (@$obj->_rx_digital == 't') echo "checked"; ?>> SIM
                    <input type="radio" name="rx_digital" id="rx_digital" value="f" <? if (@$obj->_rx_digital == 'f') echo "checked"; ?>> NÃO
                </td>
                <td>
                    <label><b>LEITURA EM NEGATOSCÓPIO</b></label>
                    <input type="radio" name="leitura_negatoscopio" id="leitura_negatoscopio" value="t" <? if (@$obj->_leitura_negatoscopio == 't') echo "checked"; ?>> SIM
                    <input type="radio" name="leitura_negatoscopio" id="leitura_negatoscopio" value="f" <? if (@$obj->_leitura_negatoscopio == 'f') echo "checked"; ?>> NÃO
                </td>
            </tr>
            <tr>
                <td>
                <label><b>DATA DA LEITURA</b></label>
                <input type="text"  id="data" name="data" class="size1"  value="<?= @$obj->_data; ?>" />
                </td>
            </tr>
        </table>
    </fieldset>


    <div class="box left">
    <fieldset>
            <legend>1A</legend>
        <table>
        <tr>
            <td>
            <label><b>QUALIDADE TÉCNICA:</b></label>
            <input type="radio" name="qualidade_tecnica" id="qualidade_tecnica" value="1" <? if (@$obj->_qualidade_tecnica == '1') echo "checked"; ?>> 1
            <input type="radio" name="qualidade_tecnica" id="qualidade_tecnica" value="2" <? if (@$obj->_qualidade_tecnica == '2') echo "checked"; ?>> 2
            <input type="radio" name="qualidade_tecnica" id="qualidade_tecnica" value="3" <? if (@$obj->_qualidade_tecnica == '3') echo "checked"; ?>> 3
            <input type="radio" name="qualidade_tecnica" id="qualidade_tecnica" value="4" <? if (@$obj->_qualidade_tecnica == '4') echo "checked"; ?>> 4
            </td>
            <td>
                <label>Coment&aacute;rio</label>
                <input type="text"  id="comentario_1a" name="comentario_1a" class="size8"  value="<?= @$obj->_comentario_1a; ?>" />
            </td>
        </tr>    
        </table>
    </fieldset>
    </div>

    <div class="box right">
    <fieldset>
            <legend>1B</legend>
            <table>
            <tr>
            <td>
                <label><b>RADIOGRAFIA NORMAL:</b></label>
                <input type="radio" name="radiografia_normal" id="radiografia_normal_nao_habilitar" value="t" <? if (@$obj->_radiografia_normal == 't') echo "checked"; ?> onClick="habilitar_2a()"> SIM
                <input type="radio" name="radiografia_normal" id="radiografia_normal_habilitar" value="f" <? if (@$obj->_radiografia_normal == 'f') echo "checked"; ?> onClick="habilitar_2a()"> NAO
            </td>
            </tr>
            </table>
    </fieldset>
    </div>

    <fieldset id='laudooit_2a' style="display: none;">
            <legend>2A</legend>
            <table>
                <tr>
                   <td>
                   <label><b>ALGUMA ANORMALIDADE DE PARÊNQUIMA CONSISTENTE COM PNEUMOCONIOSE:</b></label>
                        <input type="radio" name="anormalidade_parenquima" id="anormalidade_parenquima_habilitar" value="t" <? if (@$obj->_anormalidade_parenquima == 't') echo "checked"; ?> onClick="habilitar_2b_c()"> SIM
                        <input type="radio" name="anormalidade_parenquima" id="anormalidade_parenquima_nao_habilitar" value="f" <? if (@$obj->_anormalidade_parenquima == 'f') echo "checked"; ?> onClick="habilitar_2b_c()"> NAO
                   </td>
                </tr>
            </table>
    </fieldset>
                    
    <div class="containerr" id="laudooit_2b_c" style="display: none;">
    <div class="box2 left">
    <fieldset>
            <legend>2B</legend>
            <table>
            <tr>
                <td>
                <label><b>PEQUENAS OPACIDADES:</b></label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
            a) Forma / tamanho
                </td>

                <td >
            b) Zonas
                </td>

                <td >
            c) Profusão
                </td>
            </tr>
            <tr class="menor">
                <td>
                <table class="linhas">
                    <tr>
                        <th colspan='2'>Primária</th>
                    </tr>

                    <?
                        if (@$obj->_forma_primaria_array != '') {
                                $forma_primaria = json_decode(@$obj->_forma_primaria_array);
                        } else {
                                $forma_primaria = array();
                        }
                                ?>
                    
                    <tr>
                        <td><input type="checkbox" name="forma_primaria[]" value="p" <?= (in_array('p', $forma_primaria)) ? 'checked' : ''; ?>> p</td>
                        <td><input type="checkbox" name="forma_primaria[]" value="s" <?= (in_array('s', $forma_primaria)) ? 'checked' : ''; ?>> s</td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="forma_primaria[]" value="q" <?= (in_array('q', $forma_primaria)) ? 'checked' : ''; ?>> q</td>
                    <td><input type="checkbox" name="forma_primaria[]" value="t" <?= (in_array('t', $forma_primaria)) ? 'checked' : ''; ?>> t</td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="forma_primaria[]" value="r" <?= (in_array('r', $forma_primaria)) ? 'checked' : ''; ?>> r</td>
                    <td><input type="checkbox" name="forma_primaria[]" value="u" <?= (in_array('u', $forma_primaria)) ? 'checked' : ''; ?>> u</td>
                    </tr>
                </table>
                </td>

                <td>
                <table class="linhas">
                    <tr>
                        <th colspan='2'>Secundária</th>
                    </tr>
                    <?
                        if (@$obj->_forma_secundaria_array != '') {
                                $forma_secundaria = json_decode(@$obj->_forma_secundaria_array);
                        } else {
                                $forma_secundaria = array();
                        }
                                ?>
                    
                    <tr>
                        <td><input type="checkbox" name="forma_secundaria[]" value="p" <?= (in_array('p', $forma_secundaria)) ? 'checked' : ''; ?>> p</td>
                        <td><input type="checkbox" name="forma_secundaria[]" value="s" <?= (in_array('s', $forma_secundaria)) ? 'checked' : ''; ?>> s</td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="forma_secundaria[]" value="q" <?= (in_array('q', $forma_secundaria)) ? 'checked' : ''; ?>> q</td>
                    <td><input type="checkbox" name="forma_secundaria[]" value="t" <?= (in_array('t', $forma_secundaria)) ? 'checked' : ''; ?>> t</td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="forma_secundaria[]" value="r" <?= (in_array('r', $forma_secundaria)) ? 'checked' : ''; ?>> r</td>
                    <td><input type="checkbox" name="forma_secundaria[]" value="u" <?= (in_array('u', $forma_secundaria)) ? 'checked' : ''; ?>> u</td>
                    </tr>
                </table>
                </td>

                <td>
                <table class="linhas">
                    <tr>
                        <th>D</th>
                        <th>E</th>
                    </tr>

                    <?
                        if (@$obj->_zonas_d_e_array != '') {
                                $zonas_d_e_array = json_decode(@$obj->_zonas_d_e_array);
                        } else {
                                $zonas_d_e_array = array();
                        }
                    ?>

                    <tr>
                        <td><input type="checkbox" name="zona_d[]" value="p" <?= (in_array('p', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                        <td><input type="checkbox" name="zona_d[]" value="s" <?= (in_array('s', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="zona_d[]" value="q" <?= (in_array('q', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                    <td><input type="checkbox" name="zona_d[]" value="t" <?= (in_array('t', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                    </tr>
                    <tr>
                    <td><input type="checkbox" name="zona_d[]" value="r" <?= (in_array('r', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                    <td><input type="checkbox" name="zona_d[]" value="u" <?= (in_array('u', $zonas_d_e_array)) ? 'checked' : ''; ?>> </td>
                    </tr>
                </table>
                </td>

                <td>
                <table class="linhas">
                <?
                        if (@$obj->_profusao_array != '') {
                                $profusao_array = json_decode(@$obj->_profusao_array);
                        } else {
                                $profusao_array = array();
                        }
                ?>

                    <tr>
                        <td><input type="checkbox" name="profusao[]" value="0/-" <?= (in_array('0/-', $profusao_array)) ? 'checked' : ''; ?>> 0/-</td>
                        <td><input type="checkbox" name="profusao[]" value="0/0" <?= (in_array('0/0', $profusao_array)) ? 'checked' : ''; ?>> 0/0</td>
                        <td><input type="checkbox" name="profusao[]" value="0/1" <?= (in_array('0/1', $profusao_array)) ? 'checked' : ''; ?>> 0/1</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="profusao[]" value="1/0" <?= (in_array('1/0', $profusao_array)) ? 'checked' : ''; ?>> 1/0</td>
                        <td><input type="checkbox" name="profusao[]" value="1/1" <?= (in_array('1/1', $profusao_array)) ? 'checked' : ''; ?>> 1/1</td>
                        <td><input type="checkbox" name="profusao[]" value="1/2" <?= (in_array('1/2', $profusao_array)) ? 'checked' : ''; ?>> 1/2</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="profusao[]" value="2/0" <?= (in_array('2/0', $profusao_array)) ? 'checked' : ''; ?>> 2/0</td>
                        <td><input type="checkbox" name="profusao[]" value="2/1" <?= (in_array('2/1', $profusao_array)) ? 'checked' : ''; ?>> 2/1</td>
                        <td><input type="checkbox" name="profusao[]" value="2/2" <?= (in_array('2/2', $profusao_array)) ? 'checked' : ''; ?>> 2/2</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="profusao[]" value="3/0" <?= (in_array('3/0', $profusao_array)) ? 'checked' : ''; ?>> 3/0</td>
                        <td><input type="checkbox" name="profusao[]" value="3/1" <?= (in_array('3/1', $profusao_array)) ? 'checked' : ''; ?>> 3/1</td>
                        <td><input type="checkbox" name="profusao[]" value="3/2" <?= (in_array('3/2', $profusao_array)) ? 'checked' : ''; ?>> 3/2</td>
                    </tr>

                </table>
                </td>
            </tr>
            </table>
    </fielset>
    </div>

    <div class="box3 right">
    <fieldset>
            <legend>2C</legend>
            <table>
            <?
                if (@$obj->_grandes_opacidades_array != '') {
                    $grandes_opacidades_array = json_decode(@$obj->_grandes_opacidades_array);
                } else {
                    $grandes_opacidades_array = array();
                }
            ?>
                <tr>
                <td>
                <label><b>GRANDES OPACIDADES:</b></label>
                    <input type="checkbox" name="grandes_opacidades[]" value="0" <?= (in_array('0', $grandes_opacidades_array)) ? 'checked' : ''; ?>> 0
                    <input type="checkbox" name="grandes_opacidades[]" value="a" <?= (in_array('a', $grandes_opacidades_array)) ? 'checked' : ''; ?>> A
                    <input type="checkbox" name="grandes_opacidades[]" value="b" <?= (in_array('b', $grandes_opacidades_array)) ? 'checked' : ''; ?>> B
                    <input type="checkbox" name="grandes_opacidades[]" value="c" <?= (in_array('c', $grandes_opacidades_array)) ? 'checked' : ''; ?>> C
                </td>
                </tr>
            </table>
    </fielset>
    </div>
    </div>

    <fieldset id='laudooit_3a' style="display: none;">
            <legend>3A</legend>
            <table>
                <tr>
                   <td>
                   <label><b>ALGUMA ANORMALIDADE PLEURAL CONSISTENTE COM PNEUMOCONIOSE:</b></label>
                        <input type="radio" name="anormalidade_pleural" id="anormalidade_pleural_habilitar" value="t" <? if (@$obj->_anormalidade_pleural == 't') echo "checked"; ?> onClick="habilitar_3b_c_d()"> SIM
                        <input type="radio" name="anormalidade_pleural" id="anormalidade_pleural_nao_habilitar" value="f" <? if (@$obj->_anormalidade_pleural == 'f') echo "checked"; ?> onClick="habilitar_3b_c_d()"> NAO
                   </td>
                </tr>
            </table>
    </fieldset>

    <fieldset id='laudooit_3b' style="display: none;">
            <legend>3B</legend>
            <table>
                <tr>
                   <td>
                        <label><b>PLACAS PLEURAIS:</b></label>
                        <input type="radio" name="placa_pleuras" value="t" <? if (@$obj->_placa_pleuras == 't') echo "checked"; ?>> SIM
                        <input type="radio" name="placa_pleuras" value="f" <? if (@$obj->_placa_pleuras == 'f') echo "checked"; ?>> NAO
                   </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                            <th></th>
                            <th >LOCAL</th>
                            <th >CALCIFICAÇÃO</th>
                            </tr>
                            
                            <tr>
                            <td>Parede em perfil</td>
                            <td>
                                <table class="linhas">
                            <?
                                if (@$obj->_local_paredeperfil_3b_array != '') {
                                    $local_paredeperfil_3b_array = json_decode(@$obj->_local_paredeperfil_3b_array);
                                } else {
                                    $local_paredeperfil_3b_array = array();
                                }
                            ?>
                                    <tr>
                                        <td><input type="checkbox" name="local_paredeperfil_3b[]" value="0" <?= (in_array('0', $local_paredeperfil_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="local_paredeperfil_3b[]" value="d" <?= (in_array('d', $local_paredeperfil_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="local_paredeperfil_3b[]" value="e" <?= (in_array('e', $local_paredeperfil_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">

                                <?
                                if (@$obj->_calcificacao_paredeperfil_3b_array != '') {
                                    $calcificacao_paredeperfil_3b_array = json_decode(@$obj->_calcificacao_paredeperfil_3b_array);
                                } else {
                                    $calcificacao_paredeperfil_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_paredeperfil_3b[]" value="0" <?= (in_array('0', $calcificacao_paredeperfil_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_paredeperfil_3b[]" value="d" <?= (in_array('d', $calcificacao_paredeperfil_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_paredeperfil_3b[]" value="e" <?= (in_array('e', $calcificacao_paredeperfil_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            </tr>

                            <tr>
                            <td>Frontal</td>
                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_local_frontal_3b_array != '') {
                                    $local_frontal_3b_array = json_decode(@$obj->_local_frontal_3b_array);
                                } else {
                                    $local_frontal_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="local_frontal_3b[]" value="0" <?= (in_array('0', $local_frontal_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="local_frontal_3b[]" value="d" <?= (in_array('d', $local_frontal_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="local_frontal_3b[]" value="e" <?= (in_array('e', $local_frontal_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_calcificacao_frontal_3b_array != '') {
                                    $calcificacao_frontal_3b_array = json_decode(@$obj->_calcificacao_frontal_3b_array);
                                } else {
                                    $calcificacao_frontal_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_frontal_3b[]" value="0" <?= (in_array('0', $local_frontal_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_frontal_3b[]" value="d" <?= (in_array('d', $local_frontal_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_frontal_3b[]" value="e" <?= (in_array('e', $local_frontal_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            </tr>

                            <tr>
                            <td>Diafragma</td>
                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_local_diafragma_3b_array != '') {
                                    $local_diafragma_3b_array = json_decode(@$obj->_local_diafragma_3b_array);
                                } else {
                                    $local_diafragma_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="local_diafragma_3b[]" value="0" <?= (in_array('0', $local_diafragma_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="local_diafragma_3b[]" value="d" <?= (in_array('d', $local_diafragma_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="local_diafragma_3b[]" value="e" <?= (in_array('e', $local_diafragma_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_calcificacao_diafragma_3b_array != '') {
                                    $calcificacao_diafragma_3b_array = json_decode(@$obj->_calcificacao_diafragma_3b_array);
                                } else {
                                    $calcificacao_diafragma_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_diafragma_3b[]" value="0" <?= (in_array('0', $calcificacao_diafragma_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_diafragma_3b[]" value="d" <?= (in_array('d', $calcificacao_diafragma_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_diafragma_3b[]" value="e" <?= (in_array('e', $calcificacao_diafragma_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>                          
                            </tr>

                            <tr>
                            <td>Outros Locais</td>
                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_local_outroslocais_3b_array != '') {
                                    $local_outroslocais_3b_array = json_decode(@$obj->_local_outroslocais_3b_array);
                                } else {
                                    $local_outroslocais_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td class="linhas_2"><input type="checkbox" name="local_outroslocais_3b[]" value="0" <?= (in_array('0', $local_outroslocais_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td class="linhas_2"><input type="checkbox" name="local_outroslocais_3b[]" value="d" <?= (in_array('d', $local_outroslocais_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td class="linhas_2"><input type="checkbox" name="local_outroslocais_3b[]" value="e" <?= (in_array('e', $local_outroslocais_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">
                                <?
                                if (@$obj->_calcificacao_outroslocais_3b_array != '') {
                                    $calcificacao_outroslocais_3b_array = json_decode(@$obj->_calcificacao_outroslocais_3b_array);
                                } else {
                                    $calcificacao_outroslocais_3b_array = array();
                                }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_outroslocais_3b[]" value="0" <?= (in_array('0', $calcificacao_outroslocais_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_outroslocais_3b[]" value="d" <?= (in_array('d', $calcificacao_outroslocais_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_outroslocais_3b[]" value="e" <?= (in_array('e', $calcificacao_outroslocais_3b_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>                         
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                            <th coslpan='5'>EXTENSÃO: PAREDE <br> (Combinado em perfil e frontal)</th>
                            </tr>
                            
                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                if (@$obj->_extensao_parede_d_3b_array != '') {
                                    $extensao_parede_d_3b_array = json_decode(@$obj->_extensao_parede_d_3b_array);
                                } else {
                                    $extensao_parede_d_3b_array = array();
                                }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_d_3b[]" value="0" <?= (in_array('0', $extensao_parede_d_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3b[]" value="d" <?= (in_array('d', $extensao_parede_d_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_d_3b[]" value="1" <?= (in_array('1', $extensao_parede_d_3b_array)) ? 'checked' : ''; ?>> 1</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3b[]" value="2" <?= (in_array('2', $extensao_parede_d_3b_array)) ? 'checked' : ''; ?>> 2</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3b[]" value="3" <?= (in_array('3', $extensao_parede_d_3b_array)) ? 'checked' : ''; ?>> 3</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                if (@$obj->_extensao_parede_e_3b_array != '') {
                                    $extensao_parede_e_3b_array = json_decode(@$obj->_extensao_parede_e_3b_array);
                                } else {
                                    $extensao_parede_e_3b_array = array();
                                }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_e_3b[]" value="0" <?= (in_array('0', $extensao_parede_e_3b_array)) ? 'checked' : ''; ?>> 0</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3b[]" value="e" <?= (in_array('e', $extensao_parede_e_3b_array)) ? 'checked' : ''; ?>> E</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_e_3b[]" value="1" <?= (in_array('1', $extensao_parede_e_3b_array)) ? 'checked' : ''; ?>> 1</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3b[]" value="2" <?= (in_array('2', $extensao_parede_e_3b_array)) ? 'checked' : ''; ?>> 2</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3b[]" value="3" <?= (in_array('3', $extensao_parede_e_3b_array)) ? 'checked' : ''; ?>> 3</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td coslpan="5">Até 1/4 da parede latera = 1</td>
                            </tr>
                            <tr>
                                <td coslpan="5">1/4 a 1/2 da parede lateral = 2</td>
                            </tr>
                            <tr>
                                <td coslpan="5">> 1/2 da parede lateral = 3</td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                            <th coslpan='4'>LARGURA (OPCIONAL) <br> (Mínimo de 3 mm para marcação   )</th>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                if (@$obj->_largura_d_3b_array != '') {
                                    $largura_d_3b_array = json_decode(@$obj->_largura_d_3b_array);
                                } else {
                                    $largura_d_3b_array = array();
                                }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="largura_d_3b[]" value="d" <?= (in_array('d', $largura_d_3b_array)) ? 'checked' : ''; ?>> D</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="largura_d_3b[]" value="a" <?= (in_array('a', $largura_d_3b_array)) ? 'checked' : ''; ?>> a</td>
                                            <td><input type="checkbox" name="largura_d_3b[]" value="b" <?= (in_array('b', $largura_d_3b_array)) ? 'checked' : ''; ?>> b</td>
                                            <td><input type="checkbox" name="largura_d_3b[]" value="c" <?= (in_array('c', $largura_d_3b_array)) ? 'checked' : ''; ?>> c</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                if (@$obj->_largura_e_3b_array != '') {
                                    $largura_e_3b_array = json_decode(@$obj->_largura_e_3b_array);
                                } else {
                                    $largura_e_3b_array = array();
                                }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="largura_e_3b[]" value="e" <?= (in_array('e', $largura_e_3b_array)) ? 'checked' : ''; ?>> E</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="largura_e_3b[]" value="a" <?= (in_array('a', $largura_e_3b_array)) ? 'checked' : ''; ?>> a</td>
                                            <td><input type="checkbox" name="largura_e_3b[]" value="b" <?= (in_array('b', $largura_e_3b_array)) ? 'checked' : ''; ?>> b</td>
                                            <td><input type="checkbox" name="largura_e_3b[]" value="c" <?= (in_array('c', $largura_e_3b_array)) ? 'checked' : ''; ?>> c</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td coslpan='4'>3 a 5 mm = a</td>
                            </tr>
                            <tr>
                                <td coslpan='4'>5 a 10 mm = b</td>
                            </tr>
                            <tr>
                                <td coslpan='4'>> 10 mm = c</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    </fieldset>

    <fieldset id='laudooit_3c' style="display: none;">
            <legend>3C</legend>
            <table>
            <?
                if (@$obj->_obliteracao_array != '') {
                $obliteracao_array = json_decode(@$obj->_obliteracao_array);
                } else {
                $obliteracao_array = array();
                }
            ?>
                <tr>
                   <td>
                   <label><b>OBLITERAÇÃO DO SEIO COSTOFRÊNICO:</b></label>
                        <input type="checkbox" name="obliteracao[]" value="0" <?= (in_array('0', $obliteracao_array)) ? 'checked' : ''; ?>> 0
                        <input type="checkbox" name="obliteracao[]" value="d" <?= (in_array('d', $obliteracao_array)) ? 'checked' : ''; ?>> D
                        <input type="checkbox" name="obliteracao[]" value="e" <?= (in_array('e', $obliteracao_array)) ? 'checked' : ''; ?>> E
                   </td>
                </tr>
            </table>
    </fieldset>

    <fieldset id='laudooit_3d' style="display: none;">
            <legend>3D</legend>
            <table>
                <tr>
                   <td>
                        <label><b>ESPESSAMENTO PLEURAL DIFUSO:</b></label>
                        <input type="radio" name="espessamento_pleural_difuso" value="t" <? if (@$obj->_espessamento_pleural_difuso == 't') echo "checked"; ?>> SIM
                        <input type="radio" name="espessamento_pleural_difuso" value="f" <? if (@$obj->_espessamento_pleural_difuso == 'f') echo "checked"; ?>> NAO
                   </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                            <th></th>
                            <th >LOCAL</th>
                            <th >CALCIFICAÇÃO</th>
                            </tr>
                            
                            <tr>
                            <td>Parede em perfil</td>
                            <td>
                                <table class="linhas">
                                <?
                                    if (@$obj->_local_parede_perfil_3d_array != '') {
                                    $local_parede_perfil_3d_array = json_decode(@$obj->_local_parede_perfil_3d_array);
                                    } else {
                                    $local_parede_perfil_3d_array = array();
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="local_parede_perfil_3d[]" value="0" <?= (in_array('0', $local_parede_perfil_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="local_parede_perfil_3d[]" value="d" <?= (in_array('d', $local_parede_perfil_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="local_parede_perfil_3d[]" value="e" <?= (in_array('e', $local_parede_perfil_3d_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">
                                <?
                                    if (@$obj->_calcificacao_parede_perfil_3d_array != '') {
                                    $calcificacao_parede_perfil_3d_array = json_decode(@$obj->_calcificacao_parede_perfil_3d_array);
                                    } else {
                                    $calcificacao_parede_perfil_3d_array = array();
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_parede_perfil_3d[]" value="0" <?= (in_array('0', $calcificacao_parede_perfil_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_parede_perfil_3d[]" value="d" <?= (in_array('d', $calcificacao_parede_perfil_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_parede_perfil_3d[]" value="e" <?= (in_array('e', $calcificacao_parede_perfil_3d_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            </tr>

                            <tr>
                            <td>Frontal</td>
                            <td>
                                <table class="linhas">
                                <?
                                    if (@$obj->_local_parede_frontal_3d_array != '') {
                                    $local_parede_frontal_3d_array = json_decode(@$obj->_local_parede_frontal_3d_array);
                                    } else {
                                    $local_parede_frontal_3d_array = array();
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="local_parede_frontal_3d[]" value="0" <?= (in_array('0', $local_parede_frontal_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="local_parede_frontal_3d[]" value="d" <?= (in_array('d', $local_parede_frontal_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="local_parede_frontal_3d[]" value="e" <?= (in_array('e', $local_parede_frontal_3d_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <table class="linhas">
                                <?
                                    if (@$obj->_calcificacao_parede_frontal_3d_array != '') {
                                    $calcificacao_parede_frontal_3d_array = json_decode(@$obj->_calcificacao_parede_frontal_3d_array);
                                    } else {
                                    $calcificacao_parede_frontal_3d_array = array();
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="calcificacao_parede_frontal_3d[]" value="0" <?= (in_array('0', $calcificacao_parede_frontal_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                        <td><input type="checkbox" name="calcificacao_parede_frontal_3d[]" value="d" <?= (in_array('d', $calcificacao_parede_frontal_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        <td><input type="checkbox" name="calcificacao_parede_frontal_3d[]" value="e" <?= (in_array('e', $calcificacao_parede_frontal_3d_array)) ? 'checked' : ''; ?>> E</td>
                                    </tr>
                                </table>
                            </td>

                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                            <th coslpan='5'>EXTENSÃO: PAREDE <br> (Combinado em perfil e frontal)</th>
                            </tr>
                            
                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                    if (@$obj->_extensao_parede_d_3d_array != '') {
                                    $extensao_parede_d_3d_array = json_decode(@$obj->_extensao_parede_d_3d_array);
                                    } else {
                                    $extensao_parede_d_3d_array = array();
                                    }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_d_3d[]" value="0" <?= (in_array('0', $extensao_parede_d_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3d[]" value="d" <?= (in_array('d', $extensao_parede_d_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_d_3d[]" value="1" <?= (in_array('1', $extensao_parede_d_3d_array)) ? 'checked' : ''; ?>> 1</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3d[]" value="2" <?= (in_array('2', $extensao_parede_d_3d_array)) ? 'checked' : ''; ?>> 2</td>
                                            <td><input type="checkbox" name="extensao_parede_d_3d[]" value="3" <?= (in_array('3', $extensao_parede_d_3d_array)) ? 'checked' : ''; ?>> 3</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                    if (@$obj->_extensao_parede_e_3d_array != '') {
                                    $extensao_parede_e_3d_array = json_decode(@$obj->_extensao_parede_e_3d_array);
                                    } else {
                                    $extensao_parede_e_3d_array = array();
                                    }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_e_3d[]" value="0" <?= (in_array('0', $extensao_parede_e_3d_array)) ? 'checked' : ''; ?>> 0</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3d[]" value="e" <?= (in_array('e', $extensao_parede_e_3d_array)) ? 'checked' : ''; ?>> E</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="extensao_parede_e_3d[]" value="1" <?= (in_array('1', $extensao_parede_e_3d_array)) ? 'checked' : ''; ?>> 1</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3d[]" value="2" <?= (in_array('2', $extensao_parede_e_3d_array)) ? 'checked' : ''; ?>> 2</td>
                                            <td><input type="checkbox" name="extensao_parede_e_3d[]" value="3" <?= (in_array('3', $extensao_parede_e_3d_array)) ? 'checked' : ''; ?>> 3</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td coslpan="5">Até 1/4 da parede latera = 1</td>
                            </tr>
                            <tr>
                                <td coslpan="5">1/4 a 1/2 da parede lateral = 2</td>
                            </tr>
                            <tr>
                                <td coslpan="5">> 1/2 da parede lateral = 3</td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                            <th coslpan='4'>LARGURA (OPCIONAL) <br> (Mínimo de 3 mm para marcação   )</th>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                    if (@$obj->_largura_d_3d_array != '') {
                                    $largura_d_3d_array = json_decode(@$obj->_largura_d_3d_array);
                                    } else {
                                    $largura_d_3d_array = array();
                                    }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="largura_d_3d[]" value="d" <?= (in_array('d', $largura_d_3d_array)) ? 'checked' : ''; ?>> D</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="largura_d_3d[]" value="a" <?= (in_array('a', $largura_d_3d_array)) ? 'checked' : ''; ?>> a</td>
                                            <td><input type="checkbox" name="largura_d_3d[]" value="b" <?= (in_array('b', $largura_d_3d_array)) ? 'checked' : ''; ?>> b</td>
                                            <td><input type="checkbox" name="largura_d_3d[]" value="c" <?= (in_array('c', $largura_d_3d_array)) ? 'checked' : ''; ?>> c</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table class="linhas">
                                    <?
                                    if (@$obj->_largura_e_3d_array != '') {
                                    $largura_e_3d_array = json_decode(@$obj->_largura_e_3d_array);
                                    } else {
                                    $largura_e_3d_array = array();
                                    }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="largura_e_3d[]" value="e" <?= (in_array('e', $largura_e_3d_array)) ? 'checked' : ''; ?>> E</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="linhas">
                                        <tr>
                                            <td><input type="checkbox" name="largura_e_3d[]" value="a" <?= (in_array('a', $largura_e_3d_array)) ? 'checked' : ''; ?>> a</td>
                                            <td><input type="checkbox" name="largura_e_3d[]" value="b" <?= (in_array('b', $largura_e_3d_array)) ? 'checked' : ''; ?>> b</td>
                                            <td><input type="checkbox" name="largura_e_3d[]" value="c" <?= (in_array('c', $largura_e_3d_array)) ? 'checked' : ''; ?>> c</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td coslpan='4'>3 a 5 mm = a</td>
                            </tr>
                            <tr>
                                <td coslpan='4'>5 a 10 mm = b</td>
                            </tr>
                            <tr>
                                <td coslpan='4'>> 10 mm = c</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    </fieldset>

    <fieldset>
            <legend>4A</legend>
            <table>
                <tr>
                   <td>
                   <label><b>OUTRAS ANORMALIDADES:</b></label>
                        <input type="radio" name="outras_anormalidades" id="outras_anormalidade_habilitar" value="t" <? if (@$obj->_outras_anormalidades == 't') echo "checked"; ?> onClick="habilitar_4b()"> SIM
                        <input type="radio" name="outras_anormalidades" id="outras_anormalidade_nao_habilitar" value="f" <? if (@$obj->_outras_anormalidades == 'f') echo "checked"; ?> onClick="habilitar_4b()"> NAO
                   </td>
                </tr>
            </table>
    </fieldset>

    <fieldset id="laudooit_4b" style="display: none;">
            <legend>4b</legend>
            <table>
                <tr>
                    </td><label><b>SÍMBOLOS</b></label></td>
                </tr>
                <tr>
                    <td>
                        <table class="linhas">
                            <tr>
                                <td>aa</td>
                                <td>at</td>
                                <td>ax</td>
                                <td>bu</td>
                                <td>ca</td>
                                <td>cg</td>
                                <td>cn</td>
                                <td>co</td>
                                <td>cp</td>
                                <td>cv</td>
                                <td>di</td>
                                <td>ef</td>
                                <td>em</td>
                                <td>es</td>
                                <td>fr</td>
                                <td>hi</td>
                                <td>ho</td>
                                <td>id</td>
                                <td>ih</td>
                                <td>kl</td>
                                <td>me</td>
                                <td>pa</td>
                                <td>pb</td>
                                <td>pi</td>
                                <td>px</td>
                                <td>ra</td>
                                <td>rp</td>
                                <td>tb</td>
                                <td>od*</td>                                                                                                                            
                            </tr>
            <?
             if (@$obj->_simbolos_array != '') {
                $simbolos_array = json_decode(@$obj->_simbolos_array);
             } else {
                $simbolos_array = array();
             }
             ?>
                            <tr>
                                <td><input type="checkbox" name="simbolos[]" value="aa" <?= (in_array('aa', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="at" <?= (in_array('at', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ax" <?= (in_array('ax', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="bu" <?= (in_array('bu', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ca" <?= (in_array('ca', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="cg" <?= (in_array('cg', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="cn" <?= (in_array('cn', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="co" <?= (in_array('co', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="cp" <?= (in_array('cp', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="cv" <?= (in_array('cv', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="di" <?= (in_array('di', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ef" <?= (in_array('ef', $simbolos_array)) ? 'checked' : ''; ?>></td>
                                <td><input type="checkbox" name="simbolos[]" value="em" <?= (in_array('em', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="es" <?= (in_array('es', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="fr" <?= (in_array('fr', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="hi" <?= (in_array('hi', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ho" <?= (in_array('ho', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="id" <?= (in_array('id', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ih" <?= (in_array('ih', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="kl" <?= (in_array('kl', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="me" <?= (in_array('me', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="pa" <?= (in_array('pa', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="pb" <?= (in_array('pb', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="pi" <?= (in_array('pi', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="px" <?= (in_array('px', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="ra" <?= (in_array('ra', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="rp" <?= (in_array('rp', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="tb" <?= (in_array('tb', $simbolos_array)) ? 'checked' : ''; ?> ></td>
                                <td><input type="checkbox" name="simbolos[]" value="od" <?= (in_array('od', $simbolos_array)) ? 'checked' : ''; ?> ></td>                                                                                                                            
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>(*) od: Necessário um comentário.</td>
                </tr>
            </table>

    </fieldset>


    <fieldset>
            <legend>4C</legend>
                <label>Coment&aacute;rio</label>
                <textarea name="comentario_4c" placeholder="Comentario" cols="100" rows="5"><?= @$obj->_comentario_4c; ?></textarea>
        </fieldset>

        <button type="submit" name="Enviar" value="Enviar">Enviar</button>
        <button type="submit" name="Enviar" value="EnviarImp">Enviar e Imprimir</button>
        <button type="reset">Limpar</button>
    </form>
</div>

<script>

    $(function() {
        $("#data").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    function habilitar_2a(){
      if(document.getElementById('radiografia_normal_habilitar').checked == true){
        document.getElementById('laudooit_2a').style.display = 'block';
        document.getElementById('laudooit_3a').style.display = 'block';
      }else{
        document.getElementById('laudooit_2a').style.display = 'none';
        document.getElementById('laudooit_3a').style.display = 'none';
      }
    }

    function habilitar_2b_c(){
      if(document.getElementById('anormalidade_parenquima_habilitar').checked == true){
        document.getElementById('laudooit_2b_c').style.display = 'block';
      }else{
        document.getElementById('laudooit_2b_c').style.display = 'none';
      }
    }

    function habilitar_3b_c_d(){
      if(document.getElementById('anormalidade_pleural_habilitar').checked == true){
        document.getElementById('laudooit_3b').style.display = 'block';
        document.getElementById('laudooit_3c').style.display = 'block';
        document.getElementById('laudooit_3d').style.display = 'block';
      }else{
        document.getElementById('laudooit_3b').style.display = 'none';
        document.getElementById('laudooit_3c').style.display = 'none';
        document.getElementById('laudooit_3d').style.display = 'none';
      }
    }

    function habilitar_4b(){
        if(document.getElementById('outras_anormalidade_habilitar').checked == true){
            document.getElementById('laudooit_4b').style.display = 'block';
        }else{
            document.getElementById('laudooit_4b').style.display = 'none';
        }
    }


    $(document).ready(function(){
        checked_radio();
    });

    function checked_radio(){
        if(document.getElementById('radiografia_normal_habilitar').checked == true){
            document.getElementById('laudooit_2a').style.display = 'block'; 
            document.getElementById('laudooit_3a').style.display = 'block';
        }

        if(document.getElementById('anormalidade_parenquima_habilitar').checked == true){
        document.getElementById('laudooit_2b_c').style.display = 'block';
        }

        if(document.getElementById('anormalidade_pleural_habilitar').checked == true){
        document.getElementById('laudooit_3b').style.display = 'block';
        document.getElementById('laudooit_3c').style.display = 'block';
        document.getElementById('laudooit_3d').style.display = 'block';
        }

        if(document.getElementById('outras_anormalidade_habilitar').checked == true){
            document.getElementById('laudooit_4b').style.display = 'block';
        }

      
    }
</script>