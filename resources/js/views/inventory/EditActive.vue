<template>
  <div style="margin-top: 15px 0" class="grid-content bg-purple">
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
            <el-form-item size="mini" label="Unidad:" prop="oficina">
              <el-select
                v-model="editForm.ofc_cod"
                filterable
                remote
                :remote-method="getUnidades"
                placeholder="Seleccione una unidad"
                @change="getSubUnidades(editForm.ofc_cod)"
              >
                <el-option v-for="item in unidades"
                  :key="item.cod_ofc"
                  :label="item.descripcion"
                  :value="item.cod_ofc">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Vida Util:">
              <el-input
                v-model="editForm.vida_util"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="50">
            <el-form-item
              size="mini"
              label="SubUnidad:"
              prop="sub_ofc_cod"
            >
              <el-select v-model="editForm.sub_ofc_cod" placeholder="Seleccione una subunidad" >
                <el-option v-for="item in subUnidades"
                  :key="item.id"
                  :label="item.descripcion"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Estado de Activo:" prop="estado">
             <el-input v-model="editForm.estado" placeholder="" size="normal" clearable></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row>
          <el-col :span="50">
            <el-form-item
              size="mini"
              label="Descripcion:"
              prop="descripcion"
              width="300px"
            >
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
      estados: [],
      unidades:[],
      subUnidades:[],
      editForm: {
        des: "",
        des_det: "",
        vida_util: "",
        estado: "",
        ofc_cod: "",
        sub_ofc_cod: "",
        ci_resp: "",
        id: "",
      },
    };
  },
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde re asignacion de activos "
    );
    this.getEstados();
  },
  created() {
    //created vs mounted
    var app = this;
    this.id = this.$route.params.id;
    axios
      .get("/api/reasignacion/edit/" + this.id)
      .then((response) => {
        app.editForm = response.data[0];
        app.unidades.push({ 
          cod_ofc: app.editForm.ofc_cod,
          descripcion:app.editForm.oficina
        });
        app.subUnidades.push({ 
          id: app.editForm.sub_ofc_cod,
          descripcion:app.editForm.descripcion
        });
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
    getEstados() {
      axios
        .get("/api/activo/estados/")
        .then((data) => {
          this.estados = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getUnidades(keyWord) {
      axios
        .get("/api/inventory2/unidad/", {
          params: { keyWord: keyWord.toUpperCase() },
        })
        .then((data) => {
          this.unidades = Object.values(data.data.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getSubUnidades(cod_ofc) {
      this.unidadesLoading = true;
      this.subUnidadesLoading = true;
      axios
        .get("/api/inventory2/sub_unidad", {
          params: { cod_ofc: cod_ofc },
        })
        .then((data) => {
          this.subUnidadesLoading = false;
          this.subUnidades = data.data;
          this.editForm.sub_ofc_cod = null;
        })
        .catch((err) => {
          console.log(err);
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