<?php
//echo "<pre>";
//print_r($procedimentos_cadastrados);
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ficha</title>
        <style>
            td{
                font-family: arial;
            }img{
                max-height: 150px; 
                /*                margin-left:11%;*/

            }#espaco{
                height: 35px;

            }
        </style>
    </head>
    <body>
        <table border=0 cellspacing=0 cellpadding=2 bordercolor="666633">
            <tr valign="top">
                <td  colspan="1">Nome: </td>
                <td><?= @$listapaciente[0]->paciente; ?> </td>

                <td rowspan="2">  <?= @$dadosimpressao[0]->cabecalho; ?> </td>
            </tr>
            <tr  valign="top">
                <td colspan="1">Pront: </td>
                <td>
                    <?
                    if (@$listapaciente[0]->prontuario_antigo == "") {
                        echo "____________________________";
                    } else {
                        echo @$listapaciente[0]->prontuario_antigo;
                    }
                    ?>
                </td>

            </tr>
           

            <tr>
                <td colspan="3" ><hr style="color: black;background-color:black;height: 5px;"></td>
            </tr>
            <tr >
                <td colspan="2" id="espaco" style="">UNID:
                    <?php
                    if (@$sala[0]->nome != "") {
                        echo @$sala[0]->nome;
                    }else{
                    ?>_________________________________
                    <?php }?>
                </td>

                <td>LEITO:____________________________________</td>
            </tr>
            <tr >
                <td colspan="3" id="espaco">DATA DA REQUISIÇÃO: <?= date("d/m/Y", strtotime(@$procedimentos_cadastrados[0]->data_requisicao)) ?></td>

            </tr>

            <tr>
                <td colspan="2" id="espaco">DATA DE NASCIMENTO: <?= date("d/m/Y", strtotime(@$listapaciente[0]->nascimento)); ?></td>

                <td>SEXO: <?php
                    if (@$listapaciente[0]->sexo == "M") {
                        echo "M";
                    }
                    if (@$listapaciente[0]->sexo == "F") {
                        echo "F";
                    }
                    ?>


                </td>
            </tr>
           
            <tr>
                <td colspan="3" id="espaco">MÉDICO RESP.: ___________________________ </td>

            </tr>
            <tr>
                <td colspan="3" id="espaco">DIAGNÓSTICO PROVÁVEL: <?php
                    if (@$procedimentos_cadastrados[0]->dig_provavel != "") {
                        echo @$procedimentos_cadastrados[0]->dig_provavel;
                    } else {
                        echo "_______________________________";
                        ?>
                        <?php
                    }
                    ?> </td>

            </tr>
            <tr>
                <td colspan="3" id="espaco"><hr style="color: black;background-color:black;height: 5px;"></td>
            </tr>
            <tr>
                <td colspan="3" id="espaco">SOLICITAÇÃO:


                    <?php
//                    echo "<pre>";
//                    print_r($procedimentos_cadastrados);

                    @$cont2 = count($procedimentos_cadastrados);
                    foreach ($procedimentos_cadastrados as $item) {
                        @$contverif2++;
                        if (@$contverif2 == @$cont2) {
                            @$barra2 = "";
                        } else {
                            @$barra2 = " / ";
                        }
                        if (@$item->emergencia == "t") {
                            @$tipo = "Emergência";
                        } elseif (@$item->eletivo == "t") {
                            @$tipo = "Eletivo";
                        } else {
                            
                        }

                        if ($item->procedimento != "") {
                            $nomeprocedimento = $item->procedimento;
                        } else {
                            $nomeprocedimento = $item->procedimento_escrito;
                        }

                        echo "(" . @$nomeprocedimento . ") (" . @$item->quantidade . ") (" . @$tipo . ") " . @$barra2;
                    }
                    ?>
                </td> 
            </tr>
            <tr>
                <td colspan="3" id="espaco">DADOS CLINICOS: <?php
                    @$ulti = @$cont2 - 1;

                    if (@$procedimentos_cadastrados[$ulti]->justificativa == "") {
                        echo "_________________________________<br>_______________________________________________<br>_______________________________________________<br>_______________________________________________<br>_______________________________________________";
                    } else {
                        echo @$procedimentos_cadastrados[$ulti]->justificativa;
                    }
                    ?> 



                </td>
            </tr>
            <tr>
                <td colspan="3" id="espaco" >MÉDICO SOLICITANTE: <?php
                    if ($procedimentos_cadastrados[$ulti]->carimbo == "") {
                        echo "___________________________<br>_______________________________________________<br>_______________________________________________<br>_______________________________________________<br>_______________________________________________";
                    } else {
                        echo @$procedimentos_cadastrados[$ulti]->carimbo;
                    }
                    ?></td>
            </tr>

        </table>
    </body>
</html>
