<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>perfil</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>        
      </div>
      
      <h1></h1>
      <h5>aca viene los perfiles</h5>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Profile",
  data() {
    return {
      messages: {},
      data: {}
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
