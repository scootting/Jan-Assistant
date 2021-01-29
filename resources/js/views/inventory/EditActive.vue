<template>
    <div>
        <el-card class="box-card">
        <el-form :model="form" ref="form" label-width="80px" :inline="false" size="normal">
            <el-row>
                <el-col :span="11" center>
                    <el-form-item size="small" label="CI Responsable" prop="proveedor">
                    <el-input v-model="editForm.ci_resp"></el-input>
                    </el-form-item>
                </el-col>
                </el-row>
                <hr style="color:gray;" />
                <br />
                <el-row>
                <el-col :span="11">
                    <el-form-item size="small" label="Uni.Med." prop="uni_med">
                    <el-select v-model="editForm.uni_med">
                        <el-option
                        v-for="item in unidMeds"
                        :key="'u_m_key_'+item.id_uni_med"
                        :label="item.uni_des_det"
                        :value="item.uni_des_cor"
                        ></el-option>
                    </el-select>
                    </el-form-item>
                </el-col>
                </el-row>
                <el-row>
                <el-col :span="11">
                    <el-form-item size="small" label="Cantidad" prop="cantidad">
                    <el-input v-model="editForm.can"></el-input>
                    </el-form-item>
                </el-col>
                </el-row>
                <hr style="color:gray;" />
                <br />
                <el-row>
                <el-col :span="11">
                    <el-form-item size="small" label="Vida Util" required><br>
                    <el-input v-model="editForm.vida_util"></el-input>
                    </el-form-item>
                    <el-form-item size="small" label="Precion Unitario" required> <br>
                    <el-input v-model="editForm.imp_bs"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="11">
                    <el-form-item size="small" label="Descripcion" prop="descripcion"> <br>
                    <el-input type="textarea" v-model="editForm.des" rows="5" max-rows="8"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="11">
                    <el-form-item size="small" label="Descripcion Detallada" prop="descripcion" width="180px"> <br>
                    <el-input type="textarea" v-model="editForm.des_det" rows="5" max-rows="8"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
                <el-form-item>
                <el-button type="primary" plain @click="saveAsset">Guardar Cambios</el-button>
                <el-button type="danger" plain @click="Exit">Cancelar</el-button>
                </el-form-item>
        </el-form>
        </el-card>
    </div>
</template>

<script>
export default {
  name: "EditActive",
  data() {
    return {
      gestion: this.$store.state.user.gestion,
      info: {},
      data: {},
      editForm: {
        can: "",
        des: "",
        des_det: "",
        uni_med: "",
        vida_util: "",
        imp_bs: "",
        est_cod: "",
        id: "",
        car_cod: "",
      },
      unidMeds: [],
      ofc_cod:"",
      sub_ofc_cod:[],
    };
  },
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde re asignacion de activos ",
    );
  },
  created() {  //created vs mounted
    var app = this;
    this.id = this.$route.params.id;
    axios
      .get("/api/reasignacion/edit/"+this.id)
      .then(response => {
        app.editForm = response.data[0];
      })
      .catch(error => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message
        });
      });
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
        },
    noVerificate(){
    },
    Exit() {
      this.$notify.info({
        title: "Edicion cancelada",
        message: "No se hizo cambios al Activo seleccionado",
        duration: 0
      });
      this.$router.push({
        name: "active",
      });

    },
  
    saveAsset() {
     
    },
  }
};
</script>

<style>

</style>