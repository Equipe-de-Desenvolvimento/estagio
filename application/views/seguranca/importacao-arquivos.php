<?
$convenios = $this->convenio->listarconvenionaodinheiro();
?>
<div class="content  "> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Carregar Arquivo</a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'seguranca/operador/importararquivooperador'); ?>
            <form method="POST" action="<?=base_url() . 'seguranca/operador/importararquivooperador'?>" enctype="multipart/form-data">
            <table>                
                <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
                <input type="file" multiple="" name="arquivos[]"/>
                <select name="tipoarquivo" required="">
                    <option value="">Selecione</option>
                    <?php
                    foreach ($documentos as $item) {
                        ?>
                        <option value="<?= $item->documentacao_profissional_id; ?>"><?= $item->nome; ?></option>
                        <?
                    }
                    ?>
                </select> <br><br>
                <button type="submit" name="btnEnviar">Enviar</button>
                <input type="hidden" name="operador_id" value="<?= $operador_id; ?>" />
            </table>
            </form>
            <?// form_close(); ?>  
        </div>

        <h3><a href="#">Vizualizar Arquivos </a></h3>
        <div>
            <table>
                <tr>
                    <?
                    $perfil_id = $this->session->userdata('perfil_id'); 
                    foreach ($documentos as $item) {
                        ?> 
                <tr>
                    <td style="color:white;background-color: blue;" ><?=  $item->nome; ?></td>
                </tr> 
                    <tr>                       
                        <?
                        $arquivo_pasta = directory_map("./upload/arquivosoperador/$operador_id/$item->documentacao_profissional_id");
                        if ($arquivo_pasta != false) {
                            sort($arquivo_pasta);
                        }
                        $i = 0;
                        if ($arquivo_pasta != false) {
                            foreach ($arquivo_pasta as $value) {
                                $i++;
                                ?> 
                                <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/arquivosoperador/" . $operador_id . "/" . $item->documentacao_profissional_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? echo substr($value, 0, 10) ?><br>
                                    <?php if($perfil_id != 18 && $perfil_id != 20){?>
                                    <a href="<?= base_url() ?>seguranca/operador/excluirimagem/<?= @$operador_id ?>/<?= $item->documentacao_profissional_id; ?>/<?= $value ?>">Excluir</a>
                                    <?php }?>
                                </td>
                                <?
                                if ($i == 8) {
                                    ?>
                                <!--</tr><tr>-->
                                    <?
                                }
                            }
                        }
                        ?>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div> <!-- Final da DIV content -->
 
    </div> <!-- Final da DIV content -->
    
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });



</script>
