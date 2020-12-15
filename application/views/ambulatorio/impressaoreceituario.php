

<link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" />
<meta charset="utf8"/>
<?
if (file_exists("./upload/operadortimbrado/" . $laudo['0']->medico_parecer1 . ".png")) {
    $caminho_background = base_url() . "upload/operadortimbrado/" . $laudo['0']->medico_parecer1 . ".png";
} else {
    $caminho_background = base_url() . 'upload/timbrado/timbrado.png';
}
?>
<BODY>
    <div class="teste" style="background-size: contain;height: 100%;width: 90%;background-image: url(<?= $caminho_background; ?>);">
        <? if (@$receituario != NULL) { ?>
                <center><p style="text-align: center; font-weight: bold;"></p></center>
        <? } ?>
        <br>
     

        <?= $laudo['0']->texto; ?><br/>

        <? if (@$atestado != NULL) { ?>
            <?
            if (@$imprimircid == "t") {
                if (isset($cid['0']->co_cid) && isset($cid['0']->no_cid)) {
                    ?>

                    <center>Cid Principal: <? echo $cid['0']->co_cid . "-" . $cid['0']->no_cid; ?></center><br/>


            <?
        }
        if (isset($cid2['0']->co_cid) && isset($cid2['0']->no_cid)) {
            ?>

            <center>Cid Secundario: <? echo $cid2['0']->co_cid . "-" . $cid2['0']->no_cid; ?></center><br/>


        <? } ?>

        <center>Resolução CFM 1.658/2002 - Art. 5º - Os médicos somente podem fornecer atestados com o diagnóstico codificado ou não quando por justa causa, exercício de dever legal, solicitação do próprio paciente ou de seu representante legal.</center>

    <? }
}
?>

<?
if (isset($operador_assinatura)) {
    $this->load->helper('directory');
    $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
    foreach ($arquivo_pasta as $value) {
        if ($value == $operador_assinatura . ".jpg") {
            ?>

                                                <td><img width="200px;" height="50px;" src="<?= base_url() . "upload/1ASSINATURAS/$value" ?>" /></td>

            <?
        }
    }
}

if ($laudo[0]->carimbo == 't') {
    ?>
              <?= $laudo[0]->medico_carimbo ?>
                        
    <?
}
?>



<div>
  
