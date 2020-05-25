/*
 *** - funcion para la busqueda de personas por carnet de identidad - ***
*/
CREATE OR REPLACE FUNCTION public.ff_buscar_persona (
  p_personal varchar
)
RETURNS SETOF public.tt_persona AS
$body$
DECLARE
  Datos RECORD;
BEGIN
   FOR Datos IN SELECT p.id_persona::INTEGER,
                       p.nro_dip::VARCHAR, 
                       p.paterno::VARCHAR, 
                       p.materno::VARCHAR, 
   					   p.nombres::VARCHAR, 
                       p.id_sexo::CHAR, 
                       p.fec_nacimiento::DATE
   				  FROM public.personas p
        		 WHERE p.nro_dip = p_personal
                   LOOP
        RETURN NEXT Datos;
    END LOOP;
    IF NOT FOUND THEN
       RAISE EXCEPTION 'No existe la persona con el numero de identificacion.';
    END IF;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION public.ff_buscar_persona (p_personal varchar)
  OWNER TO postgres;

/*
 *** - funcion para registrar personas - ***
*/
CREATE OR REPLACE FUNCTION public.ff_registrar_persona (
  p_personal varchar,
  p_paterno varchar,
  p_materno varchar,
  p_nombres varchar,
  p_sexo char,
  p_nacimiento varchar
)
RETURNS varchar AS
$body$
DECLARE
  Datos RECORD;
BEGIN
	SELECT * FROM public.personas p 
             INTO Datos
    		WHERE p.nro_dip = p_personal;
    IF FOUND THEN
       RAISE EXCEPTION 'El usuario con el numero de idenficacion ya existe.';
    END IF;
    IF p_personal = '' OR
       p_paterno = '' OR
       p_nombres = '' OR
       p_sexo = '' OR
       p_nacimiento = '' THEN
       RAISE EXCEPTION 'Los campos obligatorios no pueden ser nulo.';
    END IF;               
    INSERT INTO public.personas(nro_dip, paterno, materno, nombres, id_sexo, fec_nacimiento)
            VALUES (p_personal, p_paterno, p_materno, p_nombres, p_sexo, p_nacimiento::DATE);       
       RETURN 'Msg: La persona : [' || p_nombres || '] fue Creado';  
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION public.ff_registrar_persona (p_personal varchar, p_paterno varchar, p_materno varchar, p_nombres varchar, p_sexo char, p_nacimiento varchar)
  OWNER TO postgres;

/*
 *** - funcion para editar personas - ***
*/

CREATE OR REPLACE FUNCTION public.ff_editar_persona (
  p_personal varchar,
  p_paterno varchar,
  p_materno varchar,
  p_nombres varchar,
  p_sexo char,
  p_nacimiento varchar
)
RETURNS varchar AS
$body$
DECLARE
  Datos RECORD;
BEGIN
    IF p_personal = '' OR
       p_paterno = '' OR
       p_nombres = '' OR
       p_sexo = '' OR
       p_nacimiento = '' THEN
       RAISE EXCEPTION 'Los campos obligatorios no pueden ser nulo.';
    END IF;
    UPDATE public.personas
       SET paterno = p_paterno,
           materno = p_materno,
           nombres = p_nombres,
           id_sexo = p_sexo,
           fec_nacimiento = p_nacimiento::DATE        
     WHERE nro_dip = p_personal;                          
    RETURN 'Msg: Los datos de [' || p_nombres || '] fueron actualizados';  
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION public.ff_editar_persona (p_personal varchar, p_paterno varchar, p_materno varchar, p_nombres varchar, p_sexo char, p_nacimiento varchar)
  OWNER TO postgres;