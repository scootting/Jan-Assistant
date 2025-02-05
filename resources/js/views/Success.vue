<template>
  <div>
    <el-row :gutter="20">
      <el-col :span="6" :offset="9">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>Pago de matriculas (para estudiantes regulares)</span>
          </div>
          <div>
            <center>
              <p>Si realizo el pago correctamente, haga click en el siguiente enlace para imprimir su matricula</p>
              <el-button type="success" @click="initPage" size="small">Imprimir
                Matricula</el-button>
            </center>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <p></p>
    <el-row :gutter="20">
      <el-col :span="6" :offset="9">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>Pago de postulaciones (para estudiantes de colegio)</span>
          </div>
          <div>
            <center>
              <p>Si realizo el pago correctamente, haga click en el siguiente enlace para imprimir su matricula</p>
              <el-button type="warning" @click="initThirdPage" size="small">Imprimir
                confirmacion</el-button>
            </center>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <p></p>
    <el-row :gutter="20">
      <el-col :span="6" :offset="9">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>pago de valores universitarios</span>
          </div>
          <div>
            <center>
              <p>Si realizo el pago correctamente, haga click en el siguiente enlace para imprimir los comprobantes de
                pago de los valores adquiridos</p>
              <el-button type="primary" @click="initSecondPage" size="small">Imprimir
                papeletas de pago</el-button>
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
    initSecondPage() {
      window.location.href = 'https://daf.uatf.edu.bo/api/';
    },
    initThirdPage() {
      window.location.href = 'https://postulaciones.uatf.edu.bo';
    },
  },
};
</script>
