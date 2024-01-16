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
              <el-button style="float: right; padding: 3px 0" type="text" @click.native="dialogVisible = true">ayuda
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
            <el-button style="float: right; padding: 3px 0" type="text" @click.native="initRegisterPerson">Verifique si
              está
              registrado</el-button>
            <div class="version">Version 1.01.01</div>
          </div>
        </el-col>
      </el-row>
      <el-dialog title="Soporte" :visible.sync="dialogVisible" width="30%">
        <span>Puede ponerse en contacto con el siguiente numero si tiene problemas para ingresar, 74246032.</span>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible = false" size="medium">Cerrar</el-button>
        </span>
      </el-dialog>
    </el-main>
  </div>
</template>
<script>
export default {
  name: "login",
  data() {
    return {
      model: {
        username: null,
        password: null,
      },
      dialogVisible: false,
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
    initRegisterPerson() {
      this.$router.push({ name: "register" });
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