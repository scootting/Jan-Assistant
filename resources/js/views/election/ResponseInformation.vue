<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>informacion sobre la consulta</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <p>
                    <el-alert title="Nota importante"
                        description="El resultado de esta consulta es de la lista de habilitados(as) emitida por el comite electoral para las elecciones a vicerrector."
                        type="success" show-icon>
                    </el-alert>
                </p>
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>datos de la persona</p>
                        <el-form ref="form" :model="user" label-width="200px" size="mini">
                            <el-form-item label="carnet de identidad">
                                <!--
                  <el-input v-model="user.ci_per" disabled></el-input>
                                <el-input v-model="user.des_per" disabled></el-input>
                                <el-input v-model="user.habilitado" disabled></el-input>
                    -->
                                {{ user.nro_dip }}
                            </el-form-item>
                            <el-form-item label="nombre completo">
                                {{ user.des_per }}
                            </el-form-item>
                            <el-form-item label="estado">
                                <template>
                                    <div v-if="user.estado !== 'Habilitado'">
                                        <el-tag type="danger" effect="dark">{{ user.estado }}</el-tag>
                                    </div>
                                    <div v-else>
                                        <el-tag type="success" effect="dark">{{ user.estado }}</el-tag>
                                    </div>
                                </template>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="12">
                    <div class="grid-content bg-purple" v-if="user.estado === 'Habilitado'">
                        <p>datos de la mesa</p>
                        <el-form ref="form" :model="tablet" label-width="200px" size="mini">
                            <el-form-item label="numero">
                                <el-tag type="" effect="dark">{{ tablet.numero }}</el-tag>
                            </el-form-item>
                            <el-form-item label="ubicacion">
                                {{ tablet.ubicacion }}
                            </el-form-item>
                            <el-form-item label="piso/ambiente">
                                {{ tablet.observaciones }}
                            </el-form-item>
                            <el-form-item label="descripcion">
                                {{ tablet.descripcion }}
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="test">Como puedo llegar?</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
            <el-button type="primary" size="small" @click="initRequestInformation">volver a consultar</el-button>
        </el-card>
    </div>
</template>
  
<script>
export default {
    name: "",
    data() {
        return {
            id: 0,
            user: {},
            tablet: {},
        };
    },
    mounted() {
        this.id = this.$route.params.id;
        this.initGetAuthorizedPerson();
    },
    methods: {
        test() {
            alert("Hola, como esta tu dia?");
        },
        async initGetAuthorizedPerson() {
            var app = this;
            try {
                let response = await axios.post("/getAuthorizedPerson", {
                    id: app.id,
                });
                console.log(response);
                app.user = response.data.dataPerson[0];
                app.tablet = response.data.dataTablet[0];
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initRequestInformation() {
            this.$router.push({
                name: "informationelection",
            });
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
    background: #e8e9e3;
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
  