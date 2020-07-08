<template>
  <div>
    <el-card class="box-card bg-purple">
      <div slot="header" class="clearfix">
        <span>Documento Editado</span>
      </div>
      <div>
        <el-tabs :tab-position="tabPosition" style="height:425px;">
          <el-tab-pane label="Detalles de Encargado">
            <el-form :model="encargadoForm" ref="encargadoForm" label-width="150px">
              <el-row>
                <el-col :span="6" size="small">
                  <div>
                    <el-form-item size="small" label="N°" prop="nro_doc">
                      <el-input size="small" v-model="encargadoForm.nro_doc" disabled></el-input>
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="8">
                  <el-form-item size="small" label="Fecha" prop="fecha">
                    <el-date-picker
                      disabled
                      type="date"
                      placeholder="Pick a date"
                      v-model="encargadoForm.fecha"
                      style="width: 100%;"
                    ></el-date-picker>
                  </el-form-item>
                </el-col>
                <el-col :span="6">
                  <el-form-item size="small" label="Estado" prop="estado">
                    <el-input size="small" v-model="encargadoForm.estado" disabled></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <hr style="color:light gray;" />
              <br />
              <el-row>
                <el-col :span="10">
                  <el-form-item size="small" label="Responsable" prop="responsable" required>
                    <el-input size="small" v-model="encargadoForm.responsable"></el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="10">
                  <el-form-item size="small" label="Cargo Responsable" prop="cargores" required>
                    <el-input size="small" v-model="encargadoForm.cargores"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="12">
                  <el-form-item size="small" label="Desde: ">
                    <el-input size="small" v-model="encargadoForm.ofc_des" disabled></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="12">
                  <el-form-item label="Con destino: ">
                    <el-select :span="12" size="small" v-model="encargadoForm.a">
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
              <el-button :disabled="(encargadoForm.estado.toLowerCase() === 'verificado')">Guardar/Actualizar</el-button>
            </el-form>
          </el-tab-pane>
          <!--Aqui acaba el formulario del responsable -->
          <!--Aqui empezamos a crear las tablas -->
          <el-tab-pane label="Tabla">
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
    <!--Aqui inicia el formulario para el Activo se llama con formEdit :v -->
    <el-dialog title="Detalle de Entrega de Activo" :visible.sync="formEdit" width="65%" center>
      <el-form :model="editForm" ref="editForm" label-width="150px">
        <el-row>
          <el-col :span="11" center>
            <el-form-item size="small" label="Proveedor" prop="proveedor">
              <el-input v-model="editForm.proveedor"></el-input>
              <br />
              <br />
              <el-input v-model="editForm.prov" placeholder="buscar"></el-input>
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
              <el-input v-model="editForm.cantidad"></el-input>
            </el-form-item>
            <el-form-item size="small" label="Partida" required>
              <el-select v-model="editForm.id_partida">
                <el-option
                  v-for="item in partidas"
                  :key="'partida_key_'+item.par_cod"
                  :label="item.par_des"
                  :value="item.par_cod"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <hr style="color:gray;" />
        <br />
        <el-row>
          <el-col :span="11">
            <el-form-item size="small" label="Grupo Contable" required>
              <el-select v-model="editForm.id_contable">
                <el-option
                  v-for="item in contGroups"
                  :key="'group_contable_key_'+item.con_cod"
                  :label="item.con_des"
                  :value="item.con_cod"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item size="small" label="Vida Util" required>
              <el-input v-model="editForm.vida_util"></el-input>
            </el-form-item>
            <el-form-item size="small" label="Precion Unitario" required>
              <el-input v-model="editForm.pre_uni"></el-input>
            </el-form-item>
            <el-form-item size="small" label="Nro Factura" prop="nro_fac">
              <el-input v-model="editForm.nro_fac"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item size="small" label="Descripcion Detallada" prop="descripcion">
              <el-input type="textarea" v-model="editForm.des_det" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item size="small" label="descripcion" prop="descripcion" width="180px">
              <el-input type="textarea" v-model="editForm.descripcion" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button type="primary" plain @click="saveAsset">Aceptar</el-button>
          <el-button type="danger" plain @click="Exit">Cancelar</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "EditDocuments",
  data() {
    return {
      gestion: this.$store.state.user.gestion,
      info: {},
      tableData: [],
      formEdit: false,
      
      data: {},
      tabPosition: "Bottom",
      encargadoForm: {
        nro_doc: "",
        fecha_doc: "",
        estado: "",
        ofc_des: "",
        a: "",
        responsable: "",
        cargores: ""
      },
      destiny: {},
      editForm: {
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
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde DeliveryDocuments: ",
      this.$store.state.encargado
    );
  },
  created() {  //created vs mounted
    var app = this;
    axios
      .post("/api/deliveryDocuments/edit", {
        nro_doc: this.$store.state.encargado.nro_doc,
        gestion: this.$store.state.user.gestion
      })
      .then(response => {
        app.info = response.data;
        app.tableData = app.info.listActivos;
        app.encargadoForm = Object.assign(
          app.encargadoForm,
          app.info.encargado
        );
      })
      .catch(error => {
        this.error = error;
        this.$notify.error({
          title: "Error",
          message: this.error.message
        });
      });
    axios
      .get("/api/deliveryDocuments/getRecursos/" + this.gestion)
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
      this.formEdit = true;
      var atrib = Object.keys(this.editForm);
      for (let i = 0; i < atrib.length; i++) {
        this.editForm[atrib[i]] = activo[atrib[i]];
      }
      //console.log("editForm: ", this.editForm);
    },
    noVerificate(){
    },
    cleanEditForm() {
      var atrib = Object.keys(this.editForm);
      for (let i = 0; i < atrib.length; i++) this.editForm[atrib[i]] = "";
    },
    Exit() {
      this.formEdit = false;
      this.$notify.info({
        title: "Edicion cancelada",
        message: "No se hizo cambios al Documento de compra",
        duration: 0
      });
    },
    saveAsset() {
      var app = this;
      axios
        .post("/api/deliveryDocuments/store", this.editForm)
        .then(response => {
          //console.log(response.data);
          app.cleanEditForm();
          app.formEdit = false;
          this.$notify.success({
            title: "Guardado",
            message: response.data[0]
          });
          axios
            .post("/api/deliveryDocuments/edit", {
              nro_doc: this.$store.state.encargado.nro_doc,
              gestion: this.$store.state.user.gestion
            })
            .then(response => {
              app.info = response.data;
              app.tableData = app.info.listActivos;
              app.encargadoForm = Object.assign(
                app.encargadoForm,
                app.info.encargado
              );
            })
            .catch(error => {
              this.error = error;
              this.$notify.error({
                title: "Error",
                message: this.error.message
              });
            });
        })
        .catch(error => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message
          });
        });
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>