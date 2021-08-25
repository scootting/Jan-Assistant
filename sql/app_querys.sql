/*
 *** - app.users - tabla para la gestion de usuarios - ***
*/
CREATE TABLE app.users (
  id SERIAL,
  activo BOOLEAN NOT NULL,
  nodip CHAR(10) NOT NULL,
  descripcion VARCHAR(50) NOT NULL,
  usuario VARCHAR(50) NOT NULL,
  clave VARCHAR NOT NULL,
  gestion SMALLINT,
  CONSTRAINT users_pkey PRIMARY KEY(id)
) 
WITH (oids = false);

ALTER TABLE app.users
  OWNER TO postgres;

/*
 *** - app.profiles - tabla para la gestion de los perfiles para cada sistema - ***
*/
CREATE TABLE app.profiles (
  id SERIAL,
  division CHAR(50) NOT NULL,
  grupo CHAR(50) NOT NULL,
  funcion CHAR(100) NOT NULL,
  icono CHAR(50),
  vista CHAR(100) NOT NULL,
  CONSTRAINT profiles_pkey PRIMARY KEY(id)
) 
WITH (oids = false);

ALTER TABLE app.profiles
  OWNER TO postgres;

/*
 *** - app.profiles - tabla para la gestion de los perfiles asignados a cada usuarios - ***
*/
CREATE TABLE app.userprofiles (
  id SERIAL,
  activo BOOLEAN NOT NULL,
  id_usuario INTEGER NOT NULL,
  id_perfil INTEGER NOT NULL,
  gestion SMALLINT NOT NULL,
  CONSTRAINT userprofiles_pkey PRIMARY KEY(id)
) 
WITH (oids = false);

ALTER TABLE app.userprofiles
  OWNER TO postgres;

/*
 *** - funcion para las gestiones(años) en las que tiene perfiles un usuario - ***
*/
CREATE OR REPLACE FUNCTION app.ff_gestiones_usuario (
  p_usuario varchar
)
RETURNS SETOF smallint AS
$body$
DECLARE
  Datos SMALLINT;
BEGIN
   FOR Datos IN SELECT up.gestion
   				  FROM app.profiles p
   			INNER JOIN app.userprofiles up
                    ON p.id = up.id_perfil
        		 WHERE up.id_usuario = (
            	SELECT u.id from app.users u where u.usuario = p_usuario
                     ) 
              GROUP BY up.gestion
              ORDER BY up.gestion DESC
                   LOOP
        RETURN NEXT Datos;
    END LOOP;
    IF NOT FOUND THEN
       RAISE EXCEPTION 'No existe gestiones para el usuario en la gestion';
    END IF;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION app.ff_gestiones_usuario (p_usuario varchar)
  OWNER TO postgres;

/*
 *** - funcion para determinar el ingreso del usuario al sistema - ***
*/
CREATE OR REPLACE FUNCTION app.ff_login_usuario (
  p_usuario varchar,
  p_clave varchar
)
RETURNS SETOF app.users AS
$body$
DECLARE
  Datos RECORD;
BEGIN
    IF p_usuario = '' OR p_clave = ''  THEN
       RAISE EXCEPTION 'El usuario o clave no puede ser nulos';
    END IF;        	
   FOR Datos IN SELECT u.id,
                       u.activo, 
                       u.nodip, 
                       u.descripcion, 
   					   u.usuario, 
                       u.clave, 
                       u.gestion
   				  FROM app.users u
        		 WHERE u.usuario = p_usuario
          		   AND u.clave = md5(p_clave) 
          		   AND u.activo = TRUE 
                   LOOP
        RETURN NEXT Datos;
    END LOOP;
    IF NOT FOUND THEN
       RAISE EXCEPTION 'El usuario o clave son incorrectas';
    END IF;    
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION app.ff_login_usuario (p_usuario varchar, p_clave varchar)
  OWNER TO postgres;

/*
 *** - funcion para determinar los perfiles asignados a cada usuario por gestion - ***
*/
CREATE OR REPLACE FUNCTION app.ff_perfiles_usuario (
  p_usuario varchar,
  p_gestion smallint
)
RETURNS SETOF app.profiles AS
$body$
DECLARE
  Datos RECORD;
BEGIN
   FOR Datos IN SELECT p.id,
                       p.division, 
                       p.grupo, 
                       p.funcion, 
   					   p.icono, 
                       p.vista 
   				  FROM app.profiles p
   			INNER JOIN app.userprofiles up
                    ON p.id = up.id_perfil
        		 WHERE up.id_usuario = (
            	SELECT u.id from app.users u where u.usuario=p_usuario
                     ) 
          		   AND up.gestion=p_gestion
                   AND up.activo<>FALSE 
              ORDER BY p.division, p.grupo, p.funcion
                   LOOP
        RETURN NEXT Datos;
    END LOOP;
    IF NOT FOUND THEN
       RAISE EXCEPTION 'No existe perfiles para el usuario en la gestion';
    END IF;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

/*
 *** - funcion para registrar nuevos usuarios - ***
*/
CREATE OR REPLACE FUNCTION app.ff_registrar_usuario (
  p_personal varchar
)
RETURNS varchar AS
$body$
DECLARE
  v_usuario varchar;
  v_clave varchar;
  v_descripcion varchar;
  p_gestion SMALLINT;                                             
  Datos RECORD;
BEGIN
	SELECT * FROM app.users u 
             INTO Datos
    		WHERE u.nodip = p_personal;
    IF FOUND THEN
       RAISE EXCEPTION 'El usuario con el numero de idenficacion ya existe.';
    END IF;
    IF p_personal = '' THEN
       RAISE EXCEPTION 'El numero de identificacion no puede ser nulo.';
    END IF;               
    SELECT p.paterno, 
           p.materno, 
           p.nombres, 
 	       p.nro_dip
      INTO Datos 
   	  FROM public.personas p
     WHERE p.nro_dip = p_personal;
    IF NOT FOUND THEN
       RAISE EXCEPTION 'No existe la persona con el numero de identificacion, registrela primero';
    ELSE
       v_usuario := Datos.nombres::char(1) || iif(Datos.paterno = '', Datos.paterno, Datos.materno);
       v_descripcion := iif(Datos.paterno <> '', Datos.paterno || ' ', '') || Datos.materno || ', ' || Datos.nombres;
       v_clave := md5('12345678'); 
       p_gestion := (SELECT gestion FROM public.sis_gestion ps WHERE ps.estado='S');
       /*
       INSERT INTO app.users
       */
       INSERT INTO app.users(activo, nodip, descripcion, usuario, clave, gestion)
            VALUES (true, p_personal, v_descripcion, lower(v_usuario), v_clave, p_gestion);       
       RETURN 'Msg: El Usuario : [' || v_usuario || '] fue Creado';
    END IF;        
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION app.ff_registrar_usuario (p_personal varchar)
  OWNER TO postgres;