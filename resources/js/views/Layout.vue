<template>
  <div>
    <!-- Carousel -->
    <el-card class="box-card">
      <div class="carousel-container">
        <el-carousel type="card" height="200px">
          <el-carousel-item v-for="item in 3" :key="item">Imagen {{ item }}</el-carousel-item>
        </el-carousel>
      </div>
      <div>
        <h1>Bienvenidos!</h1>
        <p>
          Como parte del compromiso de una mejora continua, tenemos el placer de
          compartir con ustedes la actualización de nuestra página web.
          <br /><br />Nuestro objetivo es mejorar su experiencia como
          visitante y continuar facilitando la realización de sus tramites.
          <br /><br />
          Agradecemos sus sugerencias, y les pedimos que las compartan con
          nosotros para seguir mejorando. Agradecemos su apoyo constante.
        </p>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Bienvenido",
  data() {
    return {
      messages: {},
      data: {},
    };
  },
  mounted() { },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initLogin() {
      this.$router.push({ name: "login" });
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>

<style>
.carousel-container {
  margin: 20px;
}

.el-carousel__item:nth-child(2n) {
  background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n+1) {
  background-color: #d3dce6;
}

</style>