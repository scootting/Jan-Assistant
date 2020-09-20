<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>perfil</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>

      <div>
        <el-row>
          <el-col :span="24">
            <el-form ref="form" :model="user" label-width="260px">
              <el-form-item size="small" label="numero de identificacion" prop="nodip">
                <el-input size="small" v-model="user.nodip"></el-input>
              </el-form-item>
              <el-form-item size="small" label="apellidos y nombres" prop="descripcion">
                <el-input size="small" v-model="user.descripcion"></el-input>
              </el-form-item>
              <el-form-item size="small" label="nombre de usuario" prop="usuario">
                <el-input size="small" v-model="user.usuario"></el-input>
              </el-form-item>
              <el-form-item size="small" label="clave de usuario" prop="clave" >
                <el-input style="width:84%" size="small" v-model="user.clave"></el-input>
                <el-button style="width:15%"  size="small" type="primary" @click.prevent="changePassword" plain>Cambiar</el-button>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Profile",
  data() {
    return {
      messages: {},
      data: {},
      user: {
        nodip: "",
        descripcion: "",
        usuario: "",
        clave: "",
      },
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    changePassword() {},
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
