<template>
  <div>
    <el-row :gutter="20">
      <el-col :span="6" :offset="9">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>¿Realizo el pago correctamente?</span>
          </div>
          <div>
            <p>Si realizo el pago correctamente, haga click en el siguiente enlace para imprimir su matricula</p>
            <center>
              <el-button type="success" @click="initPage">Imprimir
                Matricula</el-button>

            </center>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
  
<script>
export default {
  data() {
    return {
      messages: {},
      data: {},
    };
  },
  mounted() { 
    //this.initPage();
  },
  methods: {
    test() {

      //alert("bienvenido al modulo");
    },
    initPage() {
      window.location.href = 'https://estudiantes.uatf.edu.bo/home';
    },
  },
};
</script>
