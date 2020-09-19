<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo usuario</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form ref="form" :model="user" :rules="rules" label-width="260px">
              <el-form-item size="small" label="numero de identificacion" prop="personal">
                <el-input size="small" v-model="user.personal"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button size="small" type="primary" @click.prevent="saveUser('user')" plain>Crear</el-button>
                <el-button size="small" type="danger" @click="noUser" plain>Cancelar</el-button>
              </el-form-item>
              <!--
              <el-form-item size="small" label="descripcion" prop="descripcion">
                <el-input size="small" v-model="user.descripcion" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item size="small" label="usuario">
                <el-input size="small" v-model="user.name" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item size="small" label="apellido materno" prop="materno">
                <el-input size="small" v-model="user.materno" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item size="small" label="genero">
                <el-radio-group v-model="user.estado" size="small">
                  <el-radio-button label="T"></el-radio-button>
                  <el-radio-button label="F"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              -->
            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "AniadirPersona",
  data() {
    return {
      messages: {},
      user: {
        personal: "",
        /* description: "", name: "", password:"", state: "T" */
      },
      rules: {
        personal: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
        ],
      },
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    saveUser() {
      var app = this;
      var newUser = app.user;

      axios
        .post("/api/user", {
          usuario: newUser,
        })
        .then(function (response) {
          alert("se ha creado el registro del usuario");
        })
        .catch(function (error) {
          app.error = error.response.data;
          console.log(error.response.data);
          app.$notify.error({
            title: "Error",
            message: app.error.message,
          });
        });
    },

    noUser() {
      this.$router.push("/api");
    },

    resetUser() {},
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
}
.el-form {
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 60px;
}
</style>
