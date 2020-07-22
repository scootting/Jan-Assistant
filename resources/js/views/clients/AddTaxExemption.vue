<template>
  <div>
    <el-card class="box-card">
        <el-row>
          <el-col :span="24">
            <el-form ref="form" :model="tax"  label-width="260px">
              <el-form-item size="small" label="numero de identificacion" prop="personal">
                <el-input size="small" v-model="tax.personal"></el-input>
              </el-form-item>
              <el-form-item size="small" label="descripcion" prop="descripcion">
                <el-input size="small" v-model="tax.descripcion"></el-input>
              </el-form-item>
              <el-form-item size="small" label="mes">
                <el-input size="small" v-model="tax.mes"></el-input>
              </el-form-item>
              <el-form-item size="small" label="gestion" prop="gestion">
                <el-input size="small" v-model="tax.gestion"></el-input>
              </el-form-item>
            </el-form>
          </el-col>   
          </el-row>   
      <uploader :csrf="csrfToken" :info="dataSource"></uploader>
    </el-card>
  </div>
</template>

<script>
import uploader from "../components/uploader";
export default {
  components: {
    uploader
  },
  data() {
    return {
      messages: {},
      tax: {
        descripcion: "Pueblo Alejo, Juan",
        personal: "6600647",
        mes: "Agosto",
        gestion: "2020",
        tipo: "Bono de Te"
      }
    };
  },
  computed: {
    // un getter computado
    reversedMessage: function() {
      // `this` apunta a la instancia vm
      return "hola";
    },
    csrfToken: function() {
      return window.axios.defaults.headers.common["X-CSRF-TOKEN"];
    },
    dataSource: function(){
      return this.tax;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
