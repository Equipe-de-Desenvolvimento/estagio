
<title>Relatório Vagas Associadas</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<h4>RELATÓRIO VAGAS ASSOCIADAS</h4>
<h4>PERIODO: <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_inicio))); ?> ate <?= str_replace("-", "/", date("d-m-Y", strtotime($txtdata_fim))); ?></h4>

<?if($instituicao > 0){?>
    <h4>INSTITUIÇÃO: <?= $instituicao_nome; ?></h4>
<?}else{?>
    <h4>INSTITUIÇÃO: TODAS </h4>
<?}?>

<br><br><br>
<table border="1" style="width:100%">
    <tr>
        <th>Qtde Total</th>
        <th>Instituição</th>
        <th>Tipo</th>
    </tr>

    <?foreach($relatorio as $item){?>
        <tr align="center">
            <td><?=$item->total?></td>
            <td><?=$item->nome_fantasia?></td>
            <td><?=$item->tipo_vaga?></td>
        </tr>
    <?}?>
</table>