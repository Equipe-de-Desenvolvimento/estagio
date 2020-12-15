<html>
    <meta charset="UTF-8">
    <body>
        <?        
        if(count($exames) == 0){?>
            <h3>Não há resultados para essa guia</h3>
        <?die;
        }
        $autorizacoes = '';
        foreach ($exames as $key => $value) {
            if($value->autorizacao != ''){
                $autorizacoes .= $value->autorizacao . ' / ';
            }
            
        }
        ?>
        <?= @$cabecalho; ?>
        <br>
        <br>
        <table>
            <?
            $grupo = '';
            $subtotal = 0;
            $arrayGrupo = array();
            
            ?>
            <?foreach ($grupos as $key => $value) {?>
            <?$subtotal = 0;?>
                <tr>
                    <td colspan="1" style="text-decoration: underline; font-weight: bold; width: 200px;">
                        <?=$value->grupo;?>
                    </td>
                    <td style="text-decoration: underline; font-weight: bold; width: 200px;">
                        Quantidade
                    </td>
                    <td style="text-decoration: underline; font-weight: bold; width: 200px;">
                        Unitário
                    </td>
                    <td style="text-decoration: underline; font-weight: bold; width: 200px;">
                        Total
                    </td>
                </tr>
                <?foreach ($examesgrupo as $key2 => $item) {?>
                    <?if($item->grupo == $value->grupo){?>
                        <?$subtotal += $item->valor_total;?>
                        <?$grupo = $value->grupo;?>
                        <tr>
                            <td>
                                <?=$item->procedimento;?>
                            </td>
                            <td>
                                <?=$item->quantidade;?>
                            </td>
                            <td>
                                <?=number_format($item->valor_total / $item->quantidade, 2, ',', '.');?>
                            </td>
                            <td>
                                <?=number_format($item->valor_total, 2, ',', '.');?>
                            </td>
                            <td>
                                <?=$item->codigo;?>
                            </td>
                        </tr>
                    <?}?>
                <?}?>
                <?
                $arrayGrupo[$grupo] = $subtotal;
                ?>
                
                <tr >
                    <td colspan="2">
                    &nbsp;
                    </td>
                    <td style="font-weight: bold; padding-top: 10px; padding-bottom: 10px;" >
                        Sub total:
                    </td>
                    <td style="font-weight: bold;" colspan="2">
                    R$ <?=number_format($subtotal, 2, ',', '.');?>
                    </td>
                </tr>
               
            <?}?>
        </table>
        <table>
            <tr>
                <td colspan="1" style="text-decoration: underline; padding-bottom: 10px; width: 200px;">
                    RESUMO
                </td>
                <td style="text-decoration: underline; width: 100px; padding-bottom: 10px; ">
                    VALOR
                </td>
            </tr>
            <?$totalGeral = 0;?>
            <?foreach ($arrayGrupo as $key => $value) {?>
                <?
                $totalGeral += $value;
                ?>
                <tr>
                    <td>
                        <?=$key?>
                    </td>
                    <td>
                    R$ <?=number_format($value, 2, ',', '.');?>
                    </td>
                </tr>
            <?}?>
            <tr>
                <td style="width: 100px; padding-top: 10px; font-weight: bold;">
                    TOTAL GERAL
                </td>
                <td style=" width: 100px; padding-top: 10px; font-weight: bold;">
                    R$ <?=number_format($totalGeral, 2, ',', '.');?>
                </td>
            </tr>
            
        </table>
    </body>
</html>