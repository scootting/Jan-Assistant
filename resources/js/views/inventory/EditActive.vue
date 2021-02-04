<template>
  <div style="margin-top: 15px 0;"  class="grid-content bg-purple">
    <el-card class="box-card">
      <el-form
        :label-position="right"
        label-width="220px"
        :model="form"
        ref="form"
        :inline="false"
        size="normal"
        class="demo-form-inline"
      >
        <span> Activo: {{ editForm.id }} </span> <br /><br />
        <hr style="color: gray" />
        <br />
        <el-row>
          <el-col :span="50"> 
            <div>
            <el-form-item size="mini" label="CI Responsable:" prop="ci_resp">
              <el-input
                v-model="editForm.ci_resp"
                style="width: 200px"
              ></el-input>
            </el-form-item> 
            </div>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row>
          <el-col :span="50">
            <el-form-item size="mini" label="Codigo Unidad:" prop="ofc_cod">
              <el-input v-model="editForm.ofc_cod" label="oficina" style="width: 200px"></el-input>
            </el-form-item> 
            <el-form-item size="mini" label="Vida Util:">
              <el-input
                v-model="editForm.vida_util"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="50">
            <el-form-item size="mini" label="Codigo SubUnidad:" prop="sub_ofc_cod">
              <el-input v-model="editForm.sub_ofc_cod" style="width: 200px"></el-input>
            </el-form-item> 
            <el-form-item size="mini" label="Estado de Activo:" prop="estado">
              <el-select v-model="editForm.estado">
                <el-option
                  v-for="item in estado"
                  :key=" item.estado"
                  :label="item.estado"
                  :value="item.estado"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row>
          <el-col :span="50">
            <el-form-item size="mini" label="Descripcion:" prop="descripcion" width="300px">
              <el-input
                type="textarea"
                v-model="editForm.des"
                rows="10"
                max-rows="10"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="50">
            <el-form-item
              size="mini"
              label="Descripcion Detallada:"
              prop="descripcion"
              width="300px"
            >
              <el-input
                type="textarea"
                v-model="editForm.des_det"
                rows="10"
                max-rows="10"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button size="mini" type="primary" plain @click="saveAsset"
            >Guardar Cambios</el-button
          >
          <el-button size="mini" type="danger" plain @click="Exit"
            >Cancelar</el-button
          >
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
      editForm: {
        des: "",
        des_det: "",
        vida_util: "",
        estado:"",
        ofc_cod:"",
        sub_ofc_cod:"",
        ci_resp:"",
        id: "",
      },
      unidMeds: [],
      ofc_cod: "",
    };
  },
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde re asignacion de activos "
    );
  },
  created() {
    //created vs mounted
    var app = this;
    this.id = this.$route.params.id;
    axios
      .get("/api/reasignacion/edit/" + this.id)
      .then((response) => {
        app.editForm = response.data[0];
      })
      .catch((error) => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message,
        });
      });
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    noVerificate() {},
    Exit() {
      this.$notify.info({
        title: "Edicion cancelada",
        message: "No se hizo cambios al Activo seleccionado",
        duration: 0,
      });
      this.$router.push({
        name: "active",
      });
    },
    saveAsset() {
      axios
        .post("/api/reasignacion/save", this.editForm)
        .then((data) => {
          this.$notify.success({
        title: "Cambios guardados",
        message: "Se realizo cambios al Activo seleccionado exitosamente",
        duration: 0,
      });
      this.$router.push({
        name: "active",
      });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    
  },
};
</script>

<style> 
.bg-purple {
  background: #d3dce6;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
</style>