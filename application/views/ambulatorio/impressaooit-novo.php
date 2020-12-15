<html>
<head>
<meta charset='UTF-8'>
<title>Impressão OIT</title>
<style>
input[type="checkbox"] {
    -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
    -moz-appearance: checkbox;    /* Firefox */
    -ms-appearance: checkbox;     /* not currently supported */
}
body{
    color: green;
}

.fontemaior{
    font-size:17px;
}
.table{
    border-collapse: collapse;
}
.bordar{
    border: 0.5px solid green !important;
}
.bordar2{
    border-right: 0.5px solid green !important;
}
label{
    color: black !important;
    font-family: Arial;
}
.a4{
    width: 100%;
}
.img{
    width: 180px;
    height: 70px;
}

</style>
</head>

<body>

<?
//ARRAYS

// 2B
if (@$obj[0]->forma_primaria_array != '') {
    $forma_primaria = json_decode(@$obj[0]->forma_primaria_array);
} else {
    $forma_primaria = array();
}

if (@$obj[0]->forma_secundaria_array != '') {
    $forma_secundaria = json_decode(@$obj[0]->forma_secundaria_array);
} else {
    $forma_secundaria = array();
}

if (@$obj[0]->zonas_d_e_array != '') {
    $zonas_d_e_array = json_decode(@$obj[0]->zonas_d_e_array);
} else {
    $zonas_d_e_array = array();
}

if (@$obj[0]->profusao_array != '') {
    $profusao_array = json_decode(@$obj[0]->profusao_array);
} else {
    $profusao_array = array();
}


// 2C

if (@$obj[0]->grandes_opacidades_array != '') {
    $grandes_opacidades_array = json_decode(@$obj[0]->grandes_opacidades_array);
} else {
    $grandes_opacidades_array = array();
}

// 3B
if (@$obj[0]->local_paredeperfil_3b_array != '') {
    $local_paredeperfil_3b_array = json_decode(@$obj[0]->local_paredeperfil_3b_array);
} else {
    $local_paredeperfil_3b_array = array();
}

if (@$obj[0]->calcificacao_paredeperfil_3b_array != '') {
    $calcificacao_paredeperfil_3b_array = json_decode(@$obj[0]->calcificacao_paredeperfil_3b_array);
} else {
    $calcificacao_paredeperfil_3b_array = array();
}

if (@$obj[0]->local_frontal_3b_array != '') {
    $local_frontal_3b_array = json_decode(@$obj[0]->local_frontal_3b_array);
} else {
    $local_frontal_3b_array = array();
}

if (@$obj[0]->calcificacao_frontal_3b_array != '') {
    $calcificacao_frontal_3b_array = json_decode(@$obj[0]->calcificacao_frontal_3b_array);
} else {
    $calcificacao_frontal_3b_array = array();
}

if (@$obj[0]->local_diafragma_3b_array != '') {
    $local_diafragma_3b_array = json_decode(@$obj[0]->local_diafragma_3b_array);
} else {
    $local_diafragma_3b_array = array();
}

if (@$obj[0]->calcificacao_diafragma_3b_array != '') {
    $calcificacao_diafragma_3b_array = json_decode(@$obj[0]->calcificacao_diafragma_3b_array);
} else {
    $calcificacao_diafragma_3b_array = array();
}

if (@$obj[0]->local_outroslocais_3b_array != '') {
    $local_outroslocais_3b_array = json_decode(@$obj[0]->local_outroslocais_3b_array);
} else {
    $local_outroslocais_3b_array = array();
}

if (@$obj[0]->calcificacao_outroslocais_3b_array != '') {
    $calcificacao_outroslocais_3b_array = json_decode(@$obj[0]->calcificacao_outroslocais_3b_array);
} else {
    $calcificacao_outroslocais_3b_array = array();
}

if (@$obj[0]->extensao_parede_d_3b_array != '') {
    $extensao_parede_d_3b_array = json_decode(@$obj[0]->extensao_parede_d_3b_array);
} else {
    $extensao_parede_d_3b_array = array();
}

if (@$obj[0]->extensao_parede_e_3b_array != '') {
    $extensao_parede_e_3b_array = json_decode(@$obj[0]->extensao_parede_e_3b_array);
} else {
    $extensao_parede_e_3b_array = array();
}

if (@$obj[0]->largura_d_3b_array != '') {
    $largura_d_3b_array = json_decode(@$obj[0]->largura_d_3b_array);
} else {
    $largura_d_3b_array = array();
}

