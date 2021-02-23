<div class="panel-body">
    <div class="alert alert-primary"><b>Cadastro de Vagas</b></div>

    <form action="<?=base_url()?>cadastros/pacientes/gravarcadastrovagas" method="POST">
    <div class="row">
        <div class="col-lg-3">
            <label for="vaganome">Vaga</label>
            <input type="hidden" name="vaga_id" value="<?=$vagas_id?>">
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
            <label for="">Nível de Formação</label>
            <select name="formacao" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'escolaridade'){?>
                        <option value="<?=$item->informacaovaga_id?> <?=(@$obj[0]->escolaridade == $item->informacaovaga_id)?'selected':''?>"><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-2">
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
            <label for="">Periodo</label>
            <select name="periodo" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'periodo'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->periodo == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-2">
            <label for="">Periodicidade</label>
            <select name="periodicidade" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'periodicidade'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->periodicidade == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
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

        <div class="col-lg-2">
            <label for="">Qtde de Vagas</label>
            <input type="number" name="qtdvagas" class="form-control" value="<?=@$obj[0]->qtde_vagas?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>

        <? if($vagas_id > 0){?>
        <div class="col-lg-2">
            <label>Qtde Inicial</label>
            <input type="number" name="qtdinicial" class="form-control" value="<?=@$obj[0]->qtde_inicial?>" readonly>
        </div>
            <?}?>

        </div>

        <br>
        <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>
        <button class="btn btn-outline-default btn-round btn-sm" type="reset">Limpar</button>

        </form>
</div>

<!-- <script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script> -->
