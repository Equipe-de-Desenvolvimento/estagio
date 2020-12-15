 
<meta charset="UTF-8">
<title>Relatório Mapa de Calor</title>
<div class="content"> <!-- Inicio da DIV content -->
    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Relatório Mapa de Calor</h4>
    <h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>
    <h4>Médico: <?
        if (count(@$medico) > 0 && $medico != null) {
            echo $medico[0]->operador;
        } else {
            echo 'TODOS';
        }
        ?>
    </h4>
    <!-- <h4>Sala: <?
        if (count(@$salas) > 0 && $salas != null) {
            echo $salas[0]->nome;
        } else {
            echo 'TODAS';
        }
        ?>
    </h4> -->
    <h4>
        Turno: <?
        if($_POST['turno'] == ''){
            echo 'TODOS';
        }else if($_POST['turno'] == '06:00'){
            echo 'MANHÃ';
        }else if($_POST['turno'] == '12:00'){
            echo 'TARDE';
        }else{
            echo 'NOITE';
        }
        ?>
    </h4>
    <?
    $total = 0;
    foreach ($relatorio as $key => $value) {
        $total+= $value->contador_sala;
    }?>
    <table border="1" cellspacing=0 cellpadding=2>
        <tr>
            <th style="width: 300px;">
                Sala
            </th>
            <th>
                Total
            </th>
            <th>
                %
            </th>
        </tr>
        <?foreach ($relatorio as $key => $value) {?>
            
            <tr>
                <td>
                    <?=$value->sala?>
                </td>
                <td>
                    <?=$value->contador_sala?>
                </td>
                <td>
                    <?=round(($value->contador_sala * 100)/$total, 2)?>%
                </td>
            </tr>
            
        <?}?>
        <tr>
            
        
            <th colspan="4">
                TOTAL: <?=$total?>
            </th>
        </tr>
    </table>

  
    <hr>

    <?
    @$empresa_id = $this->session->userdata('empresa_id');
    @$data['retorno'] = $this->exame->permisoesempresa($empresa_id);
    @$agenda_modificada = $data['retorno'][0]->agenda_modificada;
// var_dump($agenda_modificada); die;
    ?>

   

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>
