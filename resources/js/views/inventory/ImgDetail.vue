<template>
  <div  style="margin-top: 15px 0" class="grid-content bg-purple">
    <el-card class="box-card">
      <el-row>
        <el-col :span="24">
          <el-form ref="form" :model="image" label-width="260px">
            <el-form-item size="small" label="Codigo" prop="id">
              <el-input size="small" v-model="image.id"></el-input>
            </el-form-item>
            <el-form-item size="small" label="descripcion de activo" prop="des">
              <el-input size="small" v-model="image.des"></el-input>
            </el-form-item>
            <el-form-item size="small" label="oficina">
              <el-input size="small" v-model="image.ofc_cod"></el-input>
            </el-form-item>
            <el-form-item size="small" label="gestion" prop="gestion">
              <el-input size="small" v-model="gestion"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <br><br>
      <load-imgs :info="dataSource" :default-values="data" @change="onChangeImgs"/> 
      <br>
      <!-- <uploader :csrf="csrfToken" :info="dataSource"></uploader> -->
      <el-button type="primary" size="mini" plain @click="saveImage">Guardar Imagenes</el-button>
      <el-button type="danger" size="mini" plain @click="returnPage">Atras</el-button>
    </el-card>
    
    
  </div>
</template>
 
<script>
//import Uploader from '../components/uploader.vue';
import LoadImgs from './components/ImagenDetail/loadImgs.vue';
export default {
  name: "ImgDetail",
  components: {
    LoadImgs,
  },
  data() {
    return {
      id: null,
      messages: {},
      gestion: this.$store.state.user.gestion,
      image: {},
      no_doc:this.$route.params.no_cod,
      data: {
        cod_act: '',
      },
    };
  },
  created() {
    //created vs mounted
    var app = this;
    this.id = this.$route.params.id;
    //recuperar
  	this.getData();
    this.no_doc = this.$route.params.no_cod;
    axios
      .get("/api/reasignacion/edit/" + this.id)
      .then((response) => {
        app.image = response.data[0];
        app.data.cod_act = response.data[0].id;
      })
      .catch((error) => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message,
        });
      });
  },
  computed: {
    dataSource: function () {
      return this.image;
    },
  },
  methods: {
    getData(){
      axios
        .get("/api/inventory2/image/"+this.id)
        .then((data) => {
          if(data.data[0] != undefined)
            this.data = data.data[0];
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onChangeImgs(imgs){
      this.data = {...this.data, ...imgs};
    },
    saveImage(){
      axios
        .post("/api/inventory2/saveImage", this.data)
        .then((data) => {
          this.$notify.success({
            title: "Imagen (es) guardada (s)",
            message: "Se guardo exitosamente",
            duration: 3000,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    returnPage(no_doc){
      this.$router.push({
        name: "inventory2detail",
        params: {
          no_doc:this.$route.params.no_cod,
        }
      });
    },
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.bg-purple {
  background: #d3dce6;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
</style>