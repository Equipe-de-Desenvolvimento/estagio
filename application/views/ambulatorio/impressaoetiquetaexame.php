
<div class="content ficha_ceatox">

    <table>
        <tbody>
            <tr>
                <td colspan="2" ><font size = -1><center><?= ($empresa[0]->nome) ?></center></td>
        </tr>
        <tr>
            <td  ><font size = -2><b><?= str_replace("-", "/", $emissao); ?>-<?= $paciente['0']->nome; ?></b></td>
            <td ><font size = -2></td>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <?
            $i = 0;
            foreach ($exames as $item) :
                $i++;
                if ($item->grupo == $exame[0]->grupo) {
                    ?>
                <td ><font size = -2><?= ($item->procedimento) ?> / </td>
                <?
                if ($i == 2) {
                    $i = 0;
                    ?><tr>
                            <?
                        }
                    }
                endforeach;
                ?>
            </tbody>
    </table>
</div>
