<template>
  <div>
    <el-card class="box-card bg-purple">
      <div slot="header" class="clearfix">
        <span>Nuevo Documento de Donacion</span>
      </div>
      <div>
        <el-tabs v-model="selectedTabPane" :tab-position="tabPosition" style="height: 425px;">
          <el-tab-pane label="Detalles de Encargado" name='encargado'>
            <el-form :model="encargadoForm" ref="encargadoForm" label-width="120px">
              <el-row>
                <el-col :span="6" size="small">
                  <div>
                    <el-form-item size="small" label="N°" prop="nro_doc">
                      <el-input size="small" v-model="encargadoForm.nro_doc" disabled></el-input>
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="8" size="small">
                  <div>
                    <el-form-item size="small" label="Fecha" prop="fecha">
                      <el-col :span="16">
                        <el-form-item size="small" prop="fecha">
                          <el-date-picker
                            type="date"
                            placeholder="Pick a date"
                            v-model="encargadoForm.fecha_doc"
                            format="dd/MM/yyyy"
                            value-format="dd/MM/yyyy"
                            :default-value="new Date()"
                            style="width: 100%;"
                          ></el-date-picker>
                        </el-form-item>
                      </el-col>
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="6">
                  <el-form-item size="small" label="Estado" prop="estado">
                    <el-input size="small" v-model="encargadoForm.estado" disabled></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="8">
                  <el-form-item size="small" label="Con Destino" required>
                    <el-select size="small" v-model="encargadoForm.a">
                      <el-option
                        v-for="item in destiny"
                        :key="'oficina_key_'+item.id_oficina"
                        :label="optionsLabelDestiny(item.id_oficina,item.ofc_des)"
                        :value="item.id_oficina"
                      ></el-option>
                    </el-select>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="8">
                  <el-form-item size="small" label="Responsable" prop="responsable" required>
                    <el-input size="small" v-model="encargadoForm.responsable"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="8">
                  <el-form-item size="small" label="Cargo Responsable" prop="cargores" required>
                    <el-input size="small" v-model="encargadoForm.cargores"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-button @click="saveEncargado">Guardar/Actualizar</el-button>
              <el-button @click="unlockTab">Siguiente</el-button>
            </el-form>
          </el-tab-pane>
          <!--Aqui acaba el formulario del responsable -->
          <!--Aqui empezamos a crear las tablas -->
          <el-tab-pane label="Tabla" :disabled="Siguiente" name='tabla'>
             <el-button @click="openNewForm">Nuevo</el-button>
            <el-table :data="tableData" height="400" style="width:100%" class="bg-purple">
              <el-table-column prop="cantidad" label="Cantidad" width="100"></el-table-column>
              <el-table-column prop="uni_med" label="Uni.Med." width="100"></el-table-column>
              <el-table-column prop="descripcion" label="Descripción" width="200"></el-table-column>
              <el-table-column prop="pre_uni" label="Precio Unitario" width="90"></el-table-column>
              <el-table-column prop="importe" label="Importe" width="90"></el-table-column>
              <el-table-column prop="proveedor" label="Proveedor" width="110"></el-table-column>
              <el-table-column prop="tipo_adq" label="Tipo Adquisición" width="120"></el-table-column>
              <el-table-column fixed="left" label=" " width="70">
                <template slot-scope="scope">
                  <el-button type="text" size="small">Quitar</el-button>
                  <br />
                  <el-button type="text" size="small" @click.native="editActive(scope.row)">Editar</el-button>
                </template>
              </el-table-column>
            </el-table>
           
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-card>
    <!--Aqui acaba el formulario general XD yey -->
    <!--Aqui inicia el formulario para el Activo se llama con showFormEdit :v -->
    <el-dialog title="Detalle de Entrega de Activo" :visible.sync="showFormEdit" width="65%" center>
      <el-form :model="assetForm" ref="assetForm" label-width="150px">
        <el-row>
          <el-col :span="11">
            <el-form-item label="Proveedor" prop="proveedor">
              <el-input v-model="assetForm.proveedor"></el-input>
              <el-input v-model="assetForm.prov" placeholder="buscar"></el-input>
            </el-form-item>
            <el-form-item label="Uni.Med." prop="uni_med">
              <el-select v-model="assetForm.uni_med">
                <el-option
                  v-for="item in unidMeds"
                  :key="'u_m_key_'+item.id_uni_med"
                  :label="optionsLabelDestiny(item.uni_des_cor,item.uni_des_det)"
                  :value="item.uni_des_cor"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item label="descripcion" prop="descripcion" width="180px">
              <el-input type="textarea" v-model="assetForm.descripcion" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="11">
            <el-form-item label="Cantidad" prop="cantidad">
              <el-input v-model="assetForm.cantidad"></el-input>
            </el-form-item>
            <el-form-item label="Partida" required>
              <el-select v-model="assetForm.id_partida">
                <el-option
                  v-for="item in partidas"
                  :key="'partida_key_'+item.par_cod"
                  :label="optionsLabelDestiny(item.par_cod,item.par_des)"
                  :value="item.par_cod"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="11">
            <el-form-item label="Grupo Contable" required>
              <el-select v-model="assetForm.id_contable">
                <el-option
                  v-for="item in contGroups"
                  :key="'group_contable_key_'+item.con_cod"
                  :label="optionsLabelDestiny(item.con_cod,item.con_des)"
                  :value="item.con_cod"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Vida Util" required>
              <el-input v-model="assetForm.vida_util"></el-input>
            </el-form-item>
            <el-form-item label="Precion Unitario" required>
              <el-input v-model="assetForm.pre_uni"></el-input>
            </el-form-item>
            <el-form-item label="Nro Factura" prop="nro_fac">
              <el-input v-model="assetForm.nro_fac"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item label="Descripcion Detallada" prop="descripcion">
              <el-input type="textarea" v-model="assetForm.des_det" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button type="primary" @click="saveAsset">Aceptar</el-button>
          <el-button type="danger" @click="Exit">Cancelar</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "NewDocuments",
  data() {
    return {
      selectedTabPane: 'encargado',
      gestion: this.$store.state.user.gestion,
      info: {},
      tableData: [],
      showFormEdit: false,
      Siguiente: true,
      data: {},
      tabPosition: "Bottom",
      encargadoForm: {
        nro_doc: "NUEVO",
        fecha_doc:
          new Date().getDay() +
          "/" +
          (new Date().getMonth()+1) +
          "/" +
          new Date().getFullYear(),
        estado: "Solicitado",
        a: "",
        responsable: "",
        cargores: "",
        gestion: this.$store.state.user.gestion
      },
      destiny: {},
      assetForm: {
        cantidad: "",
        descripcion: "",
        des_det: "",
        uni_med: "",
        id_partida: "",
        id_contable: "",
        vida_util: "",
        pre_uni: "",
        importe: "",
        proveedor: "",
        nro_fac: "",
        id: ""
      },
      unidMeds: [],
      contGroups: [],
      partidas: []
    };
  },
  created() {
    var app = this;
    axios
      .get("/api/donationDocuments/getRecursos/" + this.gestion)
      .then(response => {
        app.destiny = response.data.destinos;
        app.partidas = response.data.partidas;
        app.contGroups = response.data.gruposC;
        app.unidMeds = response.data.unidMeds;
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
    optionsLabelDestiny(id, desc) {
      return id + " " + desc;
    },
    editActive(activo) {
      console.log("activo: ", activo);
      this.showFormEdit = true;
      var atrib = Object.keys(this.assetForm);
      for (let i = 0; i < atrib.length; i++) {
        this.assetForm[atrib[i]] = activo[atrib[i]];
      }
      console.log("assetForm: ", this.assetForm);
    },
    cleanassetForm() {
      var atrib = Object.keys(this.assetForm);
      for (let i = 0; i < atrib.length; i++) this.assetForm[atrib[i]] = "";
    },
    saveEncargado() {
      var app = this;
      axios
        .post("/api/donationDocuments/encargado/add", {
          data: app.encargadoForm
        })
        .then(response => {
          app.encargadoForm.nro_doc = response.data.nro_doc;
          console.log("info de respuesta: ", response.data);
        })
        .catch(error => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message
          });  
        });
    },
    unlockTab() {
      this.Siguiente = false
      this.selectedTabPane='tabla'
    },
    openNewForm(){
      this.cleanassetForm()
      this.showFormEdit= true
    },
    saveAsset(){
      console.log('enviando info a server')
      var app = this;
      axios
        .post("/api/donationDocuments/asset/add", {
          data: app.assetForm
        })
        .then(response => {
          this.$notify.success({
            title: "el activo se guardó",
            message:response.data.msn
          });
        })
        .catch(error => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message
          });
        });
    },
     Exit() {
      this.showFormEdit = false;
      this.$notify.info({
        title: "Nuevo activo cancelado",
        message: "No se hizo la creacion de activo",
        
      });
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>