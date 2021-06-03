<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Lista de activos para la realizacion del Inventario</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >Ayuda</el-button
        >
      </div>
      <div>
        <el-form
          v-if="doc_inv"
          :model="doc_inv"
          label-width="160px"
          :inline="false"
          size="mini"
        >
          <el-form-item label="Oficina:">
            <el-input
              v-model="doc_inv.oficina.descripcion"
              style="width: 200px"
              disabled
            ></el-input>
          </el-form-item>
          <el-form-item label="Sub Oficinas:">
            <el-tag
              v-for="sub_oficina in doc_inv.sub_oficinas"
              :key="sub_oficina.id"
              type="default"
              size="normal"
              effect="dark"
              >{{ sub_oficina.descripcion }}</el-tag
            >
          </el-form-item>
          <el-form-item label="Encargados de inventario:">
            <el-tag
              v-for="encargado in doc_inv.encargados"
              :key="encargado.nro_dip"
              type="default"
              size="normal"
              effect="dark"
              >{{ formatResponsable(encargado).responsable }}</el-tag
            >
          </el-form-item>
        </el-form>
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getActivesSearch"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getActivesSearch"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="Identificador" width="130">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="small">{{ scope.row.cod_ant }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="des"
            label="DESCRIPCION"
            width="300"
          ></el-table-column>
          <el-table-column label="ESTADO" width="150">
            <el-select
              slot-scope="scope"
              v-model="data[scope.$index].detalle_doc_act.est_act"
              value-key="desc"
              placeholder="desterminar estado"
            >
              <el-option
                v-for="item in estados"
                :key="item.id"
                :label="item.desc"
                :value="item.id"
              >
              </el-option>
            </el-select>
          </el-table-column>
          <el-table-column label="OBSERVACIONES" width="250">
            <input
              type="text"
              slot-scope="scope"
              v-model="data[scope.$index].detalle_doc_act.obs_est"
              style="width: 200px"
            />
          </el-table-column>
          <el-table-column label="VALIDACION" width="180">
            <template slot-scope="scope">
              <el-checkbox
                v-model="data[scope.$index].detalle_doc_act.validacion"
                label="Verificado"
              ></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="200" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                plain
                type="primary"
                size="mini"
                @click="saveActiveInDetail(scope.$index)"
                >Guardar</el-button
              >
              <el-button
                plain
                type="primary"
                size="mini"
                @click="cargarImagen(scope.$index, scope.row)"
                >Imagen</el-button
              ></template
            >
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getActivesPaginate"
        ></el-pagination>
        <div>
          <el-button
            style="margin: 10px; text-align: right; float: right"
            type="primary"
            size="small"
            @click="returnPage2"
            :disabled="verificado"
            >VERIFICAR</el-button
          >
          <el-button
            style="margin: 10px; text-align: right; float: right"
            type="danger"
            size="small"
            @click="returnPage"
            >ATRAS</el-button
          >
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "DocumentoInventarioDetalle",
  data() {
    return {
      writtenTextParameter: "",
      estados: [],
      verificado: false,
      checked: true,
      doc_inv_no_cod: null,
      doc_inv: null,
      loading: false,
      user: this.$store.state.user,
      messages: {},
      data: [],
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    this.doc_inv_no_cod = this.$route.params.no_cod;
    this.getDocInventory();
    this.getEstados();
  },

  methods: {
    getDocInventory() {
      axios
        .get("/api/inventory2/doc_inv/" + this.doc_inv_no_cod)
        .then((data) => {
          this.doc_inv = data.data;
          this.getActivesSearch();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    whenDontHaveDocDetail() {
      return {
        est_act: 1,
        validacion: false,
      };
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
    getActivesSearch() {
      axios
        .get("/api/inventory2/search/" + this.doc_inv.no_cod, {
          params: {
            page: this.pagination.page,
            idOffice: this.doc_inv.oficina.id,
            idSubOffices: this.doc_inv.sub_ofc_cod,
            keyWord: this.writtenTextParameter.toUpperCase(),
          },
        })
        .then((data) => {
          this.loading = false;
          var info = Object.values(data.data.data);
          this.data = info.map((a) => {
            if (!a.detalle_doc_act)
              a.detalle_doc_act = this.whenDontHaveDocDetail();
            a.detalle_doc_act.id_act = a.id;
            a.detalle_doc_act.id_des = a.esquema;
            a.detalle_doc_act.doc_cod = this.doc_inv.no_cod;
            a.detalle_doc_act.cod_act = this.doc_inv.cod_nue;

            return a;
          });
          this.pagination = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getActivesPaginate(page) {
      this.pagination.page = page;
      this.getActivesSearch();
    },
    test() {
      alert("bienvenido al modulo");
    },
    formatResponsable(responsable) {
      return {
        responsable:
          responsable.paterno.trim() +
          " " +
          responsable.materno.trim() +
          " " +
          responsable.nombres.trim(),
        nro_dip: responsable.nro_dip,
      };
    },
    change(checked) {
      this.checked = true;
    },
    saveActiveInDetail(index) {
      {
        axios
          .post("/api/inventory2/saveActive", {
            ...this.data[index].detalle_doc_act,
            cod_ges: this.user.gestion,
            cod_act: this.data[index].cod_ant
          })
          .then((data) => {
            this.$notify.success({
              title: "Cambios guardados",
              message:
                "Se realizo cambios al Documento de inventario seleccionado exitosamente",
              duration: 0,
            });
            this.getActivesSearch();
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    cargarImagen(index, row) {
      this.$router.push({
        name: "imgdetail",
        params: {
          id: row.id,
        },
      });
    },
    getActives() {
      this.$notify.info({
        title: "Prueba de boton",
        message: "prueba de boton",
        duration: 0,
      });
    },
    returnPage2() {
      this.$notify.info({
        title: "Return",
        message: "prueba de boton de verificado",
        duration: 0,
      });
      (this.verificado = true),
        this.$router.push({
          name: "inventory2",
        });
    },
    returnPage() {
      this.$notify.info({
        title: "Edicion cancelada",
        message: "prueba de boton",
        duration: 0,
      });
      this.$router.push({
        name: "inventory2",
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.el-tag {
  margin: 2px;
}
</style>