if (@$obj[0]->largura_e_3b_array != '') {
    $largura_e_3b_array = json_decode(@$obj[0]->largura_e_3b_array);
} else {
    $largura_e_3b_array = array();
}

// 3C
if (@$obj[0]->obliteracao_array != '') {
    $obliteracao_array = json_decode(@$obj[0]->obliteracao_array);
    } else {
    $obliteracao_array = array();
    }

// 3D

if (@$obj[0]->local_parede_perfil_3d_array != '') {
    $local_parede_perfil_3d_array = json_decode(@$obj[0]->local_parede_perfil_3d_array);
} else {
    $local_parede_perfil_3d_array = array();
}

if (@$obj[0]->calcificacao_parede_perfil_3d_array != '') {
    $calcificacao_parede_perfil_3d_array = json_decode(@$obj[0]->calcificacao_parede_perfil_3d_array);
} else {
    $calcificacao_parede_perfil_3d_array = array();
}

if (@$obj[0]->local_parede_frontal_3d_array != '') {
    $local_parede_frontal_3d_array = json_decode(@$obj[0]->local_parede_frontal_3d_array);
} else {
    $local_parede_frontal_3d_array = array();
}

if (@$obj[0]->calcificacao_parede_frontal_3d_array != '') {
    $calcificacao_parede_frontal_3d_array = json_decode(@$obj[0]->calcificacao_parede_frontal_3d_array);
} else {
    $calcificacao_parede_frontal_3d_array = array();
}

if (@$obj[0]->extensao_parede_d_3d_array != '') {
    $extensao_parede_d_3d_array = json_decode(@$obj[0]->extensao_parede_d_3d_array);
} else {
    $extensao_parede_d_3d_array = array();
}

if (@$obj[0]->extensao_parede_e_3d_array != '') {
    $extensao_parede_e_3d_array = json_decode(@$obj[0]->extensao_parede_e_3d_array);
} else {
    $extensao_parede_e_3d_array = array();
}

if (@$obj[0]->largura_d_3d_array != '') {
    $largura_d_3d_array = json_decode(@$obj[0]->largura_d_3d_array);
} else {
    $largura_d_3d_array = array();
}

if (@$obj[0]->largura_e_3d_array != '') {
    $largura_e_3d_array = json_decode(@$obj[0]->largura_e_3d_array);
} else {
    $largura_e_3d_array = array();
}

// 4B
if (@$obj[0]->simbolos_array != '') {
    $simbolos_array = json_decode(@$obj[0]->simbolos_array);
} else {
    $simbolos_array = array();
}

