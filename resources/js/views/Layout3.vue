<template>
  <div>
    <el-card class="box-card" style="text-align: justify">
      <div class="block">
        <el-carousel height="350px">
          <el-carousel-item v-for="item in 4" :key="item">
            <h3 class="small">{{ "imagen "+ item }}</h3>
          </el-carousel-item>
        </el-carousel>
      </div>
      <div>
        <h1>Bienvenidos!</h1>
        <p>
          Como parte del compromiso de una mejora continua, tenemos el placer de
          compartir con ustedes la actualización de nuestra página web.
          <br /><br />La migración a la nueva plataforma otorga nueva tecnología
          que nos ayuda a mejorar la experiencia de nuestros visitantes. Durante
          los próximos meses, comenzará a ver cambios sutiles en el diseño del
          sitio entre los que se incluyen modificaciones en la navegación, para
          que se simplifique aún más la búsqueda de la información y el
          lanzamiento de nuevas herramientas para asistirlos en sus necesidades
          comerciales.
          <br /><br />Nuestro objetivo es mejorar su experiencia como
          visitante y continuar facilitando la realización de sus tramites.
          <br /><br />
          Agradecemos sus sugerencias, y les pedimos que las compartan con
          nosotros para seguir mejorando. Agradecemos su apoyo constante.
        </p>
      </div>
    </el-card>

  <!--
    <el-row :gutter="20">
      <el-col :offset="4" :span="4">
        <el-card>
          <el-button type="text" icon="el-icon-message" circle @click.native="initLogin"></el-button>
          <h4>valores en linea</h4>
          <p>
            modulo para la venta de valores en linea
          </p>
        </el-card>
      </el-col>
      <el-col :span="4">
        <el-card>
          <el-button type="text" icon="el-icon-edit" circle @click.native="initLogin"></el-button>
          <h4>tramites</h4>
          <p>
            modulo para la solicitud de tramite
          </p>
        </el-card>
      </el-col>
      <el-col :span="4">
        <el-card>
          <el-button type="text" icon="el-icon-document" circle @click.native="initLogin"></el-button>
          <h4>solicitud de memorial</h4>
          <p>
            modulo para la solicitud de memorial
          </p>
        </el-card>
      </el-col>
      <el-col :span="4">
        <el-card>
          <el-button type="text" icon="el-icon-s-order" circle @click.native="initLogin"></el-button>
          <h4>solvencias</h4>
          <p>
            modulo para la solicitud de solvencia
          </p>
        </el-card>
      </el-col>
    </el-row>
    -->
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
.el-carousel__item h3 {
  color: #475669;
  font-size: 14px;
  opacity: 0.75;
  line-height: 150px;
  text-align: center;
  margin: 0;
}

.el-carousel__item:nth-child(2n) {
  background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n+1) {
  background-color: #d3dce6;
}
</style>