-- FACILITANDO A EXCLUSÃO E CRIAÇÃO DE AGENDAS
-- DOIS IDS ADICIONADOS. UM PARA CASO O USUÁRIO EXCLUA A AGENDA INTEIRA E OUTRO PARA CASO ELE EXCLUA APENAS O HORÁRIO NA TABELA.

-- 19/05/2017

ALTER TABLE ponto.tb_agenda_exames ADD COLUMN horario_id integer;


-- 06/07/2017

CREATE TABLE ponto.tb_empresa_impressao
(
  empresa_impressao_id serial NOT NULL,

  cabecalho text,
  rodape text,
  paciente boolean DEFAULT false,
  procedimento boolean DEFAULT false,
  convenio boolean DEFAULT false,
  ativo boolean DEFAULT true,
  empresa_id integer,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_empresa_impressao_pkey PRIMARY KEY (empresa_impressao_id)
);

ALTER TABLE ponto.tb_ambulatorio_laudo ADD COLUMN alergias character varying(40000);
ALTER TABLE ponto.tb_ambulatorio_laudo ADD COLUMN cirurgias character varying(40000);


ALTER TABLE ponto.tb_paciente ADD COLUMN alergias character varying(40000);
ALTER TABLE ponto.tb_paciente ADD COLUMN cirurgias character varying(40000);
ALTER TABLE ponto.tb_paciente ADD COLUMN observacoes character varying(40000);
ALTER TABLE ponto.tb_empresa ADD COLUMN listadeespera boolean DEFAULT false;

-- Dia 02/10/2017
ALTER TABLE ponto.tb_agenda_exames ADD COLUMN indicacao integer;

ALTER TABLE ponto.tb_agenda_exames ADD COLUMN valor_medico numeric(10,2);
ALTER TABLE ponto.tb_agenda_exames ADD COLUMN percentual_medico boolean;


-- Dia 03/10/2017

ALTER TABLE ponto.tb_agenda_exames ADD COLUMN valor_promotor numeric(10,2);
ALTER TABLE ponto.tb_agenda_exames ADD COLUMN percentual_promotor boolean DEFAULT false;


ALTER TABLE ponto.tb_procedimento_tuss ADD COLUMN valor_promotor numeric(10,2);
ALTER TABLE ponto.tb_procedimento_tuss ADD COLUMN percentual_promotor boolean DEFAULT false;

-- Dia 22/06/2017

CREATE TABLE ponto.tb_procedimento_percentual_promotor
(
  procedimento_percentual_promotor_id serial,
  procedimento_tuss_id integer,
  promotor integer,
  valor numeric(10,2),
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_procedimento_percentual_promotor_pkey PRIMARY KEY (procedimento_percentual_promotor_id )
);


CREATE TABLE ponto.tb_procedimento_percentual_promotor_convenio
(
  procedimento_percentual_promotor_convenio_id serial,
  procedimento_percentual_promotor_id integer,
  promotor integer,
  valor numeric(10,2),
  percentual boolean DEFAULT true,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_procedimento_percentual_promotor_convenio_pkey PRIMARY KEY (procedimento_percentual_promotor_convenio_id )
);


CREATE TABLE ponto.tb_sigla
(
  nome text,
  sigla_id serial NOT NULL,
  operador_cadastro integer,
  data_cadastro timestamp without time zone,
  operador_atualizacao integer,
  data_atualizacao timestamp without time zone,
  ativo boolean DEFAULT true,
  CONSTRAINT tb_sigla_pkey PRIMARY KEY (sigla_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ponto.tb_sigla
  OWNER TO postgres;


ALTER TABLE ponto.tb_operador ADD COLUMN sigla_id integer;

ALTER TABLE ponto.tb_empresa ADD COLUMN client_id text;
ALTER TABLE ponto.tb_empresa ADD COLUMN client_secret text;
ALTER TABLE ponto.tb_empresa ADD COLUMN client_sandbox boolean;
ALTER TABLE ponto.tb_empresa ALTER COLUMN client_sandbox SET DEFAULT true;
ALTER TABLE ponto.tb_empresa ADD COLUMN valor_consulta_app numeric(10,2);

ALTER TABLE ponto.tb_agenda_exames ADD COLUMN gerencianet_id integer;
ALTER TABLE ponto.tb_agenda_exames ADD COLUMN gerencianet_link text;

ALTER TABLE ponto.tb_empresa ADD COLUMN guia_procedimento boolean;
ALTER TABLE ponto.tb_empresa ALTER COLUMN guia_procedimento SET DEFAULT true;

-- 13/04/2020
ALTER TABLE ponto.tb_paciente ADD COLUMN senha_app varchar(300);
ALTER TABLE ponto.tb_paciente ADD COLUMN usuario_app varchar(100);
ALTER TABLE ponto.tb_operador ADD COLUMN grupo_agenda text;

CREATE TABLE ponto.tb_posts_blog
(
  posts_blog_id serial NOT NULL,
  titulo character varying(200),
  breve_descricao text,
  thumbnail text,
  corpo_html text,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_posts_blog_pkey PRIMARY KEY (posts_blog_id)
);

CREATE TABLE ponto.tb_registro_dispositivo
(
  registro_dispositivo_id serial NOT NULL,
  hash text,
  medico_id integer,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  ativo boolean DEFAULT true,
  confirmado boolean DEFAULT true,
  CONSTRAINT tb_registro_dispositivo_pkey PRIMARY KEY (registro_dispositivo_id)
);

ALTER TABLE ponto.tb_registro_dispositivo ADD COLUMN paciente_id integer;

CREATE TABLE ponto.tb_paciente_risco_cirurgico
(
  paciente_risco_cirurgico_id serial NOT NULL,
  paciente_id integer,
  questionario text,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_paciente_risco_cirurgico_pkey PRIMARY KEY (paciente_risco_cirurgico_id)
);

CREATE TABLE ponto.tb_paciente_pesquisa_satisfacao
(
  paciente_pesquisa_satisfacao_id serial NOT NULL,
  paciente_id integer,
  questionario text,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_paciente_pesquisa_satisfacao_pkey PRIMARY KEY (paciente_pesquisa_satisfacao_id)
);

ALTER TABLE ponto.tb_operador ADD COLUMN link_reuniao text;

CREATE TABLE ponto.tb_paciente_solicitar_agendamento
(
  paciente_solicitar_agendamento_id serial,
  paciente_id integer,
  data text,
  hora text,
  convenio_id integer,
  procedimento_convenio_id integer,
  ativo boolean DEFAULT true,
  data_cadastro timestamp without time zone,
  operador_cadastro integer,
  data_atualizacao timestamp without time zone,
  operador_atualizacao integer,
  CONSTRAINT tb_paciente_solicitar_agendamento_pkey PRIMARY KEY (paciente_solicitar_agendamento_id)
);

ALTER TABLE ponto.tb_paciente_solicitar_agendamento ADD COLUMN confirmado boolean DEFAULT false;


ALTER TABLE ponto.tb_paciente_solicitar_agendamento ADD COLUMN procedimento_text text;
ALTER TABLE ponto.tb_paciente_solicitar_agendamento ADD COLUMN convenio_text text;

ALTER TABLE ponto.tb_ambulatorio_receituario ADD COLUMN especial boolean DEFAULT false;