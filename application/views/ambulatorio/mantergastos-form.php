<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Gastos</a></h3>
        <div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>ambulatorio/exame/gravargastosoperador" enctype="multipart/form-data" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Nome do Gasto</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="manter_gasto_id" class="texto1"  value="<?=@$obj[0]->manter_gasto_id?>"/>
                        <input type="text" name="nome" class="texto10" value="<?=@$obj[0]->nome?>" required=''/>
                    </dd>
                    <? if(isset($obj[0]->data_gasto)){
                        $data = date("d/m/Y", strtotime(str_replace('-', '/', $obj[0]->data_gasto)));
                        }
                        else{
                        $data = '';
                        }?>
                    <dt>
                        <label>Data do Gasto</label>
                    </dt>
                    <dd>
                        <input type="text" name="data_gasto" id="data_gasto" class="texto3" value="<?=$data?>" required/>
                    </dd>
                    <dt>
                        <label>Preço</label>
                    </dt>
                    <dd>
                        <input type="text" name="preco_gasto" id="preco_gasto" alt="decimal" class="texto3"  value="<?=@$obj[0]->preco?>" required=''/>
                    </dd>
                    <dt>
                        <label>Horario Inicio / Final</label>
                    </dt>
                    <dd>
                        <input type="text" name="horario_inicial" id="horario_inicial" class="texto3" value="<?=@$obj[0]->horario_inicial?>" required/>
                        a
                        <input type="text" name="horario_final" id="horario_final" value="<?=@$obj[0]->horario_final?>" class="texto3" <?if($status == 'FINALIZAR') echo 'required';?>/>
                    </dd>
                    <dt>
                        <label>Observação</label>
                    </dt>
                    <dd>
                        <textarea name="observacao"><?=@$obj[0]->observacao?> </textarea>
                    </dd>

                    <br><br><br>

                    <dt>
                    <label for='selecao-arquivo'>Anexo:</label>
                    </dt>
                    <dd>
                    <input type="file" multiple="" name="arquivos[]"/>
                    </dd>

                    <input type="hidden" name="status" id="status" class="texto3" value="<?=$status?>"/>
                <br>

                </dl>    
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <!--<button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>-->
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript">

$(function () {
        $("#accordion").accordion();
    });

    $(function () {
        $("#data_gasto").datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy',
            maxDate: 'date'
        });

        $("#horario_inicial").mask("99:99");
        $("#horario_final").mask("99:99");
    });








</script>