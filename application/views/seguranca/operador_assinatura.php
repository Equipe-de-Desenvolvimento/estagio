<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3><a href="#">Carregar Assinatura </a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'seguranca/operador/importarimagem'); ?>
            <form method="POST" action="<?=base_url() . 'seguranca/operador/importarimagem'?>"  enctype="multipart/form-data">
            <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
            <input type="file" name="userfile"/>
            <button type="submit" name="btnEnviar">Enviar</button>
            <input type="hidden" name="operador_id" value="<?= $operador_id; ?>" />
            </form>
            <?// form_close(); ?>

        </div>

        <h3><a href="#">Vizualizar imagens </a></h3>
        <div >
            <table>
                <tr>
                    <?
                    $perfil_id = $this->session->userdata('perfil_id'); 
                    $i = 0;
                    if ($arquivo_pasta != false):
                        if (in_array($operador_id . ".jpg", $arquivo_pasta) || in_array($operador_id . ".jpeg", $arquivo_pasta) || in_array($operador_id . ".png", $arquivo_pasta)){
                        ?>

                            <td width="10px">
                                <img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/1ASSINATURAS/$operador_id" ?>.jpg', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/1ASSINATURAS/$operador_id" ?>.jpg">
                                <br><? echo "$operador_id.jpg" ?>
                                <br/>
                                <?php if($perfil_id != 18 && $perfil_id != 20){?>
                                <a onclick="javascript: return confirm('Deseja realmente excluir o arquivo <?= $operador_id; ?>.jpg');" href="<?= base_url() ?>seguranca/operador/ecluirimagem/<?= $operador_id ?>">Excluir</a>
                                <?php }?>
                            </td>
                            <?
                        }
                    endif
                    ?>
            </table>
        </div> 

        <h3><a href="#">Carregar Certificado Digital </a></h3>
        <div >
            <?// form_open_multipart(base_url() . 'seguranca/operador/importarimagem'); ?>
            <form method="POST" action="<?=base_url() . 'seguranca/operador/importarcertificadomedico'?>"  enctype="multipart/form-data">
            <label>Informe o Certificado para importa&ccedil;&atilde;o</label><br>
            
            <input type="file" name="userfile"/> 
            <br>
            Senha Certificado: <input type="password" name="senha" id="senha"/>

            <button type="submit" name="btnEnviar">Enviar</button>
            <input type="hidden" name="operador_id" value="<?= $operador_id; ?>" />
            </form>
            <?// form_close(); ?>

        </div>

        <h3><a href="#">Vizualizar Arquivo Certificado </a></h3>
        <div >
            <table>
                <tr>
                    <?
                    $perfil_id = $this->session->userdata('perfil_id'); 
                    $i = 0;
                    if ($arquivo_pasta_certificado != false):
                        if (in_array($operador_id . ".crt", $arquivo_pasta_certificado)){
                        ?>

                            <td width="10px">
                                <img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/certificadomedico/$operador_id" ?>.crt', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "upload/certificadomedico/$operador_id" ?>.crt">
                                <br><? echo "$operador_id.crt" ?>
                                <br/>
                                <?php if($perfil_id != 18 && $perfil_id != 20){?>
                                <a onclick="javascript: return confirm('Deseja realmente excluir o Certificado ?');" href="<?= base_url() ?>seguranca/operador/ecluirimagemcertificado/<?= $operador_id ?>">Excluir</a>
                                <?php }?>
                            </td>
                            <?
                        }
                    endif
                    ?>
            </table>
        </div>



    </div> <!-- Final da DIV content -->
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });



</script>
