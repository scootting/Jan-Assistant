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

