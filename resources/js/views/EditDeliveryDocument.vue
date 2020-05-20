<template>
  <div>
    <el-card class="box-card bg-purple">
      <div slot="header" class="clearfix">
        <span>Documento Editado</span>
      </div>
      <div>
        <el-form :model="encargadoForm" ref="encargadoForm" label-width="120px">
          <el-row>
            <el-col :span="6">
              <div>
                <el-form-item label="N°" prop="id">
                  <el-input v-model="encargadoForm.id"></el-input>
                </el-form-item>
              </div>
            </el-col>
            <el-col :span="8">
              <div>
                <el-form-item label="Fecha" prop="fecha">
                  <el-col :span="11">
                    <el-form-item prop="fecha_doc">
                      <el-date-picker
                        type="date"
                        placeholder="Pick a date"
                        v-model="encargadoForm.fecha_doc"
                        style="width: 100%;"
                      ></el-date-picker>
                    </el-form-item>
                  </el-col>
                </el-form-item>
              </div>
            </el-col>
            <el-col :span="6">
              <el-form-item label="Estado" prop="estado">
                <el-input v-model="encargadoForm.estado"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="8">
              <el-form-item label="Con Destino" required>
                <el-input v-model="encargadoForm.dest"></el-input>
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
        <!--Aqui acaba el formulario del responsable -->
        <!--Aqui empezamos a crear las tablas -->
        <el-table :data="tableData" height="250" style="width: 100%" class="bg-purple">
          <el-table-column prop="cantidad" label="Cantidad" width="70"></el-table-column>
          <el-table-column prop="uni_med" label="Uni.Med." width="70"></el-table-column>
          <el-table-column prop="descripcion" label="Descripción" width="120"></el-table-column>
          <el-table-column prop="des_det" label="Descripcion Detallada" width="120"></el-table-column>
          <el-table-column prop="id_partida" label="ID Partida" width="80"></el-table-column>
          <el-table-column prop="id_contable" label="ID Contable" width="80"></el-table-column>
          <el-table-column prop="vida_util" label="Vida Util" width="40"></el-table-column>
          <el-table-column prop="pre_uni" label="Precio Unitario" width="50"></el-table-column>
          <el-table-column prop="importe" label="Importe" width="50"></el-table-column>
          <el-table-column prop="proveedor" label="Proveedor" width="80"></el-table-column>
          <el-table-column prop="nro_fac" label="N° Factura" width="60"></el-table-column>
          <el-table-column prop="tipo_adq" label="Tipo Adquisición" width="60"></el-table-column>
          <el-table-column prop="id" label="Id" width="40"></el-table-column>
          <el-table-column fixed="left" label=" " width="50">
            <template slot-scope="scope">
              <el-button type="text" size="small">Quitar</el-button>
              <br />
              <el-button type="text" size="small" @click.native="editActive(scope.row)">Editar</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-card>
    <!--Aqui acaba el formulario general XD yey -->
    <!--Aqui inicia el formulario para el Activo se llama con formEdit :v -->
    <el-dialog title="Detalle de Entrega de Activo" :visible.sync="formEdit" width="60%" center>
      <el-form :model="editForm" ref="editForm" label-width="150px">
        <el-row>
          <el-col :span="11">
            <el-form-item label="Proveedor" prop="proveedor">
              <el-input v-model="editForm.proveedor"></el-input>
              <el-input v-model="editForm.prov" placeholder="buscar"></el-input>
            </el-form-item>
            <el-form-item label="Uni. Med" prop="uni_med">
              <el-input v-model="editForm.uni_med"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="11">
            <el-form-item label="descripcion" prop="descripcion" width="180px">
              <el-input type="textarea" v-model="editForm.descripcion"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="11">
            <el-form-item label="Cantidad" prop="cantidad">
              <el-input v-model="editForm.cantidad"></el-input>
            </el-form-item>
            <el-form-item label="Partida" required>
              <el-input v-model="editForm.id_partida"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="11">
            <el-form-item label="Grupo Contable" required>
              <el-input v-model="editForm.id_contable"></el-input>
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
              <el-input type="textarea" v-model="editForm.des_det"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button type="primary">Aceptar</el-button>
          <el-button>Cancelar</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "ActivosFijos :3",
  data() {
    return {
      info: {},
      tableData: [],
      formEdit: false,
      data: {},
      encargadoForm: {
        id: this.$store.state.encargado.nro_doc,
        fecha_doc: this.$store.state.encargado.fecha_doc,
        estado: "",
        responsable: this.$store.state.encargado.responsable,
        cargores: ""
      },
      editForm: {
        cantidad: "",
        descripcion: "",
        det_des: "",
        uni_med: "",
        id_partida: "",
        id_contable: "",
        vida_util: "",
        pre_uni: "",
        importe: "",
        proveedor: "",
        nro_fac: "",
        t_adqui: "",
        id: ""
      }
    };
  },
  mounted() {
    console.log("mensaje de recuperacion de datos desde DeliveryDocuments: ", this.$store.state.encargado);
  },
  created() {
    var app = this;
    axios
      .post("/api/editDocument", {
        nro_doc: app.encargadoForm.id
      })
      .then(response => {
        app.info = response.data;
        app.tableData = app.info.listActivos;
        app.encargadoForm.cargores = app.info.encargado.cargores;
        app.encargadoForm.estado = app.info.encargado.estado;
        console.log("info prueba 4:", response.data);
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
    editActive(activo) {
      console.log("activo: ", activo);
      this.formEdit = true;
      var atrib = Object.keys(this.editForm);
      for (let i = 0; i < atrib.length; i++) {
        this.editForm[atrib[i]] = activo[atrib[i]];
      }
      console.log("editForm: ", this.editForm);
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>