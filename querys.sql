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

/*
 *** - funcion para mostrar la Partida - ***
*/
CREATE OR REPLACE FUNCTION act.ff_obtener_partida (
  p_gestion integer
)
RETURNS SETOF act.tt_partida AS
$body$
DECLARE
  Datos RECORD;
BEGIN
   FOR Datos IN select pa.par_cod,
   
   					   pa.par_des
                       
   from act.partida pa
   		where  pa.gestion = p_gestion 
         LOOP
        RETURN NEXT Datos;
    END LOOP;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_obtener_partida (p_gestion integer)
  OWNER TO postgres;

/*
 *** - funcion para guardar activo desde editar - ***
*/
  CREATE OR REPLACE FUNCTION act.ff_guardar_activo (
  p_cantidad varchar,
  p_descripcion text,
  p_des_det text,
  p_uni_med varchar,
  p_id_partida varchar,
  p_id_contable varchar,
  p_vida_util varchar,
  p_pre_uni varchar,
  p_nro_fac varchar,
  p_id varchar
)
RETURNS varchar AS
$body$
DECLARE
  Datos RECORD;
BEGIN
    IF 
       p_cantidad = '' OR
       p_descripcion = '' OR
       p_des_det = '' OR
       p_uni_med = '' OR
       p_id_partida = '' OR
       p_id_contable = '' OR
       p_vida_util = '' OR
       p_pre_uni = '' OR
       p_nro_fac = '' OR
       p_id = '' THEN
       RAISE EXCEPTION 'Los campos obligatorios no pueden ser nulo.';
    END IF;
    UPDATE act.asignaciones_detalles
       SET cantidad = p_cantidad::integer,
           descripcion = p_descripcion,
           des_det = p_des_det,
           uni_med = p_uni_med,
           id_partida = p_id_partida,
           id_contable = p_id_contable,
           vida_util = p_vida_util::integer,
           pre_uni = p_pre_uni::numeric,
           nro_fac = p_nro_fac
     WHERE id = p_id::integer;                          
    RETURN 'Msg: Los datos de [' || p_id || '] fueron actualizados';  
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION act.ff_guardar_activo (p_cantidad varchar, p_descripcion text, p_des_det text, p_uni_med varchar, p_id_partida varchar, p_id_contable varchar, p_vida_util varchar, p_pre_uni varchar, p_nro_fac varchar, p_id varchar)
  OWNER TO postgres;


CREATE OR REPLACE FUNCTION act.ff_desc_encargado (
  p_gestion integer
)
RETURNS SETOF act.t_desc_encargado AS
$body$
DECLARE
    Datos RECORD;
BEGIN
    ---lista de asignaciones
    FOR Datos IN select 	
    				a.nro_doc::varchar(10) ,
                    a.fecha::date , 
                    a.responsable::varchar (55) , 
                    of.ofc_des::varchar (80)
			 	from act.asignaciones a , act.oficina of
			 	where a.id_ofc = of.id_oficina and a.tip_doc = 1
                and a.gestion = p_gestion
                LOOP
        	RETURN NEXT Datos;
      	END LOOP;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
RETURNS NULL ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_desc_encargado (p_gestion integer)
  OWNER TO postgres;


-----------------fin

