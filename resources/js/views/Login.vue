<template>
  <div>
    <el-main>
      <el-row :gutter="20" style="padding-top: 200px">
        <el-col :span="4" :offset="7">
          <div class="header">
            <el-image :src="url_image" style="width: 90%; height: 90%">
              <div slot="placeholder" class="image-slot">
                Loading<span class="dot">...</span>
              </div>
            </el-image>
          </div>
        </el-col>
        <el-col :span="6">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>login</span>
              <el-button style="float: right; padding: 3px 0" type="text" @click.native="drawer = true">ayuda
              </el-button>
            </div>
            <div>
              <el-form ref="form" class="login-form" :model="model" :rules="rules" @submit.native.prevent="login">
                <el-form-item prop="username">
                  <el-input v-model="model.username" placeholder="Usuario">
                    <i slot="prefix" class="el-input__icon el-icon-user"></i>
                  </el-input>
                </el-form-item>
                <el-form-item prop="password">
                  <el-input v-model="model.password" placeholder="Contraseña" type="password">
                    <i slot="prefix" class="el-input__icon el-icon-lock"></i>
                  </el-input>
                </el-form-item>
                <el-form-item>
                  <el-button :loading="loading" class="login-button" type="primary" native-type="submit" block>acceder
                  </el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-card>
          <div class="footer">
            <el-button style="float: right; padding: 3px 0" type="text" @click.native="saber = true">Verifique que está
              registrado</el-button>
            <div class="version">Version 1.01.01</div>
          </div>
        </el-col>
      </el-row>
      <!-- *** Formulario de Ayuda al Usuario *** -->
      <el-drawer title="Ayuda" :visible.sync="drawer" :with-header="false">
        <span>Contacto con la unidad de sistemas de la direccion administrativa y
          financiera</span>
        <span>74246032</span>
      </el-drawer>
      <el-drawer title="Formulario" :visible.sync="saber" :with-header="false">
        <div class="demo-drawer__content">
          <el-row>
            <el-col :span="20">
              <el-form label-width="150px" @submit.native.prevent="search">
                <el-form-item label="CI:" prop="personal">
                  <el-input size="small" v-model="nro_dip"></el-input>
                </el-form-item>
              </el-form>
            </el-col>
            <el-col>
              <el-button icon="el-icon-search" type="primary" :loading="loading" class="login-button"
                native-type="submit" @click="search(nro_dip)">buscar</el-button>
            </el-col>
          </el-row>
          <el-divider content-position="left">INGRESE SUS DATOS</el-divider>
          <el-row>
            <el-col :span="20">
              <el-form ref="form" :model="person" :rules="rules" label-width="260px" :disabled="existe==true">
                <el-form-item size="small" label="numero de identificacion" prop="personal">
                  <el-input size="small" v-model="person.personal"></el-input>
                </el-form-item>
                <el-form-item size="small" label="nombres" prop="nombres">
                  <el-input size="small" v-model="person.nombres"></el-input>
                </el-form-item>
                <el-form-item size="small" label="apellido paterno">
                  <el-input size="small" v-model="person.paterno"></el-input>
                </el-form-item>
                <el-form-item size="small" label="apellido materno" prop="materno">
                  <el-input size="small" v-model="person.materno"></el-input>
                </el-form-item>
                <el-form-item size="small" label="fecha de nacimiento" prop="nacimiento">
                  <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                    v-model="person.nacimiento" style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item size="small" label="genero">
                  <el-radio-group v-model="person.sexo" size="small">
                    <el-radio-button label="M"></el-radio-button>
                    <el-radio-button label="F"></el-radio-button>
                  </el-radio-group>
                </el-form-item>
                <el-form-item size="small" label="teléfono" prop="telefono">
                  <el-input size="small" v-model="person.telefono"></el-input>
                </el-form-item>
                <el-form-item size="small" label="dirección" prop="dirección">
                  <el-input size="small" v-model="person.direccion"></el-input>
                </el-form-item>
                <el-form-item size="small" label="e-mail" prop="correo">
                  <el-input size="small" v-model="person.correo"></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="savePerson" plain>Guardar</el-button>
                  <el-button size="small" type="primary" @click="cancelForm">Cancel</el-button>
                </el-form-item>
              </el-form>
            </el-col>
          </el-row>
        </div>
      </el-drawer>
    </el-main>
  </div>
