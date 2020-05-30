<template>
  <div>
    <el-card class="box-card bg-purple">
      <div slot="header" class="clearfix">
        <span>Documento Editado</span>
      </div>
      <div>
        <el-tabs :tab-position="tabPosition" style="height: 425px;">
          <el-tab-pane label="Detalles de Encargado">
            <el-form :model="encargadoForm" ref="encargadoForm" label-width="120px">
              <el-row>
                <el-col :span="6">
                  <div>
                    <el-form-item label="N°" prop="nro_doc">
                      <el-input v-model="encargadoForm.nro_doc" disabled></el-input>
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="8">
                  <div>
                    <el-form-item label="Fecha" prop="fecha">
                      <el-col :span="11">
                        <el-form-item prop="fecha">
                          <el-date-picker
                            type="date"
                            placeholder="Pick a date"
                            v-model="encargadoForm.fecha"
                            style="width: 100%;"
                          ></el-date-picker>
                        </el-form-item>
                      </el-col>
                    </el-form-item>
                  </div>
                </el-col>
                <el-col :span="6">
                  <el-form-item label="Estado" prop="estado">
                    <el-input v-model="encargadoForm.estado" disabled></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="8">
                  <el-form-item label="Con Destino" required>
                    <el-input v-model="encargadoForm.de"></el-input>
                    <el-select v-model="encargadoForm.a">
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
                  <el-form-item label="Responsable" prop="responsable" required>
                    <el-input v-model="encargadoForm.responsable"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="8">
                  <el-form-item label="Cargo Responsable" prop="cargores" required>
                    <el-input v-model="encargadoForm.cargores"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-button>Guardar/Actualizar</el-button>
            </el-form>
          </el-tab-pane>
          <!--Aqui acaba el formulario del responsable -->
          <!--Aqui empezamos a crear las tablas -->
          <el-tab-pane label="Tabla">
            <el-table :data="tableData" height="400" style="width:100%" class="bg-purple">
              <el-table-column prop="cantidad" label="Cantidad" width="70"></el-table-column>
              <el-table-column prop="uni_med" label="Uni.Med." width="70"></el-table-column>
              <el-table-column prop="descripcion" label="Descripción" width="110"></el-table-column>
              <el-table-column prop="des_det" label="Descripcion Detallada" width="95"></el-table-column>
              <el-table-column prop="id_partida" label="ID Partida" width="80"></el-table-column>
              <el-table-column prop="id_contable" label="ID Contable" width="85"></el-table-column>
              <el-table-column prop="vida_util" label="Vida Util" width="50"></el-table-column>
              <el-table-column prop="pre_uni" label="Precio Unitario" width="65"></el-table-column>
              <el-table-column prop="importe" label="Importe" width="80"></el-table-column>
              <el-table-column prop="proveedor" label="Proveedor" width="80"></el-table-column>
              <el-table-column prop="nro_fac" label="N° Factura" width="80"></el-table-column>
              <el-table-column prop="tipo_adq" label="Tipo Adquisición" width="120"></el-table-column>
              <el-table-column prop="id" label="Id" width="35"></el-table-column>
              <el-table-column fixed="left" label=" " width="50">
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
          <el-col :span="11">
            <el-form-item label="Proveedor" prop="proveedor">
              <el-input v-model="editForm.proveedor"></el-input>
              <el-input v-model="editForm.prov" placeholder="buscar"></el-input>
            </el-form-item>
            <el-form-item label="Uni.Med." prop="uni_med">
              <el-select v-model="editForm.uni_med">
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
              <el-input type="textarea" v-model="editForm.descripcion" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="11">
            <el-form-item label="Cantidad" prop="cantidad">
              <el-input v-model="editForm.cantidad"></el-input>
            </el-form-item>
            <el-form-item label="Partida" required>
              <el-select v-model="editForm.id_partida">
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
              <el-select v-model="editForm.id_contable">
                <el-option
                  v-for="item in contGroups"
                  :key="'group_contable_key_'+item.con_cod"
                  :label="optionsLabelDestiny(item.con_cod,item.con_des)"
                  :value="item.con_cod"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Vida Util" required>
              <el-input v-model="editForm.vida_util"></el-input>
            </el-form-item>
            <el-form-item label="Precion Unitario" required>
              <el-input v-model="editForm.pre_uni"></el-input>
            </el-form-item>
            <el-form-item label="Nro Factura" prop="nro_fac">
              <el-input v-model="editForm.nro_fac"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item label="Descripcion Detallada" prop="descripcion">
              <el-input type="textarea" v-model="editForm.des_det" rows="5" max-rows="8"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button type="primary" @click="saveAsset">Aceptar</el-button>
          <el-button>Cancelar</el-button>
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
        de: "",
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
  created() {
    var app = this;
    axios
      .post("/api/delivery_documents/edit", {
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
        app.destiny = app.info.destinos;
        app.partidas = app.info.partidas;
        app.contGroups = app.info.gruposC;
        app.unidMeds = app.info.unidMeds;
        console.log("info prueba 5:", response.data);
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
      console.log("editForm: ", this.editForm);
    },
    cleanEditForm() {
      var atrib = Object.keys(this.editForm);
      for (let i = 0; i < atrib.length; i++) this.editForm[atrib[i]] = "";
    },
    saveAsset() {
      var app = this
      axios
        .post("/api/delivery_documents/store", this.editForm)
        .then(response => {
          //console.log(response.data);
          app.cleanEditForm();
          app.formEdit= false;
          this.$notify.success({
            title: "Guardado",
            message: response.data[0]
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