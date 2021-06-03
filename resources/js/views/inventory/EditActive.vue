<template>
  <div style="margin-top: 15px 0" class="grid-content bg-purple">
    <el-card class="box-card">
      <el-form
        label-width="190px"
        :model="editForm"
        ref="editForm"
        :inline="false"
        size="normal"
        class="demo-form-inline"
      >
        <span> Activo: {{ editForm.id }} </span> <br /><br />
        <hr style="color: gray" />
        <br />
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item size="mini" label="CI Responsable:">
              <el-input
                v-model="editForm.ci_resp"
                size="mini"
                type="text"
                class="input-with-select"
                ><el-button
                  slot="append"
                  icon="el-icon-search"
                  @click="getEncargados(editForm.ci_resp)"
                ></el-button
              ></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Cargo:" prop="car_cod">
              <el-select
                v-model="editForm.car_cod"
                value-key="id"
                placeholder="Determinar Cargo"
              >
                <el-option
                  v-for="item in cargos"
                  :key="item.id"
                  :label="item.descripcion"
                  :value="item.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item size="mini" label="NOMBRE:">
              <el-input
                :value="getNombre"
                placeholder=""
                size="mini"
                readonly
                style="width: 100%"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row type="flex" justify="space-between">
          <el-col :span="12">
            <el-form-item size="mini" label="Unidad:" prop="oficina">
              <el-select
                v-model="editForm.cod_soa"
                filterable
                remote
                :remote-method="getUnidades"
                placeholder="Seleccione una unidad"
                @change="getSubUnidades(editForm.ofc_cod)"
              >
                <el-option
                  v-for="item in unidades"
                  :key="item.cod_soa"
                  :label="item.descripcion"
                  :value="item.cod_soa"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Vida Util:">
              <el-input v-model="editForm.vida_util"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item size="mini" label="SubUnidad:" prop="sub_ofc_cod">
              <el-select
                v-model="editForm.sub_ofc_cod"
                placeholder="Seleccione una subunidad"
              >
                <el-option
                  v-for="item in subUnidades"
                  :key="item.id"
                  :label="item.descripcion"
                  :value="item.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Estado de Activo:" prop="estado">
              <el-select
                v-model="editForm.estado"
                value-key="desc"
                placeholder="Determinar estado"
              >
                <el-option
                  v-for="item in estados"
                  :key="item.desc"
                  :label="item.desc"
                  :value="item.desc"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row type="flex" justify="space-between">
          <el-col :span="11">
            <el-form-item size="mini" label="Descripcion:" prop="descripcion">
              <el-input
                type="textarea"
                v-model="editForm.des"
                rows="10"
                max-rows="10"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item
              size="mini"
              label="Descripcion Detallada:"
              prop="descripcion"
            >
              <DescripcionDetalle v-model="editForm.des_det" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button size="mini" type="primary" :disabled="guardado" plain @click="saveAsset"
            >Guardar Cambios</el-button
          >
          <el-button size="mini" type="primary" :disabled="!guardado" plain @click="selectActiveQr"
            >Obtener QR</el-button
          >
          <el-button size="mini" type="primary" :disabled="!guardado" plain @click="returnListActives"
            >Atras</el-button
          >
          <el-button size="mini" type="danger" plain @click="Exit"
            >Cancelar</el-button
          >
        </el-form-item>
      </el-form>
    </el-card>
        <el-dialog
      title="QR"
      :visible.sync="showQR"
      width="30%"
      @close="showQR = false"
    >
      <el-row type="flex" justify="center">
        <vue-qr :text="JSON.stringify(activoQR)" :size="400"></vue-qr>
      </el-row>
      <span slot="footer">
        <el-button @click="showQR = false">Cancel</el-button>
        <el-button>Imprimir</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>
<script>
import DescripcionDetalle from "./components/DescripcionDetalle";
import VueQr from "vue-qr";
export default {
  name: "EditActive",
  components: {
    DescripcionDetalle,
    VueQr
  },
  data() {
    return {
      gestion: this.$store.state.user.gestion,
      guardado: false,
      showQR:false,
      activoQR:'Prueva de qr',
      estados: [],
      unidades: [],
      subUnidades: [],
      cargos: [],
      searchEncargado: {},
      exampleDes: "des 1|des 2|des 3",
      editForm: {
        car_cod: 1,
        oficina:"",
        des: "",
        des_det: "",
        vida_util: "",
        estado: "",
        cargo: "",
        ofc_cod: "",
        cod_soa:"",
        sub_ofc_cod: "",
        ci_resp: "",
        id: "",
        nombres: "",
        paterno: "",
        materno: "",
      },
    };
  },
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde re asignacion de activos "
    );
    this.getEstados();
    this.getCargos();
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
          cod_soa: app.editForm.cod_soa,
          descripcion: app.editForm.oficina,
        });
        app.subUnidades.push({
          id: app.editForm.sub_ofc_cod,
          descripcion: app.editForm.descripcion,
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
  computed: {
    getNombre() {
      return this.editForm.nombres + " " + this.editForm.paterno + " " + this.editForm.materno;
    },
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    selectActiveQr(actv) {
      this.activoQR = actv;
      this.showQR = true;
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
    getCargos() {
      axios
        .get("/api/activo/cargos/")
        .then((data) => {
          this.cargos = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getEncargados(nro_dip) {
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchEncargado = Object.values(data.data.data);
          this.editForm.nombres = this.searchEncargado[0].nombres;
          this.editForm.paterno = this.searchEncargado[0].paterno;
          this.editForm.materno = this.searchEncargado[0].materno;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    saveAsset() {
      axios
        .post("/api/reasignacion/save", this.editForm)
        .then((data) => {
          this.guardado = true;
          this.$notify.success({
            title: "Cambios guardados",
            message: "Se realizo cambios al Activo seleccionado exitosamente",
            duration: 0,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    returnListActives(){
           this.$notify.success({
            title: "Cambios guardados",
            message: "Se realizo cambios al Activo seleccionado exitosamente",
            duration: 0,
          });
          this.$router.push({
          name: "active",
          });
    }
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