</template>

<script>
export default {
  name: "loginn",
  data() {
    return {
      model: {
        username: null,
        password: null,
      },
      drawer: false,
      saber: false,
      url_image: "/images/EUATF.png", //url('../images/EUATF.png'),//
      loading: false,
      error: null,
      rules: {
        username: [
          {
            required: true,
            message: "El usuario es requerido",
            trigger: "blur",
          },
          {
            min: 4,
            message: "El usuario de tener por lo menos 5 caracteres",
            trigger: "blur",
          },
        ],
        password: [
          {
            required: true,
            message: "La contraseña es requerida",
            trigger: "blur",
          },
          {
            min: 5,
            message: "La contraseña de tener por lo menos 5 caracteres",
            trigger: "blur",
          },
        ],
      },
      nro_dip: null,
      existe: false,
      messages: {},
      person: {
        personal: "",
        nombres: "",
        paterno: "",
        materno: "",
        nacimiento: "",
        sexo: "M",
        telefono: "",
        direccion: "",
        correo: "",
      },
      rules: {
        personal: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        nombres: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        materno: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        nacimiento: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
        ],
        telefono: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede mas de 8",
            trigger: "blur",
          },
        ],
        direccion: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 5 o mas de 100",
            trigger: "blur",
          },
        ],
        correo: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch("retrieveToken", {
          username: this.model.username,
          password: this.model.password,
        })
        .then((response) => {
          this.$router.push({ name: "welcome" });
        })
        .catch((error) => {
          this.error = error.response.data;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });
    },
    search(nro_dip) {
      console.log("esto es una prueba", this.nro_dip);
      if (nro_dip != null) {
        axios
          .get("persona", { params: { nro_dip: nro_dip } })
          .then((data) => {
            if (nro_dip != null) {
              // this.$notify({
              //   title: "Se encuentra registrado",
              //   message: "Usted esta registrado en el sistema",
              //   type: "success",
              // });
              this.person.nombres = data.data[0].nombres;
              this.person.materno = data.data[0].materno;
              this.person.paterno = data.data[0].paterno;
              this.person.personal = data.data[0].nro_dip;
              this.person.nacimiento = data.data[0].fec_nacimiento;
              this.person.sexo = data.data[0].id_sexo;
              this.person.direccion = data.data[0].direccion;
              this.person.correo = data.data[0].correo;
              this.person.telefono = data.data[0].telefono;
              this.existe = true;
            }
          })
          .catch((err) => {
            this.$notify({
              title: "Usted no se encuentra registrado",
              message: "Por favor ingrese sus datos",
              type: "warning",
            });
            this.existe = false;
            this.person.nombres = null;
            this.person.materno = null;
            this.person.paterno = null;
            this.person.personal = null;
            this.person.nacimiento = null;
            this.person.sexo = null;
            this.person.direccion = null;
            this.person.correo = null;
            this.person.telefono = null;
          });
      } else {
        this.$notify({
          title: "ingrese un CI",
          message: "Por favor ingrese su CI para verificar su existencia",
          type: "warning",
        });
      }
    },
    savePerson() {
      axios
        .post("newPerson", this.person)
        .then((data) => {
          this.$notify.success({
            title: "La persona fue registrada exitosamente!",
            message: "Se realizó el registro de la persona correspondiente",
            duration: 3000,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    cancelForm() {
      this.loading = false;
      this.saber = false;
      clearTimeout(this.timer);
    },
  },
};
</script>
<style scoped>
.login .el-card {
  display: flex;
  justify-content: center;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both;
}

/*estilo aprobado para su uso*/
.login-button {
  width: 100%;
  margin-top: 20px;
}

.header,
.footer {
  padding: 20px 20px;
  color: #f0f4f8;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.footer .version {
  font-family: "Open Sans";
  padding: 0 10px;
  color: #9fb3c8;
  font-size: 15px;
  margin-top: 5px;
}
</style>