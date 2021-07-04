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
                disabled
                :value="getNombre"
                placeholder="NOMBRE DE RESPONSABLE DE ACTIVO"
                size="mini"
                readonly
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color: gray" />
        <br />
        <el-row type="flex" justify="space-between">
          <el-col :span="12">
            <el-form-item size="mini" label="Cantidad:" prop="cantidad">
              <el-input-number v-model="newActive.cantidad" size="mini" label="Cantidad"
                :min="1" :max="10000000" :step="1" :controls="true" controls-position="both" >
              </el-input-number>
              
            </el-form-item>
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
          <el-col :span="12">
            <el-form-item size="mini" label="Nro de documento:" prop="nro_doc">
              <el-input
                disabled
                type="text"
                v-model="newActive.nro_doc"
              ></el-input>
            </el-form-item>
            <el-form-item size="mini" label="Seleccionar Partida:" prop="partida">
              <el-select
                v-model="newActive.partida"
                value-key="par_cod"
                placeholder="Determinar Partida"
              >
                <el-option
                  v-for="item in partidas"
                  :key="item.par_cod"
                  :label="item.par_des"
                  :value="item.par_cod"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="mini" label="Seleccionar Partida Contable:" prop="contable">
              <el-select
                v-model="newActive.contable"
                value-key="con_cod"
                placeholder="Determinar Partida Contable"
              >
                <el-option
                  v-for="item in contable"
                  :key="item.con_cod"
                  :label="item.con_des"
                  :value="item.con_cod"
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
  name: "NewActive",
  components: {
    DescripcionDetalle,
  },
  data() {
    return {
      gestion: this.$store.state.user.gestion,
      estados: [],
      partidas:[],
      contable:[],
      unidades: [],
      subUnidades: [],
      cargos: [],
      nro:null,
      searchEncargado: {},
      exampleDes: "des 1|des 2|des 3",
      newActive: {
        nro_doc:'',
        car_cod: "",
        oficina:"",
        des: "",
        des_det: "",
        estado: "",
        partida:"",
        contable:"",
        cantidad:"1",
        cargo: "",
        cod_soa: this.$route.params.soa,
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
      "mensaje de recuperacion de datos desde Crear de activos "
    );
    this.getEstados();
    this.getCargos();
    this.getSubUnidades();
    this.getPartidas();
    this.getContable();
    this.getNumero();
  },
  computed: {
    getNombre() {
      return this.newActive.nombres + " " + this.newActive.paterno + " " + this.newActive.materno;
    },
    getNroDoc(){
      return this.nro + "/98";
    }
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    selectActiveQr() {
      this.$notify.info({
        title: "QR emitido",
        message: "boton de prueba",
        duration: 5000,
      });
    },
    Exit() {
      this.$notify.info({
        title: "Creación de activo cancelado",
        message: "No se registro ningún activo nuevo",
        duration: 3000,
      });
      this.$router.push({
        name: "createactive",
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
    getPartidas() {
      axios
        .get("/api/activo/partidas/")
        .then((data) => {
          this.partidas = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getContable() {
      axios
        .get("/api/activo/contable/")
        .then((data) => {
          this.contable = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getNumero() {
      axios
        .get("/api/activo/nro/")
        .then((data) => {
          this.nro = data.data[0].numero;
          this.newActive.nro_doc = data.data[0].numero +"/98"
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getSubUnidades() {
      this.unidadesLoading = true;
      this.subUnidadesLoading = true;
      let cod_soa = this.$route.params.soa;
      axios
        .get("/api/inventory/sub_offices/"+ this.$route.params.soa)
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
        .post("/api/newactive/save", this.newActive)
        .then((data) => {
          this.$notify.success({
            title: "Activo registrado exitosamente!",
            message: "Se realizó el registro del al Activo exitosamente",
            duration: 3000,
          });
          this.$router.push({
            name: "createactive",
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