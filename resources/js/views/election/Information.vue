<template>
    <div>
        <el-row :gutter="20" style="padding-top: 10px">
            <el-col :span="4" :offset="7">
                <div class="header">
                    <el-image :src="url_image" style="width: 90%; height: 90%; ,padding-top:50px">
                    </el-image>
                </div>
            </el-col>
            <el-col :span="6">
                <el-card class="box-card">
                    <div slot="header" class="clearfix" style="text-align: center;">
                        <el-image :src="url_image_election" style="width: 40%; height: 40%">
                        </el-image>
                        <h2>elecciones a vicerrector 2023 - 2027</h2>
                        <p>verifica si estas habilitado(a) para votar</p>
                        <!--
                -->
                    </div>
                    <div>
                        <el-form ref="form" class="login-form" :model="user" :rules="rules"
                            @submit.native.prevent="initGetAuthorizedPerson">
                            <el-form-item prop="id">
                                <el-input v-model="user.id" placeholder="Documento de identidad">
                                    <i slot="prefix" class="el-input__icon el-icon-user"></i>
                                </el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button class="login-button" type="primary" native-type="submit"
                                    block>consultar
                                </el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-card>
                <div class="footer">
                    <el-button style="float: right; padding: 3px 0" type="text" @click.native="initGetDataTablets">Verifique
                        la lista de mesas habilitadas</el-button>
                    <el-button style="float: right; padding: 3px 0" type="text" @click.native="dialogMapVisible = true">Mapa
                        de
                        mesas habilitadas</el-button>
                    <div class="version">Version 1.0.01</div>
                </div>
            </el-col>
        </el-row>
        <el-dialog title="Shipping address" :visible.sync="dialogMapVisible">
            <el-image :src="url_image_maps">
            </el-image>
        </el-dialog>
    </div>
</template>
  
<script>
export default {
    name: "loginn",
    data() {
        return {
            user: {
                username: null,
            },
            url_image: "/images/EUATF.png", //url('../images/EUATF.png'),//
            url_image_election: "/images/ICE.png", //url('../images/EUATF.png'),//
            url_image_maps: "/images/MAPA.jpeg", //url('../images/EUATF.png'),//
            rules: {
                id: [
                    {
                        required: true,
                        message: "El carnet de identidad es requerido",
                        trigger: "blur",
                    },
                ],
            },
            id_election: 2,
            dialogMapVisible: false,
        };
    },
    methods: {
        initGetAuthorizedPerson() {
            let app = this;
            this.$router.push({
                name: "responseinformation",
                params: {
                    id_election: app.id_election,
                    id: app.user.id,
                },
            });
        },
        initGetDataTablets() {
            let app = this;
            this.$router.push({
                name: "responsedatatablets",
                params: {
                    id: app.id_election,
                },
            });
        }
    },
};
</script>
<style scoped>
.login .el-card {
    display: flex;
    justify-content: center;
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both;
}

/*estilo aprobado para su uso*/
.login-button {
    width: 100%;
    margin-top: 20px;
}

.header,
.footer {
    padding: 10px 10px;
    color: #f0f4f8;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.footer .version {
    font-family: "Open Sans";
    padding: 0 10px;
    color: #9fb3c8;
    font-size: 15px;
    margin-top: 5px;
}
</style>