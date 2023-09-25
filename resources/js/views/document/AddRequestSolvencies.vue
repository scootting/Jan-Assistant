<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>solicitud de solvencia universitaria</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="importante" type="error"
                        description="antes de realizar la solicitud de solvencia universitaria debe estar seguro que su informacion personal se encuentra actualizada, puede verificarlo en la opcion innformacion personal del menu."
                        show-icon>
                    </el-alert>
                </p>
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <p>lista de solvencias universitarias disponibles</p>
                        <el-row  v-for="(item, index) in dataSolvencies" :key="index">
                            <el-col :span="6">
                                <el-card>
                                    <el-button type="text" icon="el-icon-document" circle
                                        @click.native="initAddInformationAditional(item)"></el-button>
                                    <h4>{{ item.tipo }}</h4>
                                    <p>{{ item.sub }}</p>
                                </el-card>
                            </el-col>
                        </el-row>
                    </div>
                </el-col>
            </el-row>
        </el-card>

        <!-- Form Add Register Input Manual-->
        <el-dialog title="campo para el segundo registro" :visible.sync="dialogFormVisible">
            <el-form :model="aditional" label-width="220px" size="small">
                <el-form-item label="fecha del registro">
                    <el-date-picker type="date" v-model="aditional.fec_tra" placeholder="seleccione una fecha"
                        style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item label="Unidad academica">
                    <el-autocomplete class="inline-input" v-model="aditional.des_prg" :fetch-suggestions="querySearch"
                        style="width: 100%;" placeholder="ingrese:carrera y seleccione..." :trigger-on-focus="false"
                        @select="handleSelect"></el-autocomplete>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="success" size="small" @click="initStoreSolvency">Guardar</el-button>
                <el-button type="warning" size="small" @click="initCancelSolvency">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Register Input Manual-->
        <!-- Form Add Register Input Manual-->
        <el-dialog title="campo para el primer registro" :visible.sync="dialogFormVisible2">
            <el-form :model="aditional" label-width="220px" size="small">
                <el-form-item label="fecha del registro">
                    <el-date-picker type="date" v-model="aditional.fec_tra" placeholder="seleccione una fecha"
                        style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item label="observacion">
                    <el-input type="textarea" v-model="aditional.obs" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="success" size="small" @click="initStoreSolvency">Guardar</el-button>
                <el-button type="warning" size="small" @click="initCancelSolvency2">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Register Input Manual-->
    </div>
</template>
  
<script>
export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            loading: true,
            dataSolvencies: [],
            dataAditional: [],
            dialogFormVisible: false,
            dialogFormVisible2: false,
            dataCareer: [],
            solvency: {},
            aditional: {},
        };
    },
    mounted() {
        this.getTypesOfSolvencies();
        this.getAditionalInformation();
    },
    methods: {
        test() {
            alert("llamar al 74246032 para asistencia tecnica");
        },
        //  * M4. Obtiene la lista de de documentos, por tipo 'MEM' Memoriales, 'SOL' Solvencias 
        async getTypesOfSolvencies() {
            var app = this;
            try {
                let response = await axios.post("/api/getTypesOfDocuments/", {
                    year: app.client.gestion,
                    typea: 'SOL'
                });
                app.loading = false;
                app.dataSolvencies = response.data;
                //console.log(app.dataSolvencies);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        async getAditionalInformation() {
            var app = this;
            try {
                let response = await axios.post("/api/getAditionalInformation/", {
                    year: app.client.gestion,
                });
                app.loading = false;
                app.dataCareer = response.data;
                //console.log(app.dataCareer);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initAddInformationAditional(row) {
            let app = this;
            app.solvency = row;
            switch (row.modal) {
                case 1:
                    app.dialogFormVisible = true;
                    break;
                case 2:
                    app.dialogFormVisible2 = true;
                    break;
                default:
                    break;
            }
        },
        querySearch(queryString, cb) {
            var links = this.dataCareer;
            var results = queryString ? links.filter(this.createFilter(queryString)) : links;
            // call callback function to return suggestions
            cb(results);
        },
        createFilter(queryString) {
            return (link) => {
                return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
            };
        },
        handleSelect(item) {
            //console.log(item);
            this.aditional.cod_prg = item.cod_prg;
            this.aditional.des_prg = item.cat_des;
            console.log('Consola');

            console.log(this.aditional);
        },

        initStoreSolvency() {
            console.log('Store');
            this.dialogFormVisible = false;
            console.log(this.aditional);
            console.log(this.solvency);
            console.log(this.client);
        },
        initCancelSolvency() {
            this.dialogFormVisible = false;
            this.aditional = {};
        },
        initCancelSolvency2() {
            this.dialogFormVisible2 = false;
            this.aditional = {};
        },
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.bg-purple .el-card .el-button {
    font-size: 5rem;
    display: block;
    margin: 0 auto;
}

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
  