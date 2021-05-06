<template>
  <div style="margin-top: 15px 0" class="grid-content bg-purple">
    <el-card class="box-card">
      <el-form
        label-width="190px"
        :model="newActive"
        ref="newActive"
        :inline="false"
        size="normal"
        class="demo-form-inline"
      >
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item size="mini" label="CI Responsable:">
              <el-input
                v-model="newActive.ci_resp"
                size="mini"
                type="text"
                class="input-with-select"
                ><el-button
                  slot="append"
                  icon="el-icon-search"
                  @click="getEncargados(newActive.ci_resp)"
                ></el-button
              ></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Cargo:" prop="car_cod">
              <el-select
                v-model="newActive.car_cod"
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
                v-model="newActive.cod_soa"
                filterable
                remote
                :remote-method="getUnidades"
                placeholder="Seleccione una unidad"
                @change="getSubUnidades(newActive.ofc_cod)"
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
              <el-input v-model="newActive.vida_util"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item size="mini" label="SubUnidad:" prop="sub_ofc_cod">
              <el-select
                v-model="newActive.sub_ofc_cod"
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
                v-model="newActive.estado"
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
                v-model="newActive.des"
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
              <DescripcionDetalle v-model="newActive.des_det" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button size="mini" type="primary" plain @click="saveAsset"
            >Registrar activo</el-button
          >
          <el-button size="mini" type="primary" plain @click="selectActiveQr"
            >Obtener QR</el-button
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
import DescripcionDetalle from "./components/DescripcionDetalle";
export default {
  name: "EditActive",
  components: {
    DescripcionDetalle,
  },
  data() {
    return {
      gestion: this.$store.state.user.gestion,
      estados: [],
      unidades: [],
      subUnidades: [],
      cargos: [],
      searchEncargado: {},
      exampleDes: "des 1|des 2|des 3",
      newActive: {
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
  },
  computed: {
    getNombre() {
      return this.newActive.nombres + " " + this.newActive.paterno + " " + this.newActive.materno;
    },
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    selectActiveQr() {
      this.$notify.info({
        title: "QR emitido",
        message: "boton de prueba",
        duration: 0,
      });
      this.$router.push({
        name: "active",
      });
    },
    Exit() {
      this.$notify.info({
        title: "Creación de activo cancelado",
        message: "No se registro ningún activo nuevo",
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
          this.newActive.sub_ofc_cod = null;
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
          this.newActive.nombres = this.searchEncargado[0].nombres;
          this.newActive.paterno = this.searchEncargado[0].paterno;
          this.newActive.materno = this.searchEncargado[0].materno;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    saveAsset() {
      axios
        .post("/api/reasignacion/save", this.newActive)
        .then((data) => {
          this.$notify.success({
            title: "Activo registrado exitosamente!",
            message: "Se realizó el registro del al Activo exitosamente",
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