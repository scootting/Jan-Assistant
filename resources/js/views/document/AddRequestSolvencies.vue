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
                <el-row :gutter="50">
                    <el-col :span="24">
                        <div>
                            <el-table :data="dataSolvencies" style="width: 100%" size="small" fixed border>
                                <el-table-column prop="descr" label="documento" >
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{ scope.row.sub }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="tipo" label="tipo" fixed="right">
                                </el-table-column>
                                <el-table-column label="" fixed="right">
                                    <template slot-scope="scope">
                                        <el-button :disabled="scope.row.guardado === true" type="primary" size="small"
                                            icon="el-icon-tickets"
                                            @click="initAddInformationAditional(scope.$index, scope.row)">seleccionar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-col>
                </el-row>
            </el-row>
        </el-card>

        <!-- Form Add Register Input Manual-->
        <el-dialog title="informacion adicional" :visible.sync="dialogFormVisible">
            <el-alert title="importante" type="error"
                description="antes de realizar la solicitud de solvencia universitaria debe estar seguro que su informacion personal se encuentra actualizada, puede verificarlo en la opcion innformacion personal del menu."
                show-icon>
            </el-alert>
            <br>
            <el-form :model="aditional" label-width="200px" size="mini">
                <el-form-item label="fecha del registro">
                    <el-date-picker type="date" v-model="aditional.fecha" placeholder="seleccione una fecha"
                        style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item label="Unidad academica">
                    <el-autocomplete class="inline-input" v-model="aditional.des_prg" :fetch-suggestions="querySearch"
                        style="width: 100%;" placeholder="ingrese: la descripcion de la carrera y seleccione" :trigger-on-focus="false"
                        @select="handleSelect"></el-autocomplete>
                </el-form-item>
            </el-form>
            <el-form ref="form" :model="this.client" label-width="200px" size="mini">
                <el-form-item label="direccion">
                    <el-input v-model="client.direccion"></el-input>
                </el-form-item>
                <el-form-item label="telefono/celular">
                    <el-input v-model="client.telefono"></el-input>
                </el-form-item>
                <el-form-item label="correo electronico">
                    <el-input v-model="client.correo"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="initStoreSolvency">Guardar</el-button>
                <el-button type="danger" size="mini" @click="initCancelSolvency">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Register Input Manual-->
        <!-- Form Add Register Input Manual-->
        <el-dialog title="informacion adicional" :visible.sync="dialogFormVisible2">
            <el-alert title="importante" type="error"
                description="antes de realizar la solicitud de solvencia universitaria debe estar seguro que su informacion personal se encuentra actualizada, puede verificarlo en la opcion innformacion personal del menu."
                show-icon>
            </el-alert>

            <el-form :model="aditional" label-width="200px" size="mini">
                <el-form-item label="fecha del registro">
                    <el-date-picker type="date" v-model="aditional.fecha" placeholder="seleccione una fecha"
                        style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
            </el-form>
            <el-form ref="form" :model="this.client" label-width="200px" size="mini">
                <el-form-item label="direccion">
                    <el-input v-model="client.direccion"></el-input>
                </el-form-item>
                <el-form-item label="telefono/celular">
                    <el-input v-model="client.telefono"></el-input>
                </el-form-item>
                <el-form-item label="correo electronico">
                    <el-input v-model="client.correo"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="initStoreSolvency">Guardar</el-button>
                <el-button type="danger" size="mini" @click="initCancelSolvency2">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Register Input Manual-->
        <!-- Form Add Register Input Manual 3-->
        <el-dialog title="informacion adicional" :visible.sync="dialogFormVisible3">
            <el-alert title="importante" type="error"
                description="antes de realizar la solicitud de solvencia universitaria debe estar seguro que su informacion personal se encuentra actualizada, puede verificarlo en la opcion innformacion personal del menu."
                show-icon>
            </el-alert>
            <el-form :model="aditional" label-width="250px" size="mini">
                <el-form-item label="fecha del registro">
                    <el-date-picker type="date" v-model="aditional.fecha" placeholder="seleccione una fecha"
                        style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item label="Unidad academica o administrativa">
                    <el-autocomplete class="inline-input" v-model="aditional.des_prg" :fetch-suggestions="querySearch3"
                        style="width: 100%;" placeholder="ingrese: la descripcion inicial y seleccione.." :trigger-on-focus="false"
                        @select="handleSelect"></el-autocomplete>
                </el-form-item>
            </el-form>
            <el-form ref="form" :model="this.client" label-width="200px" size="mini">
                <el-form-item label="direccion">
                    <el-input v-model="client.direccion"></el-input>
                </el-form-item>
                <el-form-item label="telefono/celular">
                    <el-input v-model="client.telefono"></el-input>
                </el-form-item>
                <el-form-item label="correo electronico">
                    <el-input v-model="client.correo"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="initStoreSolvency">Guardar</el-button>
                <el-button type="danger" size="mini" @click="initCancelSolvency3">Cancelar</el-button>
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
            dialogFormVisible3: false,
            dataUniversity: [],
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
                let response = await axios.post("/api/getTypesOfDocuments", {
                    year: app.client.gestion,
                    typea: 'SOL'
                });
                app.loading = false;
                app.dataSolvencies = response.data;
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
                let response = await axios.post("/api/getAditionalInformation", {
                    year: app.client.gestion,
                });
                app.loading = false;
                app.dataCareer = response.data.dataCareer;
                app.dataUniversity = response.data.dataUniversity;
                //console.log(app.dataCareer);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initAddInformationAditional(index, row) {
            let app = this;
            app.solvency = row;
            switch (row.adicional) {
                case 1:
                    app.dialogFormVisible = true;
                    break;
                case 2:
                    app.dialogFormVisible2 = true;
                    break;
                case 3:
                    app.dialogFormVisible3 = true;
                    break;
                default:
                    break;
            }
        },
        querySearch(queryString, cb) {
            var links = this.dataCareer;
            var results = queryString ? links.filter(this.createFilter(queryString)) : links;
            cb(results);
        },

        querySearch3(queryString, cb) {
            var links = this.dataUniversity;
            var results = queryString ? links.filter(this.createFilter(queryString)) : links;
            cb(results);
        },

        createFilter(queryString) {
            return (link) => {
                return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
            };
        },
        handleSelect(item) {
            this.aditional.cod_prg = item.cod_prg;
            this.aditional.des_prg = item.cat_des;
            console.log('Consola');
            console.log(this.aditional);
        },


        async setValuesAcquired() {
        },

        //  *  M5. Guardar la solvencia escogida en linea
        async initStoreSolvency() {
            this.dialogFormVisible = false;
            var app = this;
            try {
                let response = await axios.post("/api/storeDataSolvency", {
                    cliente: app.client,
                    solvencia: app.solvency,
                    adicional: app.aditional,
                    marker: "registrar",
                });
                console.log(response);
                this.$router.push({
                name: "requestsolvencies",
            });
            } catch (error) {
                this.error = error.response.data;
                console.log(error.response);
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initCancelSolvency() {
            this.dialogFormVisible = false;
            this.aditional = {};
        },

        initCancelSolvency2() {
            this.dialogFormVisible2 = false;
            this.aditional = {};
        },

        initCancelSolvency3() {
            this.dialogFormVisible3 = false;
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