
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


<<<<<<< HEAD
/*
 *** - funcion para la lista de biblioteca- ***
*/
CREATE OR REPLACE FUNCTION act.ff_desc_biblioteca (
  p_gestion integer
)
RETURNS SETOF act.t_desc_biblioteca AS
=======
CREATE OR REPLACE FUNCTION act.ff_desc_encargado (
  p_gestion integer
)
RETURNS SETOF act.t_desc_encargado AS
>>>>>>> 9221dfb7dd103406b6badcebf7c026398116a2c8
$body$
DECLARE
    Datos RECORD;
BEGIN
<<<<<<< HEAD
    
=======
    ---lista de asignaciones
>>>>>>> 9221dfb7dd103406b6badcebf7c026398116a2c8
    FOR Datos IN select 	
    				a.nro_doc::varchar(10) ,
                    a.fecha::date , 
                    a.responsable::varchar (55) , 
                    of.ofc_des::varchar (80)
			 	from act.asignaciones a , act.oficina of
<<<<<<< HEAD
			 	where a.id_ofc = of.id_oficina and a.tip_doc = 6
=======
			 	where a.id_ofc = of.id_oficina and a.tip_doc = 1
>>>>>>> 9221dfb7dd103406b6badcebf7c026398116a2c8
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
<<<<<<< HEAD
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_desc_biblioteca (p_gestion integer)
  OWNER TO postgres;

/*
 *** - funcion para la lista de carpinteria- ***
*/
CREATE OR REPLACE FUNCTION act.ff_desc_carpinteria (
  p_gestion integer
)
RETURNS SETOF act.t_desc_carpinteria AS
$body$
DECLARE
    Datos RECORD;
BEGIN
    
    FOR Datos IN select 	
    				a.nro_doc::varchar(10) ,
                    a.fecha::date , 
                    a.responsable::varchar (55) , 
                    of.ofc_des::varchar (80)
			 	from act.asignaciones a , act.oficina of
			 	where a.id_ofc = of.id_oficina and a.tip_doc = 4
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
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_desc_carpinteria (p_gestion integer)
  OWNER TO postgres;

/*
 *** - funcion para la lista de donacion- ***
*/
CREATE OR REPLACE FUNCTION act.ff_desc_donacion (
  p_gestion integer
)
RETURNS SETOF act.t_desc_donacion AS
$body$
DECLARE
    Datos RECORD;
BEGIN
   
    FOR Datos IN select 	
    				a.nro_doc::varchar(10) ,
                    a.fecha::date , 
                    a.responsable::varchar (55) , 
                    of.ofc_des::varchar (80)
			 	from act.asignaciones a , act.oficina of
			 	where a.id_ofc = of.id_oficina and a.tip_doc = 2
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
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_desc_donacion (p_gestion integer)
  OWNER TO postgres; 
  
=======
COST 100 ROWS 1000;

ALTER FUNCTION act.ff_desc_encargado (p_gestion integer)
  OWNER TO postgres;


-----------------fin

>>>>>>> 9221dfb7dd103406b6badcebf7c026398116a2c8
