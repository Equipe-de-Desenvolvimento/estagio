<div class="panel-body"> <!-- Inicio da DIV content -->
    <fieldset>
        <div class="alert alert-primary"><b>Associar Instituição ao Convenio</b></div>
        
            <form name="form_convenio" id="form_convenio" action="<?= base_url() ?>cadastros/convenio/gravarconvenioempresa" method="post">
                <div class="row">
                    <div class="col-lg-3">
                            <label>Convênio</label>
                            <input type="text" class="form-control" name="convenio_selecionado" value="<?= $convenio_selecionado[0]->nome; ?>" readonly="" />
                    </div>

                    <div class="col-lg-3">
                        <label>Instituição</label>
                        <select name="instituicao_id" id="instituicao" class="form-control">
                            <!--<option value="">TODOS</option>-->
                            <? foreach ($instituicao as $value) : ?>
                                <option value="<?= $value->instituicao_id; ?>"><?php echo $value->nome_fantasia; ?></option>
                            <? endforeach; ?>
                        </select>
                        <input type="hidden" name="convenio_id" value="<?= $convenioid; ?>" />
                    </div>
                </div>
                                <br>
                <button class="btn btn-outline-default btn-round btn-sm" type="submit" name="btnEnviar">Enviar</button>
                <button class="btn btn-outline-danger btn-round btn-sm" type="reset" name="btnLimpar">Limpar</button>
            </form>
    </fieldset>
    <br>
    <fieldset>
        <div style="display: block; width: 100%;">
            <? if (count($empresa_conta) > 0) { ?>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="tabela_header">Empresa</th>
                            <th class="tabela_header"><center>Deletar</center></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $estilo_linha = "tabela_content01";
                        foreach ($empresa_conta as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>

                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->empresa ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><center><a class="btn btn-outline-danger btn-round btn-sm" href="<?= base_url() ?>cadastros/convenio/excluirconvenioempresa/<?= $item->convenio_instituicao_id ?>/<?= $convenioid ?>">delete</a></center></td>
                            </tr>
                    <? } ?>
                    </tbody>
                </table>
            <? } ?> 
        </div>
    </fieldset>

</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function () {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function () {
        $("#accordion").accordion();
    });


    $(document).ready(function () {
        jQuery('#form_sala').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>