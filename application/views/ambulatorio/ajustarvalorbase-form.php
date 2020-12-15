<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ambulatorio/procedimentoplano">
            Voltar
        </a>

    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Ajustar Valor Base</a></h3>
        <!--<div class="ajusteAccordion">--> 
            <form name="form_procedimentoplano" id="form_procedimentoplano" action="<?= base_url() ?>ambulatorio/procedimentoplano/gravarajsustevalorbase" method="post">

                <table class="dl_desconto_lista">
                    <input type="hidden" name="procedimento_convenio_id" value="<?= @$procedimentoescolhido[0]->procedimento_convenio_id; ?>" />
                    <tr>
                        <td>
                            <label>Convenio *</label>
                        </td>
                        <td>
                            <select name="convenio" id="convenio" class="size4" required="">
                                <option value="">Selecione</option>
                                <? foreach ($convenio as $value) : ?>
                                    <option value="<?= $value->convenio_id; ?>">
                                        <?php echo $value->nome; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Grupos *</label>
                        </td>
                        <td>
                            <select data-placeholder="Selecione um ou mais grupos" required name="grupo[]" id="grupo" multiple class="size4 chosen-select" tabindex="1" required="">
                                <option value="">TODOS</option>
                                <? foreach ($grupos as $value) : ?>
                                    <option value="<?= $value->nome; ?>">
                                        <?php echo $value->nome; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                            
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <label>Percentual</label>
                        </td>
                        <td>
                            <input type="checkbox" name="percentual" id="percentual" class="texto01"/>
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <label>Qtde CH</label>
                        </td>
                        <td>
                            <input type="number" name="qtdech" step="0.0001" id="qtdech" class="texto01" />
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <label>Valor CH</label>
                        </td>
                        <td>
                            <input type="number" name="valorch" step="0.0001" id="valorch" class="texto01" />
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <label>Qtde Filme</label>
                        </td>
                        <td>
                            <input type="number" name="qtdefilme" step="0.0001" id="qtdefilme" class="texto01"  />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Valor Filme</label>
                        </td>
                        <td>
                            <input type="number" name="valorfilme" step="0.0001" id="valorfilme" class="texto01" />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Qtde Porte</label>
                        </td>
                        <td>
                            <input type="number" name="qtdeporte" step="0.0001" id="qtdeporte" class="texto01" />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Valor Porte</label>
                        </td>
                        <td>
                            <input type="number" name="valorporte" step="0.0001" id="valorporte" class="texto01" />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Qtde UCO</label>
                        </td>
                        <td>
                            <input type="number" name="qtdeuco" step="0.0001" id="qtdeuco" class="texto01" />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Valor UCO</label>
                        </td>
                        <td>
                            <input type="number" name="valoruco" step="0.0001" id="valoruco" class="texto01" />
                        </td>

                    </tr>
                    <tr >
                        <td>
                            <label>Valor TOTAL</label>
                        </td>
                        <td>
                            <input readonly type="number" name="valortotal" step="0.0001" id="valortotal" class="texto01"/>
                        </td>

                    </tr>

                </table>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        <!--</div>-->
    </div>
</div> <!-- Final da DIV content -->

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-verificaCPF.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
    $(function () {
        $("#accordion").accordion();
        $("#form_procedimentoplano").css("height", '370pt');
    });

    $("#grupo").prop('required', false);
    
    $(function () {
        $('#qtdech').change(function () {
            if(parseInt($('#qtdech').val()) > 0){
                $("#valorch").prop('required', true);
            }else{
                $("#valorch").prop('required', false);
            }
            
        });
        $('#qtdefilme').change(function () {
            if(parseInt($('#qtdefilme').val()) > 0){
                $("#valorfilme").prop('required', true);
            }else{
                $("#valorfilme").prop('required', false);
            }
        });
        $('#qtdeuco').change(function () {
            if(parseInt($('#qtdeuco').val()) > 0){
                $("#valoruco").prop('required', true);
            }else{
                $("#valoruco").prop('required', false);
            }
        });
        $('#qtdeporte').change(function () {
            if(parseInt($('#qtdeporte').val()) > 0){
                $("#valorporte").prop('required', true);
            }else{
                $("#valorporte").prop('required', false);
            }
        });
        $('#valorch').change(function () {
            if(parseInt($('#valorch').val()) > 0){
                $("#qtdech").prop('required', true);
            }else{
                $("#qtdech").prop('required', false);
            }
        });
        $('#valorfilme').change(function () {
            if(parseInt($('#valorfilme').val()) > 0){
                $("#qtdefilme").prop('required', true);
            }else{
                $("#qtdefilme").prop('required', false);
            }
        });
        $('#valoruco').change(function () {
            if(parseInt($('#valoruco').val()) > 0){
                $("#qtdeuco").prop('required', true);
            }else{
                $("#qtdeuco").prop('required', false);
            }
        });
        $('#valorporte').change(function () {
            if(parseInt($('#valorporte').val()) > 0){
                $("#qtdeporte").prop('required', true);
            }else{
                $("#qtdeporte").prop('required', false);
            }
        });
        

    });

</script>