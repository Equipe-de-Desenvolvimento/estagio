<div class="panel-body">
    <form action="<?=base_url()?>cadastros/pacientes/gravarinformacaovagas" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <label for="">Descrição</label>
                <input type="text" name="descricao" class="form-control" value="<?=@$obj[0]->descricao?>" required>
                <input type="hidden" name="informacaovaga_id" value="<?=$informacaovaga_id?>">
            </div>

            <div class="col-lg-3">
                <label for=""> Tipo </label>
                <select name="tipo" class="form-control" required>
                    <option value="">Selecione</option>
                    <option value="escolaridade" <?=(@$obj[0]->tipo == 'escolaridade')?'selected': ''?>>Nível de Formação</option>
                    <option value="curso" <?=(@$obj[0]->tipo == 'curso')?'selected': ''?>>Curso</option>
                    <option value="disciplina" <?=(@$obj[0]->tipo == 'disciplina')?'selected': ''?>> Disciplina</option>
                    <option value="setor" <?=(@$obj[0]->tipo == 'setor')?'selected': ''?> >Setor</option>
                    <option value="periodo" <?=(@$obj[0]->tipo == 'periodo')?'selected': ''?>>Periodo</option>
                    <option value="periodicidade" <?=(@$obj[0]->tipo == 'periodicidade')?'selected': ''?>>Periodicidade</option>
                    <option value="tipovaga" <?=(@$obj[0]->tipo == 'tipovaga')?'selected': ''?>>Tipo Vaga</option>
                </select>
            </div>
        </div>
        <br>
        
        <button type="submit" class="btn btn-outline-default btn-sm">Enviar</button>
        <button type="reset" class="btn btn-outline-default btn-sm">Limpar</button>
    </form>
</div>