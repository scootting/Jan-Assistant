/*
 *** - tipo para las funciones de las personas - ***
*/
CREATE TYPE public.tt_persona AS (
  id INTEGER,
  personal VARCHAR,
  paterno VARCHAR,
  materno VARCHAR,
  nombres VARCHAR,
  sexo CHAR(1),
  nacimiento DATE
);

ALTER TYPE public.tt_persona
  OWNER TO postgres;


CREATE TYPE act.tt_desc_encargado AS (
  nro_doc VARCHAR(10),
  fecha_doc DATE,
  responsable VARCHAR(55),
  ofc_des VARCHAR(80)
);

ALTER TYPE act.tt_desc_encargado
  OWNER TO postgres;


/*
 *** - Tipo para las funciones de Partidas - ***
*/ 

CREATE TYPE act.tt_partida AS (
  par_cod CHAR(5),
  par_des CHAR(50)
);

ALTER TYPE act.tt_partida
  OWNER TO postgres;

  ----fin