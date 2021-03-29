<template>
  <div>
    <el-main>
      <el-row :gutter="20">
        <el-col :span="6" :offset="9">
          <div class="header">
            <h2>agrege el logo aca</h2>
          </div>
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>USUARIO RECURRENTE</span>
              <el-button
                style="float: right; padding: 3px 0"
                type="text"
                @click.native="drawer = true"
                >contacto</el-button
              >
            </div>
            <div>
              <el-form
                ref="form"
                class="login-form"
                :model="model"
                :rules="rules"
                @submit.native.prevent="login"
              >
                <el-form-item prop="username">
                  <el-input v-model="model.username" placeholder="Usuario">
                    <i slot="prefix" class="el-input__icon el-icon-user"></i>
                  </el-input>
                </el-form-item>
                <!--
                <el-form-item prop="password">
                  <el-input v-model="model.password" placeholder="Contaseña" type="password">
                    <i slot="prefix" class="el-input__icon el-icon-lock"></i>
                  </el-input>
                </el-form-item>
                -->
                <el-form-item>
                  <el-button
                    :loading="loading"
                    class="login-button"
                    type="success"
                    native-type="submit"
                    block
                    >ingresar</el-button
                  >
                </el-form-item>
              </el-form>
            </div>
          </el-card>
          <div class="footer">
            <div class="version">
              <el-button type="text" @click="dialogVisible = true"
                >Verifique si se encuentra registrado</el-button
              >
            </div>
          </div>
        </el-col>
      </el-row>
      <!-- *** Formulario de Ayuda al Usuario *** -->
      <el-drawer title="Ayuda" :visible.sync="drawer" :with-header="false">
        <span
          >Contacto con la unidad de sistemas de la direccion administrativa y
          financiera</span
        >
        <span>74246032</span>
      </el-drawer>
      <!-- *** Formulario de Ayuda al Usuario *** -->
    </el-main>
  </div>
</template>

<script>
export default {
  name: "login",
  data() {
    return {
      dialogVisible: false,
      model: {
        username: null,
        password: null,
      },
      person:[],
      drawer: false,
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
    };
  },
  methods: {
    login() {
      let app = this;
      axios
        .get("/person/" + app.model.username)
        .then(function (response) {
          app.person = response.data[0];
          console.log(app.person);
          app.$router.push({ name: 'dashboardclient', params: { id: '6600648' } })          
        })
        .catch(function () {
          alert("No se puede hallar el registro de la persona indicada");
        });
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