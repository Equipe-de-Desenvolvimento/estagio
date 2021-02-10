<div class="panel-body">
    <div class="alert alert-primary"><b>Solicitação de Vagas</b></div>

    <form action="<?=base_url()?>cadastros/pacientes/gravarsolicitacaodevagas" method="POST">
    <div class="row">
        <div class="col-lg-3">
            <label for="vaganome">Vaga</label>
            <input type="hidden" name="solicitacao_vaga_id" value="<?=$solicitacao_vaga_id?>">
            <input type="text" name="vaganome" id="vaganome" class="form-control" value="<?=@$obj[0]->nome_vaga?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>

        <div class="col-lg-2">
            <label for="tipovaga">Tipo</label>
            <input type="text" name="tipovaga" id="tipovaga" class="form-control" value="<?=@$obj[0]->tipo_vaga?>" required>
            <div class="invalid-feedback">
                Preechar o seguinte campo!
            </div>
        </div>


        <div class="col-lg-3">
            <label for="">Convenio</label>
            <select name="convenio_id" id="convenio_id" class="form-control" required> 
                <option value="">Selecione</option>
                <?foreach($convenios as $item){?>
                    <option value="<?=$item->convenio_id?>" <?=(@$obj[0]->convenio_id == $item->convenio_id)? 'selected': ''?>><?=$item->nome?></option>
                <?}?>
            </select>
            <div class="invalid-feedback">
                    Selecione uma opção da lista!
            </div>
        </div>


        <div class="col-lg-2">
            <label for="">Qtde de Vagas</label>
            <input type="number" name="qtdvagas" class="form-control" value="<?=@$obj[0]->qtde_vagas?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>

        </div>

        <br>
        <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>
        <button class="btn btn-outline-default btn-round btn-sm" type="reset">Limpar</button>

        </form>
</div>
