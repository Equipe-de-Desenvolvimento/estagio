<div class="panel-body">
    <div class="alert alert-primary"><b>Cadastro de Vagas</b></div>

    <div class="row">
    <input type="hidden" name="vaga_id" value="<?=$vagas_id?>">

        <div class="col-lg-3">
            <label for="">Convenio</label>
            <select name="convenio_id" id="convenio_id" class="form-control" disabled> 
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
            <label for="">Instituição de Origem</label>
            <select name="instituicao_id" id="instituicao_id" class="form-control" disabled>
                  <option value="">Selecione</option>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Disciplina</label>
            <select name="disciplina" id="" class="form-control" disabled>
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
            <select name="formacao" id="" class="form-control" disabled>
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
            <select name="curso" id="" class="form-control" disabled>
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
            <input type="text" name="tipovaga" id="tipovaga" class="form-control" disabled value="<?=@$obj[0]->tipo_vaga?>">
        </div>

        <div class="col-lg-3">
            <label for="">Responsável na Origem</label>
            <select name="resporigem" id="" class="form-control" disabled>
                  <option value="">Selecione</option>
                  <?foreach($resporigem as $item){?>
                        <option value="<?=$item->responsavel_origem_id?>" <?=(@$obj[0]->responsavel_origem == $item->responsavel_origem_id)?'selected':''?>><?=$item->nome?></option>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Responsável na IJF</label>
            <select name="respifj" id="" class="form-control" disabled>
                  <option value="">Selecione</option>
                  <?foreach($respijf as $item){?>
                        <option value="<?=$item->responsavel_ifj_id?>" <?=(@$obj[0]->responsavel_ijf == $item->responsavel_ifj_id)?'selected':''?>><?=$item->nome?></option>
                  <?}?>
            </select>
        </div>
    </div>

    <br>
      <div class="row"> 
          <div class="col-lg-12">
              <label for="">Objetivos do Estagio e Plano de Atividades</label>
              <textarea class="tinymce" name="objetivo" disabled> <?=@$obj[0]->objetivo ?></textarea>
          </div>
      </div>

      <br>
      <div class="row"> 
          <div class="col-lg-12">
              <label for="">Distribuição da Carga Horaria</label>
                <div id="calendar"></div>
          </div>
      </div>
      <br>

      <div class="row">
            <div class="col-lg-3">
                <label for="">Valor Por Aluno</label>
                <input type="text" readonly class="form-control" id="valorconvenio">
            </div>

        <div class="col-lg-2">
            <label for="">Setor</label>
            <select name="setor" id="" class="form-control" disabled>
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
            <select name="tipodavaga" id="" class="form-control" disabled>
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
            <input type="number" name="qtdvagas" class="form-control" value="<?=@$obj[0]->qtde_vagas?>" disabled>
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
</div>

<script>
    $(function () {
        if($('#convenio_id').val != ''){
            $.getJSON('<?= base_url() ?>autocomplete/listarinstituicaoconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                      options = '<option value=""></option>';

                      instituicao_id = '<?=@$obj[0]->instituicao_id?>';
                      for (var c = 0; c < j.length; c++) {
                          if(instituicao_id == j[c].instituicao_id){
                            options += '<option value="' + j[c].instituicao_id + '" selected>' + j[c].nome +'</option>';
                          }else{
                            options += '<option value="' + j[c].instituicao_id + '">' + j[c].nome +'</option>';
                          }
                          
                      }
                      $('#instituicao_id option').remove();
                      $('#instituicao_id').append(options);
                      $('.carregando').hide();
                  });
        }else{
            $('#instituicao_id').html('<option value="">Selecione um Convenio</option>');
        }


        if($('#convenio_id').val != ''){
            $.getJSON('<?= base_url() ?>autocomplete/valorestagioporconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                    $('#valorconvenio').val(j[0].valor_por_estagio);
                });
        }else{
            $('#valorconvenio').val(''); 
        }
    });

      $(function () {
          $('#convenio_id').change(function () {
              if ($(this).val()) {
                  $('.carregando').show();                                                  
                  $.getJSON('<?= base_url() ?>autocomplete/listarinstituicaoconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                      options = '<option value=""></option>';

                      for (var c = 0; c < j.length; c++) {
                          options += '<option value="' + j[c].instituicao_id + '">' + j[c].nome +'</option>';
                      }
                      $('#instituicao_id option').remove();
                      $('#instituicao_id').append(options);
                      $('.carregando').hide();
                  });

              } else {
                  $('#instituicao_id').html('<option value="">Selecione um Convenio</option>');
              }

              if ($(this).val()) {
                $.getJSON('<?= base_url() ?>autocomplete/valorestagioporconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                    $('#valorconvenio').val(j[0].valor_por_estagio);
                });
              }else{
                $('#valorconvenio').val('');
              }
          });
      });


tinymce.init({
  selector: 'textarea.tinymce',
  // width: 800,
  readonly : 1,
  height: 350,
  plugins: [
      "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
      "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
  ],
  toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough  | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
  menubar: false,
  language: 'pt_BR',
  forced_root_block : '',
  browser_spellcheck: true,
  contextmenu: false,

  setup: function (editor) {
      editor.on('init', function()
      {
      this.getDoc().body.style.fontSize = '12pt';
      this.getDoc().body.style.fontFamily = 'Arial';
      });
      editor.on('SetContent', function (e) {
      this.getDoc().body.style.fontSize = '12pt';
      this.getDoc().body.style.fontFamily = 'Arial';
      });
},
toolbar_items_size: 'small',
      style_formats: [{
      title: 'Bold text',
              inline: 'b'
      }, {
      title: 'Red text',
              inline: 'span',
              styles: {
              color: '#ff0000'
              }
      }, {
      title: 'Red header',
              block: 'h1',
              styles: {
              color: '#ff0000'
              }
      }, {
      title: 'Example 1',
              inline: 'span',
              classes: 'example1'
      }, {
      title: 'Example 2',
              inline: 'span',
              classes: 'example2'
      }, {
      title: 'Table styles'
      }, {
      title: 'Table row 1',
              selector: 'tr',
              classes: 'tablerow1'
      }],
      fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
      init_instance_callback: function () {
      window.setTimeout(function () {
      $("#div").show();
      }, 1000);
      }
});


document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'pt-br',
    selectable: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    dateClick: function(info) {
      alert('clicked ' + info.dateStr);
    },
    select: function(info) {
      alert('selected ' + info.startStr + ' to ' + info.endStr);
    }
  });

  calendar.render();
});


</script>
