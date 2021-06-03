<template>
  <div>
      <el-button type="primary" size="default" @click="$router.go(-1)">Volver Atras</el-button>
      <el-row type="flex" justify="center">
          <vue-qr :text="JSON.stringify(activo)" :size="400"></vue-qr>
      </el-row>
      
  </div>
</template>

<script>
import VueQr from 'vue-qr';
export default {
    components:{ VueQr },
    data(){
        return {
            activo:null,
        };
    },
    created(){
        this.activo = this.$route.params.activo;
    },
    mounted(){
        setTimeout(()=>{
            window.print();
        },3000);
    },
}
</script>

<style>

</style>