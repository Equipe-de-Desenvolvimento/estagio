 <meta charset="UTF-8">
 <title>Relatório Paciente Telefone</title>
<div class="content"> <!-- Inicio da DIV content -->
    <? if ($_POST['empresa'] > 0) { ?>
        <h4><?= @$relatorio[0]->empresa ?></h4>  
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4> 
    <? } ?>


    <h4>Relatorio Paciente Telefone</h4>
    <h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>
</h4>
<hr>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th class="tabela_header" width="350px;">CPF</th>
            <th class="tabela_header" width="350px;">Paciente</th>
            <th class="tabela_header">Data de Nasc</th>
            <th class="tabela_header">Idade</th>
            <th class="tabela_header">Grupo</th>
            <th class="tabela_header">Convenio</th>
            <th class="tabela_header" width="450px;">Procedimento</th>
            <th class="tabela_header">Telefone</th>
            <th class="tabela_header">Telefone 2</th>
            <th class="tabela_header">Whatsapp</th>
            <th class="tabela_header">Endereço</th>
            <th class="tabela_header">Email</th>
            <th class="tabela_header">Data</th>
            <th class="tabela_header">Previsao Retorno</th>
            <th class="tabela_header">Empresa</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $paciente = "";
        $convenio = "";

        if (count($relatorio) > 0) {
            foreach ($relatorio as $item) {


                if ($this->utilitario->validaCPF($item->cpf) || $_POST['cpf'] == 'NAO') {


                    if ($item->celular != "") {
                        @$celular = $item->celular;
                    }
                    
                    if ($item->telefone != "") {
                       @ $telefone = $item->telefone;
                    } 
                    if ($item->whatsapp != "") {
                        @$whatsapp = $item->whatsapp;
                    } 
                    


                    $data = $item->data;
                    $dia = strftime("%A", strtotime($data));

                    switch ($dia) {
                        case"Sunday": $dia = "Domingo";
                            break;
                        case"Monday": $dia = "Segunda";
                            break;
                        case"Tuesday": $dia = "Terça";
                            break;
                        case"Wednesday": $dia = "Quarta";
                            break;
                        case"Thursday": $dia = "Quinta";
                            break;
                        case"Friday": $dia = "Sexta";
                            break;
                        case"Saturday": $dia = "Sabado";
                            break;
                    }
                    // var_dump($item->nascimento); die;
                    if ($item->nascimento != '') {
                        $dataFuturo = date("Y-m-d");
                        $dataAtual = $item->nascimento;
                        $date_time = new DateTime($dataAtual);
                        $diff2 = $date_time->diff(new DateTime($dataFuturo));
                        $idade = $diff2->format('%Ya %mm %dd');
                    } else {
                        $idade = '';
                    }
                    ?>



                    <tr>
                        <td><?= $item->cpf ?></td>        
                        <td><?= $item->paciente; ?></td>
                        <td><?= ($item->nascimento != '') ? date("d/m/Y", strtotime($item->nascimento)) : ''; ?></td>
                        <td><?= $idade; ?></td>
                        <td><?= $item->grupo; ?></td>
                        <td><?= $item->convenio; ?></td>
                        <td><?= $item->nome; ?></td>
                        <td ><?= @$telefone; ?></td>
                        <td ><?= @$celular; ?></td>
                        <td ><?= @$whatsapp; ?></td>
                        <td ><?= $item->logradouro . ", " . $item->numero . ", " . $item->bairro . ", " . $item->cidade . " - " . $item->estado; ?></td>
                        <td ><?= $item->email; ?></td>
                        <td><?= date("d/m/Y", strtotime($item->data)); ?></td>
                        <td><?
            if ($item->dias_retorno != "") {
                echo date("d/m/Y", strtotime("+" . $item->dias_retorno . ' day', strtotime($item->data)));
            } else {
                echo "--";
            }
                    ?>
                        </td>
                        <td><?= $item->empresa; ?></td>
                    </tr>

                </tbody>
                <?php
            }
        }
    }
    ?>

</table>

</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function () {
        $("#accordion").accordion();
    });

</script>