// 
$empresa_id = $this->session->userdata('empresa_id');
?>
    <table class="table">
        <tr>
            <!-- <td class="bordar"><img class="img" src='<?=base_url()?>/img/logooit.jpg'></td> -->
            <td class="bordar"><img class="img" src="<?= base_url(); ?>upload/logomarca/<?= $empresa_id; ?>/logomarca.jpg" alt="Logo Clinica" title="Logo Clinica" id="Insert_logo" /></td>
            <td align="center" class="fontemaior bordar"><b>FOLHA DE LEITURA RADIOLÓGICA - CLASSIFICAÇÃO INTERNACIONAL  DE RADIOGRAFIAS DE PNEUMOCONIOSE - OIT 2011<b></td>
        </tr>
    </table>

    <table class="a4 table">
            <tr>
                <td colspan='3' class="bordar">
                    <b>NOME</b> <br>                 
                    <label> <?= @$obj[0]->paciente; ?> </label>
                </td>
                <td class="bordar">
                    <b>DATA DO RX</b> <br>
                    <label><?= substr(@$obj[0]->data_cadastro, 8, 2) . '/' . substr(@$obj[0]->data_cadastro, 5, 2) . '/' . substr(@$obj[0]->data_cadastro, 0, 4); ?></label>
                </td>
            </tr>
            <tr>
                <td class="bordar">
                    <b>Nº DO RX</b> <br>
                   <label> <?= @$obj[0]->guia_id; ?> </label>
                </td>
                <td class="bordar">
                    <b>LEITOR</b> <br>
                    <label> <?= @$obj[0]->medico; ?> </label>

                </td>
                <td class="bordar">
                    <b>RX DIGITAL</b> <br>
                    <!-- <label> -->
                    <? echo (@$obj[0]->rx_digital == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM
                    <? echo (@$obj[0]->rx_digital == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO


                    <!-- <input type="checkbox" name="rx_digital" id="rx_digital" value="t" <? if (@$obj[0]->rx_digital == 't') echo "checked"; ?> > SIM -->
                    <!-- <input type="checkbox" name="rx_digital" id="rx_digital" value="f" <? if (@$obj[0]->rx_digital == 'f') echo "checked"; ?> > NÃO -->
                    <!-- </label> -->
                </td>
                <td class="bordar">
                    <b>LEITURA EM NEGATOSCÓPIO</b> <br>
                    <!-- <label> -->
                    <? echo (@$obj[0]->leitura_negatoscopio == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM
                    <? echo (@$obj[0]->leitura_negatoscopio == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO

                    <!-- <input type="checkbox" name="leitura_negatoscopio" id="leitura_negatoscopio" value="t" <? if (@$obj[0]->leitura_negatoscopio == 't') echo "checked"; ?> > SIM
                    <input type="checkbox" name="leitura_negatoscopio" id="leitura_negatoscopio" value="f" <? if (@$obj[0]->leitura_negatoscopio == 'f') echo "checked"; ?> > NÃO -->
                    <!-- </label> -->
                </td>
            </tr>
        </table>
        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>1A</b></td>
                <td><b> QUALIDADE TÉCNICA: </b></td>
                <td>
                    <table class="table">
                        <tr>
                        <td class="bordar"><? echo (@$obj[0]->qualidade_tecnica == '1' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> 1</td>
                        <td class="bordar"><? echo (@$obj[0]->qualidade_tecnica == '2' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> 2</td>
                        <td class="bordar"><? echo (@$obj[0]->qualidade_tecnica == '3' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> 3</td>
                        <td class="bordar"><? echo (@$obj[0]->qualidade_tecnica == '4' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> 4</td>


                            <!-- <td class="bordar"><input type="checkbox" name="qualidade_tecnica" id="qualidade_tecnica" value="1" <? if (@$obj[0]->qualidade_tecnica == '1') echo "checked"; ?> > 1</td>
                            <td class="bordar"><input type="checkbox" name="qualidade_tecnica" id="qualidade_tecnica" value="2" <? if (@$obj[0]->qualidade_tecnica == '2') echo "checked"; ?> > 2</td>
                            <td class="bordar"><input type="checkbox" name="qualidade_tecnica" id="qualidade_tecnica" value="3" <? if (@$obj[0]->qualidade_tecnica == '3') echo "checked"; ?> > 3</td>
                            <td class="bordar"><input type="checkbox" name="qualidade_tecnica" id="qualidade_tecnica" value="4" <? if (@$obj[0]->qualidade_tecnica == '4') echo "checked"; ?> > 4</td> -->
                        </tr>
                    </table>
                </td>
                <td width=30 class="bordar"><b>1B</b></td>
                <td><b> RADIOGRAFIA NORMAL: </b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <b>Comentário:</b> <br>
                   <label> <?= @$obj[0]->comentario_1a; ?> </label>
                </td>

                <td class="bordar2"></td>
                <td></td>
                
                <td>
                   <label>
                    <? echo (@$obj[0]->radiografia_normal == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM
                    <? echo (@$obj[0]->radiografia_normal == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO
                    <!-- <input type="checkbox" name="radiografia_normal" <? if (@$obj[0]->radiografia_normal == 't') echo "checked"; ?> > SIM
                    <input type="checkbox" name="radiografia_normal" <? if (@$obj[0]->radiografia_normal == 'f') echo "checked"; ?> > NAO -->
                   </label>
                </td>
            </tr>
        </table>
<br>
        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>2A</b></td>
                <td width=500><b>ALGUMA ANORMALIDADE DE PARÊNQUIMA<b></td>
                <td><? echo (@$obj[0]->anormalidade_parenquima == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM </td>
                <td><? echo (@$obj[0]->anormalidade_parenquima == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO </td>
                <!-- <td><label><input type="checkbox" name="anormalidade_parenquima" <? if (@$obj[0]->anormalidade_parenquima == 't') echo "checked"; ?> > SIM</label></td>
                <td><label><input type="checkbox" name="anormalidade_parenquima" <? if (@$obj[0]->anormalidade_parenquima == 'f') echo "checked"; ?> > NÃO</label></td> -->
            </tr>
            <tr>
            <td></td>
            <td><b>CONSISTENTE COM PNEUMOCONIOSE:</b></td>
            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>2B</b></td>
                <td colspan='16'><b>PEQUENAS OPACIDADES:</b></td>
                <!-- <td colspan='11'></td> -->
                <td width=30 class="bordar"><b>2C</b></td>
                <td colspan='4'><b>GRANDES OPACIDADES:</b></td>
            </tr>

            <tr>
                <td></td>
                <td colspan='6'>a) Forma / tamanho</td>
                <td colspan='4'>b) Zonas</td>
                <td></td>
                <td colspan='4'>c) Profusão</td>
                <td class="bordar2"></td>
                <td colspan='2'></td>
            </tr>

            <tr>

                <td></td>
                <td colspan='2' align='center' class="bordar">Primária</td>
                <td colspan='2'></td>
                <td colspan='2' align='center' class="bordar">Secundária</td>
                <td colspan='2'></td>
                <td class="bordar" align='center'>D</td>
                <td class="bordar" align='center'>E</td>
                <td colspan='2'></td>
                <td class="bordar" align='center'><?= (in_array('0/-', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>"; ?>  0/-</td>
                <td class="bordar" align='center'><?= (in_array('0/0', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>"; ?> 0/0</td>
                <td class="bordar" align='center'><?= (in_array('0/1', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>"; ?> 0/1</td>
                <td class="bordar2"></td>
            </tr>

            <tr>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('p', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> p</td>
                <td class="bordar" align='center'><?= (in_array('s', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> s</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('p', $forma_secundaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> p</td>
                <td class="bordar" align='center'><?= (in_array('s', $forma_secundaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> s</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('p', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td class="bordar" align='center'><?= (in_array('s', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('1/0', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1/0</td>
                <td class="bordar" align='center'><?= (in_array('1/1', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1/1</td>
                <td class="bordar" align='center'><?= (in_array('1/2', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1/2</td>
                <td class="bordar2"></td>
                <td></td>
                <td class="bordar" align='center' rowspan='2'><?= (in_array('0', $grandes_opacidades_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center' rowspan='2'><?= (in_array('a', $grandes_opacidades_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?>  A</td>
                <td class="bordar" align='center' rowspan='2'><?= (in_array('b', $grandes_opacidades_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?>  B</td>
                <td class="bordar" align='center' rowspan='2'><?= (in_array('c', $grandes_opacidades_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?>  C</td>
            </tr>

            <tr>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('q', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> q</td>
                <td class="bordar" align='center'><?= (in_array('t', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> t</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('q', $forma_secundaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> q</td>
                <td class="bordar" align='center'><?= (in_array('t', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> t</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('q', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td class="bordar" align='center'><?= (in_array('t', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('2/0', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2/0</td>
                <td class="bordar" align='center'><?= (in_array('2/1', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2/1</td>
                <td class="bordar" align='center'><?= (in_array('2/2', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2/2</td>
                <td class="bordar2"></td>
            </tr>

            <tr>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('r', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> r</td>
                <td class="bordar" align='center'><?= (in_array('u', $forma_primaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> u</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('r', $forma_secundaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> r</td>
                <td class="bordar" align='center'><?= (in_array('u', $forma_secundaria)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> u</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('r', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td class="bordar" align='center'><?= (in_array('u', $zonas_d_e_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> </td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('3/0', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3/0</td>
                <td class="bordar" align='center'><?= (in_array('3/1', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3/1</td>
                <td class="bordar" align='center'><?= (in_array('3/2', $profusao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3/2</td>
                <td class="bordar2"></td>
            </tr>
        </table>
<br>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>3A</b></td>
                <td width=500><b>ALGUMA ANORMALIDADE PLEURAL<b></td>
                <td><? echo (@$obj[0]->anormalidade_pleural == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM</td>
                <td><? echo (@$obj[0]->anormalidade_pleural == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO</td>
            </tr>
            <tr>
            <td></td>
            <td><b>CONSISTENTE COM PNEUMOCONIOSE:</b></td>
            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>3B</b></td>
                <td colspan='20'>
                    <b>PLACAS PLEURAIS:</b>
                    <? echo (@$obj[0]->placa_pleuras == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM
                    <? echo (@$obj[0]->placa_pleuras == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO
                </td>
            </tr>

            <tr>
                <td colspan='2'></td>
                <td colspan='3'>LOCAL</td>
                <td></td>
                <td colspan='3'>CALCIFICAÇÃO</td>
                <td colspan='2'></td>
                <td colspan='6'>EXTENSÃO: PAREDE <br> (Combinado em perfil e frontal)</td>
                <td colspan='2'></td>
                <td colspan='5'>LARGURA (OPCIONAL)<br> (Mínimo de 3 mm para marcação)</td>

            </tr>

            <tr>
                <td></td>
                <td>Parede <br> em perfil</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_paredeperfil_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $extensao_parede_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $extensao_parede_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('1', $extensao_parede_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1</td>
                <td class="bordar" align='center'><?= (in_array('2', $extensao_parede_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2</td>
                <td class="bordar" align='center'><?= (in_array('3', $extensao_parede_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('d', $largura_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('a', $largura_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> a</td>
                <td class="bordar" align='center'><?= (in_array('b', $largura_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> b</td>
                <td class="bordar" align='center'><?= (in_array('c', $largura_d_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> c</td>
            </tr>

            <tr>
                <td></td>
                <td>Frontal</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_frontal_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $extensao_parede_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('e', $extensao_parede_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('1', $extensao_parede_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1</td>
                <td class="bordar" align='center'><?= (in_array('2', $extensao_parede_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2</td>
                <td class="bordar" align='center'><?= (in_array('3', $extensao_parede_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('e', $largura_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('a', $largura_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> a</td>
                <td class="bordar" align='center'><?= (in_array('b', $largura_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> b</td>
                <td class="bordar" align='center'><?= (in_array('c', $largura_e_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> c</td>
            </tr>

            <tr>
                <td></td>
                <td>Diafragma</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_diafragma_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
                <td colspan='6' rowspan='2'>
                Até 1/4 da parede lateral = 1 <br>
                1/4 a 1/2 da parede lateral = 2 <br>
                > 1/2 da parede lateral = 3
                </td>
                <td></td>
                <td></td>
                <td colspan='5' rowspan='2'>
                3 a 5 mm = a <br>
                5 a 10 mm = b <br>
                > 10 mm = c
                </td>
            </tr>

            <tr>
                <td></td>
                <td>Outros <br> Locais</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_outroslocais_3b_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>3C</b></td>
                <td width=500><b>OBLITERAÇÃO DO SEIO COSTOFRÊNICO:<b></td>
                <td class="bordar" align='center'><?= (in_array('0', $obliteracao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $obliteracao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $obliteracao_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>3D</b></td>
                <td colspan='20'>
                <b>ESPESSAMENTO PLEURAL DIFUSO:</b>
                <? echo (@$obj[0]->espessamento_pleural_difuso == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM
                <? echo (@$obj[0]->espessamento_pleural_difuso == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO
                </td>
            </tr>

            <tr>
                <td colspan='2'></td>
                <td colspan='3'>LOCAL</td>
                <td></td>
                <td colspan='3'>CALCIFICAÇÃO</td>
                <td colspan='2'></td>
                <td colspan='6'>EXTENSÃO: PAREDE <br> (Combinado em perfil e frontal)</td>
                <td colspan='2'></td>
                <td colspan='5'>LARGURA (OPCIONAL)<br> (Mínimo de 3 mm para marcação)</td>
            <tr>

            <tr>
                <td></td>
                <td>Parede <br> em perfil</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_parede_perfil_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $extensao_parede_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $extensao_parede_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('1', $extensao_parede_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1</td>
                <td class="bordar" align='center'><?= (in_array('2', $extensao_parede_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2</td>
                <td class="bordar" align='center'><?= (in_array('3', $extensao_parede_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('d', $largura_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('a', $largura_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> a</td>
                <td class="bordar" align='center'><?= (in_array('b', $largura_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> b</td>
                <td class="bordar" align='center'><?= (in_array('c', $largura_d_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> c</td>
            </tr>

            <tr>
                <td></td>
                <td>Frontal</td>
                <td class="bordar" align='center'><?= (in_array('0', $local_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $local_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $local_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $calcificacao_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('d', $calcificacao_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> D</td>
                <td class="bordar" align='center'><?= (in_array('e', $calcificacao_parede_frontal_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('0', $extensao_parede_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 0</td>
                <td class="bordar" align='center'><?= (in_array('e', $extensao_parede_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('1', $extensao_parede_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 1</td>
                <td class="bordar" align='center'><?= (in_array('2', $extensao_parede_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 2</td>
                <td class="bordar" align='center'><?= (in_array('3', $extensao_parede_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> 3</td>
                <td></td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('e', $largura_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> E</td>
                <td></td>
                <td class="bordar" align='center'><?= (in_array('a', $largura_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> a</td>
                <td class="bordar" align='center'><?= (in_array('b', $largura_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> b</td>
                <td class="bordar" align='center'><?= (in_array('c', $largura_e_3d_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?> c</td>
            </tr>

            <tr>
                <td colspan="11"></td>
                <td colspan='6'>
                Até 1/4 da parede lateral = 1 <br>
                1/4 a 1/2 da parede lateral = 2 <br>
                > 1/2 da parede lateral = 3
                </td>
                <td></td>
                <td></td>
                <td colspan='5'>
                3 a 5 mm = a <br>
                5 a 10 mm = b <br>
                > 10 mm = c
                </td>
            </tr>

        </table>

<br>
        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>4A</b></td>
                <td width=500><b>OUTRAS ANORMALIDADES:<b></td>
                <td><? echo (@$obj[0]->outras_anormalidades == 't' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> SIM</td>
                <td><? echo (@$obj[0]->outras_anormalidades == 'f' ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>" ); ?> NÃO</td>

            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>4B</b></td>
                <td colspan='20'><b>SÍMBOLOS:<b></td>
            </tr>

            <tr>
            <td colspan='20'></td>
            </tr>

            <tr>

                <td class="bordar">aa</td>
                <td class="bordar">at</td>
                <td class="bordar">ax</td>
                <td class="bordar">bu</td>
                <td class="bordar">ca</td>
                <td class="bordar">cg</td>
                <td class="bordar">cn</td>
                <td class="bordar">co</td>
                <td class="bordar">cp</td>
                <td class="bordar">cv</td>
                <td class="bordar">di</td>
                <td class="bordar">ef</td>
                <td class="bordar">em</td>
                <td class="bordar">es</td>
                <td class="bordar">fr</td>
                <td class="bordar">hi</td>
                <td class="bordar">ho</td>
                <td class="bordar">id</td>
                <td class="bordar">ih</td>
                <td class="bordar">kl</td>
                <td class="bordar">me</td>
                <td class="bordar">pa</td>
                <td class="bordar">pb</td>
                <td class="bordar">pi</td>
                <td class="bordar">px</td>
                <td class="bordar">ra</td>
                <td class="bordar">rp</td>
                <td class="bordar">tb</td>
                <td class="bordar">od*</td>
            </tr>

            <tr>

               <td class="bordar"><?= (in_array('aa', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('at', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ax', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('bu', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ca', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('cg', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('cn', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('co', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('cp', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('cv', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('di', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ef', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('em', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('es', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('fr', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('hi', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ho', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('id', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ih', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('kl', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('me', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('pa', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('pb', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('pi', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('px', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('ra', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('rp', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('tb', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>
               <td class="bordar"><?= (in_array('od', $simbolos_array)) ? "<img src='".base_url()."/img/check.png'>" : "<input type='checkbox' disabled>";?></td>    
            </tr>

            <tr>
                <td></td>
                <td colspan="20">(*) od: Necessário um comentário.</td>
            </tr>
        </table>

        <table class="table bordar a4">
            <tr>
                <td width=30 class="bordar"><b>4C</b></td>
                <td ><b>COMENTÁRIOS:<b></td>
            </tr>

            <tr>
                <td></td>
                <td>
                <textarea name="comentario_4c" placeholder="Comentario" cols="100" rows="1" readonly style="resize: none"><?= @$obj[0]->comentario_4c; ?></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table class="table bordar a4">
            <tr>
                <td width=300 class="bordar2"><b>DATA DA LEITURA<b></td>
                <td><b>ASSINATURA</b></td>
            </tr>

            <tr>
                <td width=300 class="bordar2">
                    <label><?=substr(@$obj[0]->data, 8, 2) . '/' . substr(@$obj[0]->data, 5, 2) . '/' . substr(@$obj[0]->data, 0, 4); ?></label>
                </td>
                <td align='center'>
                <?
                if (file_exists("upload/1ASSINATURAS/" . $obj[0]->medico_parecer . ".jpg")) {
                    echo "<img class='img' src='" . base_url() . "./upload/1ASSINATURAS/" . $obj[0]->medico_parecer . ".jpg'>";
                }else if(file_exists("upload/1ASSINATURAS/" . $obj[0]->medico_parecer . ".bmp")){
                    echo "<img class='img' src='" . base_url() . "./upload/1ASSINATURAS/" . $obj[0]->medico_parecer . ".bmp'>";
                }else{
                    echo '';
                }
                ?>
                </td>
            </tr>
        </table>
        <?
        //  die; 
         ?>
</body>
</html>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    window.print();


</script>