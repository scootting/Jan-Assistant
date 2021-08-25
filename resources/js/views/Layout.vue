<template>
  <div>
    <el-card class="box-card" style="text-align: justify">
      <h1>Hola, Bienvenidos a la nueva plataforma!</h1>
      <p>
        Como parte del compromiso de una mejora continua, tenemos el placer de
        compartir con ustedes la actualización de nuestra página web.
        <br /><br />La migración a la nueva plataforma otorga nueva tecnología
        que nos ayuda a mejorar la experiencia de nuestros visitantes. Durante
        los próximos meses, comenzará a ver cambios sutiles en el diseño del
        sitio entre los que se incluyen modificaciones en la navegación, para
        que se simplifique aún más la búsqueda de la información y el
        lanzamiento de nuevas herramientas para asistirlos en sus necesidades
        comerciales. <br /><br />Nuestro objetivo es mejorar su experiencia como
        visitante y continuar facilitando la realización de sus tramites.
        <br /><br />
        Agradecemos sus sugerencias, y les pedimos que las compartan con
        nosotros para seguir mejorando. Agradecemos su apoyo constante.
      </p>
      <!--
      <el-button size="mini" plain @click="loadReportAssets()"
        >Default</el-button
      >
      <el-button type="primary" size="mini" plain>Primary</el-button>
      <el-button type="primary" icon="el-icon-edit" size="mini"></el-button>
      <el-button type="primary" icon="el-icon-share" size="mini"></el-button>
      -->
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
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
