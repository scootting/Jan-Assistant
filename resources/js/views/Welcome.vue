<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>bienvenido</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <h1>bienvenido/a</h1>
      <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
      <el-button type="text"  @click="openModalPerson">Personas</el-button>
      <example msg="Welcome to Your Vue.js App" />
      <persona></persona>
      <!-- 
      -->
    </el-card>
  </div>
</template>

<script>
import persona from "./components/person";
import example from "./components/example.vue";

export default {
  name: "Bienvenido",
  components: {
    persona,
    example,
  },
  data() {
    return {
      target: false,
      messages: {},
      data: {},
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    openModalPerson(){
      this.$emit('openModal');
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
