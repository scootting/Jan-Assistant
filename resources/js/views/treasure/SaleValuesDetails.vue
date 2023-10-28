<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>venta de valores en linea</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="importante" type="error"
                        description="las solicitudes tienen validez de 30 dias calendario, durante ese periodo debe realizar la cancelacion de importe, a traves de la cuenta unica de la universidad"
                        show-icon>
                    </el-alert>
                </p>
                <el-col :span="9">
                    <div class="grid-content bg-purple">
                        <p>datos de la solicitud</p>
                        <el-form ref="form" :model="this.dataRequest" label-width="200px" size="mini">
                            <el-form-item label="numero de solicitud">
                                {{ dataRequest.idc }}
                                <!--
                                <el-input v-model="onlyRequest.idc" disabled></el-input>
                                -->
                            </el-form-item>
                            <el-form-item label="fecha en la que se realizo">
                                {{ dataRequest.fecha }}
                            </el-form-item>
                            <el-form-item label="apellidos y nombres">
                                {{ dataRequest.des_per }}
                            </el-form-item>
                            <el-form-item label="importe a cancelar">
                                {{ dataRequest.importe }}
                            </el-form-item>
                            <el-form-item label="estado">
                                <!--
                <el-input v-model="dataRequest.estado" disabled></el-input>
                -->
                                <el-tag type="" effect="dark">{{ dataRequest.estado }}</el-tag>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="15">
                    <div class="grid-content bg-purple">
                        <p>valores en linea adquiridos</p>
                        <el-table :data="acquired" style="width: 100%" show-summary sum-text="importe total a cancelar">
                            <el-table-column prop="des_val" label="descripcion" width="350"></el-table-column>
                            <el-table-column prop="cantidad" label="cantidad" width="100" align="right"></el-table-column>
                            <el-table-column prop="pre_uni" label="precio" width="100" align="right"></el-table-column>
                            <el-table-column align="right" width="100">
                                <!--
                            <template slot-scope="scope" v-if="scope.row.compuesto === 'U'">
                                <el-button @click="initRemoveValues(scope.$index, scope.row)" type="primary" size="mini" plain>Quitar
                                </el-button>
                            </template>
                    -->
                            </el-table-column>
                        </el-table>
                    </div>
                    <br>
                    <div style="text-align: right; float: right">
                        <el-tag type="success" effect="dark">IMPORTE CANCELADO: {{ total }}</el-tag>
                        <!--
                        <el-button type="primary" size="small" @click="setValuesAcquired()">guardar la solicitud de valores
                            en linea
                        </el-button>
                        -->
                    </div>
                </el-col>
            </el-row>
            <el-row> </el-row>
        </el-card>
    </div>
</template>
  
<script>
import { allowedNodeEnvironmentFlags } from 'process';

export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            id: this.$route.params.id,
            loading: true,
            offered: [],
            acquired: [],
            total: 1.00,
            dataRequest: {},
            onlyRequest:{},
        };
    },
    mounted() {
        this.getDataRequestById();
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        //  *  D3. Obtener la informacion por cada solicitud
        //  * {id: id de la solicitud }
        async getDataRequestById() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataRequestById", {
                    id: app.id,
                });
                app.loading = false;
                console.log(response);
                app.dataRequest = response.data.data;
                app.boucherRequest = response.data.boucher;
                app.dataRequest = response.data.data[0];
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-row {
    margin-bottom: 20px;
}

.el-col {
    border-radius: 4px;
}

.bg-purple-dark {
    background: #99a9bf;
}

.bg-purple {
    background: #d3dce6;
}

.bg-purple-light {
    background: #e5e9f2;
}

.grid-content {
    border-radius: 4px;
    padding: 15px;
    min-height: 36px;
}

.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}
</style>
  