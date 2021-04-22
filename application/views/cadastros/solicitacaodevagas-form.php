<div class="panel-body">
    <div class="alert alert-primary"><b>Solicitação de Vagas</b></div>

    <form action="<?=base_url()?>cadastros/pacientes/gravarsolicitacaodevagas" method="POST">
    <div class="row">

    <div class="col-lg-3">
        <label for="">Convenio</label>
        <input type="hidden" name="solicitacao_vaga_id" value="<?=$solicitacao_vaga_id?>">
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


    <div class="col-lg-3">
            <label for="">Disciplina</label>
            <select name="disciplina" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'disciplina'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->disciplina == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

          <div class="col-lg-2">
            <label for="">Nível de Formação</label>
            <select name="formacao" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'escolaridade'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->formacao == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Curso</label>
            <select name="curso" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'curso'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->curso == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-2">
            <label for="">Tipo de Estagio</label>
            <input type="text" name="tipovaga" id="tipovaga" class="form-control" value="<?=@$obj[0]->tipo_vaga?>">
        </div>

        <div class="col-lg-2">
            <label for="">Setor</label>
            <select name="setor" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'setor'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->setor == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-2">
            <label for="">Tipo da Vaga</label>
            <select name="tipodavaga" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'tipovaga'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->tipodavaga == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Aluno</label>
                <select name="aluno_id" class="form-control" required>
                    <option value=""> Selecione </option>
                    <?foreach($alunos as $item){?>
                        <option value="<?=$item->paciente_id?>" <?=(@$obj[0]->aluno_id == $item->paciente_id)? 'selected' : ''?> > <?=$item->nome?> </option>
                    <?}?>
                </select>
        </div>

        <div class="col-lg-2">
            <label for="">Data Inicio: </label>
            <input type="text" id="datainicio" class="data form-control" name="data_inicio" value="<?=date("d/m/Y", strtotime(str_replace('-', '/', @$obj[0]->data_inicio)));?>" required/>
        </div>

        <div class="col-lg-2">
            <label for="">Data Fim: </label>
            <input type="text" id="datafinal" class="data form-control" name="data_final" value="<?=date("d/m/Y", strtotime(str_replace('-', '/', @$obj[0]->data_final)));?>" required/>
        </div>

        </div>

        <br>
        <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>
        <button class="btn btn-outline-default btn-round btn-sm" type="reset">Limpar</button>

        </form>
</div>
