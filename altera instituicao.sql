ALTER TABLE ponto.tb_instituicao DROP COLUMN email_convenio;
ALTER TABLE ponto.tb_instituicao ADD COLUMN email_convenio text;
ALTER TABLE ponto.tb_instituicao ADD COLUMN credor_devedor_id integer;


ALTER TABLE ponto.tb_instituicao ADD COLUMN juridico text;
ALTER TABLE ponto.tb_instituicao ADD COLUMN email_juridico text;
ALTER TABLE ponto.tb_instituicao ADD COLUMN telefone_juridico text;
ALTER TABLE ponto.tb_instituicao ADD COLUMN observacao_juridico text;

ALTER TABLE ponto.tb_paciente ADD COLUMN paciente_operador_id integer;

--15/03/2021

ALTER TABLE ponto.tb_instituicao ADD COLUMN numero character varying(50);

ALTER TABLE ponto.tb_instituicao ADD COLUMN bairro character varying(200);

ALTER TABLE ponto.tb_convenio ADD COLUMN instituicao_id integer;
