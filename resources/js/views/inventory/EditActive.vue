<template>
  <div style="margin-top: 15px 0;">
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
            <el-form-item size="mini" label="CI Responsable:" prop="ci_resp">
              <el-input
                v-model="editForm.ci_resp"
                style="width: 200px"
              ></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Cargo del Responsable:" prop="ci_resp">
              <el-input
                v-model="editForm.car_cod"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="50">
            <el-form-item
              size="mini"
              label="Nombre Responsable:"
              prop="ci_resp"
            >
              <el-input
                v-model="editForm.ci_resp"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row>
          <el-col :span="50">
            <el-form-item size="mini" label="Codigo Unidad:" prop="ofc_cod">
              <el-input
                v-model="editForm.ofc_cod"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="50">
            <el-form-item
              size="mini"
              label="Codigo SubUnidad:"
              prop="sub_ofc_cod"
            >
              <el-input
                v-model="editForm.sub_ofc_cod"
                style="width: 200px"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="50">
            <el-form-item size="mini" label="Unidad de Medida:" prop="uni_med">
              <el-input v-model="editForm.uni_med" style="width:200px"></el-input>
            </el-form-item> 
            <el-form-item size="mini" label="Cantidad:" prop="cantidad">
              <el-input v-model="editForm.fec_adq" style="width:200px" disabled></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Cantidad:" prop="cantidad">
              <el-input v-model="editForm.can" style="width:200px" disabled></el-input>
            </el-form-item>
          </el-col> 
           <el-col :span="50"> 
             <el-form-item size="mini" label="Estado de Activo:" prop="estado">
              <el-select v-model="editForm.estado">
                <el-option
                  v-for="item in estado"
                  :key="'u_m_key_' + item.estado"
                  :label="item.estado"
                  :value="item.estado"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Vida Util:">
              <el-input
                v-model="editForm.vida_util"
                style="width: 200px"
              ></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Precion Unitario:">
              <el-input
                v-model="editForm.imp_bs"
                style="width: 200px"
              ></el-input>
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
      ofc_cod: "",
      sub_ofc_cod: [],
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

    saveAsset() {},
  },
};
</script>

<style>
</style>