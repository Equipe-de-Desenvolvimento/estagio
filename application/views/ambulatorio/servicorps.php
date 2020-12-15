<html>
<body>
<style>
table{
    border-collapse: collapse;
}
.bordar{
    border: 0.5px solid black !important;
}
/* .letrar_menor{
    font-size: 10px;
} */
</style>

<table style="width:100%" class="bordar">
    <tr>
        <td align="center" style="width:80%" class="bordar">
            <b>PREFEITURA MUNICIPAL DE SÃO PAULO </b><br>
            SECRETARIA MUNICIPAL DA FAZENDA <br>
            RPS - Recibo Provisório de Serviço
        </td>
        <td align="center" style="width:20%">
            Número da Nota <br>
            <?=$numeronota?> <br>
            <font size="2">Emitida em <?=date('d/m/Y');?></font>
        </td>
    </tr>
</table>

<table style="width:100%" class="bordar">
    <tr>
        <th colspan='4'>PRESTADOR DE SERVIÇOS</th>
    </tr>
    <tr>
        <?
        $nbr_cnpj = @$empresa[0]->cnpj;
        //30.869.446/0001-18
        @$parte_um = substr($nbr_cnpj, 0, 2);
        @$parte_dois = substr($nbr_cnpj, 2, 3);
        @$parte_tres = substr($nbr_cnpj, 5, 3);
        @$parte_quatro = substr($nbr_cnpj, 8, 4);
        @$parte_cinco = substr($nbr_cnpj, 12, 2);

        @$monta_cnpj = "$parte_um.$parte_dois.$parte_tres/$parte_quatro-$parte_cinco";
        ?>
        <td>CPF/CNPJ: <?=$monta_cnpj?></td>
        <td></td>
        <td>Inscrição Municipal:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">Nome/Razão Social:  <?=$empresa[0]->razao_social?></td>
    </tr>
    <tr>
        <td colspan="3">Endereço: <?=$empresa[0]->logradouro." ".$empresa[0]->numero?></td>
        <td>CEP: <?=$empresa[0]->cep?></td>
    </tr>
    <tr>
        <td colspan="2">Município: <?=$empresa[0]->municipio?></td>
        <td colspan="2">UF: <?=$empresa[0]->estado?></td>
    </tr>
</table>

<table style="width:100%" class="bordar">
    <tr>
        <th colspan='4'>TOMADOR DE SERVIÇOS</th>
    </tr>
    <tr>
    <?
    $nbr_cpf = @$paciente[0]->cpf;

    @$parte_um = substr($nbr_cpf, 0, 3);
    @$parte_dois = substr($nbr_cpf, 3, 3);
    @$parte_tres = substr($nbr_cpf, 6, 3);
    @$parte_quatro = substr($nbr_cpf, 9, 2);

    @$monta_cpf = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";
    ?>
        <td>CPF/CNPJ: <?=@$monta_cpf?></td>
        <td></td>
        <td>Inscrição Municipal:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">Nome/Razão Social:  <?=$paciente[0]->nome?></td>
    </tr>
    <tr>
        <td colspan="3">Endereço: <?=$paciente[0]->logradouro." ".$paciente[0]->numero." - ".$paciente[0]->bairro?></td>
        <td>CEP: <?=$paciente[0]->cep?></td>
    </tr>
    <tr>
        <td colspan="2">Município: <?=$paciente[0]->cidade?></td>
        <td colspan="2">UF: <?=$paciente[0]->estado?></td>
    </tr>

</table>

<table style="width:100%" class="bordar" border="0">
    <tr>
        <th colspan='6'>DISCRIMINAÇÃO DOS SERVIÇOS</th>
    </tr>
    <tr>
        <td colspan="6"><br></td>
    </tr>
    <tr>
        <td colspan="2" style="width:50px">Nome do Procedimento</td>
        <td>TUSS</td>
        <td>Sessões</td>
        <td>Valor por Sessão</td>
        <td>Valor Total</td>
    </tr>
    <? foreach($procedimentos as $item){
        $valor_por_sessão = $item->valor_tcd / $item->qtde;?>
        <tr>
            <td colspan="2" style="width:50px"><?=$item->procedimento?></td>
            <td align="center"><?=$item->codigo?></td>
            <td align="center"><?=$item->qtde?></td>
            <td align="center"><?=number_format($valor_por_sessão, 2, ',', '.');?></td>
            <td align="center"><?=number_format($item->valor_tcd, 2, ',', '.');?></td>
        </tr>
    <?}?>


    <tr>
        <td colspan="5" align="right">TOTAL:</td>
        <td><?=$total[0]->total?></td>
    </tr>
    <tr>
        <td colspan="6"><br></td>
    </tr>
    <tr>
        <td colspan="6">O percentual aproximado de tributos incidentes a esta prestação </td>
    </tr>
    <tr>
        <td colspan="6">de serviços é de 14%, conforme a lei 12.741/2012 </td>
    </tr>
    <tr>
        <td colspan="6"><br><br><br><br></td>
    </tr>

</table>

<table  class="bordar" border="0">
    <tr>
        <th colspan='10'>VALOR TOTAL DO SERVIÇO = <?=number_format($total[0]->total, 2, ',', '.');?></th>
    </tr>
    <tr align="center" class="letrar_menor">
        <td colspan="2">INSS (R$) -</td>
        <td colspan="2">IRRF (R$) -</td>
        <td colspan="2">CSLL (R$) -</td>
        <td colspan="2">COFINS (R$) -</td>
        <td colspan="2">PIS/PASEP (R$) -</td>
    </tr>
    <tr>
        <td colspan="10">Código do Serviço: 04030 - Medicina e Biomedicina.</td>
    </tr>
    <tr>
        <td colspan="2">Total de Deduções: -</td>
        <td colspan="3">Base de Cálc.: R$ <?=number_format($total[0]->total, 2, ',', '.');?></td>
        <td colspan="2">Alíquota: 2%</td>
        <td colspan="1">Valor do ISS:</td>

        <? $valor_iss = $total[0]->total * (2/100);?>
        <td>R$ <?=number_format($valor_iss, 2, ',', '.');?></td>
        <td>Crédito:</td>
    </tr>
    <tr>
        <td colspan="3">Município da Prestação de Serviço</td>
        <td colspan="5" align="right">Valor aproximado dos Tributos / Fonte: </td>
        <? $valor_tributo = $total[0]->total * (14/100);?>
        <td>R$ <?=number_format($valor_tributo, 2, ',', '.');?></td>
        <td>(14%)</td>
    </tr>
</table>
<table  class="bordar" border="0">
    <tr>
        <th colspan='4'>OUTRAS INFORMAÇÕES</th>
    </tr>
    <tr>
        <td colspan='4'> Este RPS tem validade de 30 (trinta) dias. Após o vencimento cabe ao prestador do serviço emitir uma NFS-e para subistitui-lo </td>
    </tr>
</table>
</body>
</html>
