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
--16/03/2021
ALTER TABLE ponto.tb_paciente ADD COLUMN seguradora text;
ALTER TABLE ponto.tb_paciente ADD COLUMN num_apolice text;
ALTER TABLE ponto.tb_paciente ADD COLUMN vigencia_apolice text;
ALTER TABLE ponto.tb_paciente ADD COLUMN matricula text;



--24/07/2021

CREATE TABLE ponto.tb_representante_unidade
(
  representante_unidade_id serial NOT NULL,
  nome varchar(100),
  email varchar(100),
  cargo varchar(100),
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_representante_unidade_pkey PRIMARY KEY (representante_unidade_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_representante_unidade
  OWNER TO postgres;

ALTER TABLE ponto.tb_representante_unidade ADD COLUMN instituicao_id integer;
ALTER TABLE ponto.tb_representante_unidade ADD COLUMN informacaovaga_id integer;

ALTER TABLE ponto.tb_vagas_empresas ADD COLUMN representante_unidade_id integer;
