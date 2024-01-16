<template>
    <div>
        <el-container>
            <el-container>
                <el-main>
                    <el-row>
                        <el-col :span="12" :offset="6">
                            <el-card class="box-card">
                                <div slot="header" class="clearfix">
                                    <span>Verifique si esta registrado</span>
                                    <!--
                                    <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
                                    -->
                                </div>
                                <el-row>
                                    <el-col :span="20">
                                        <el-form label-width="260px">
                                            <el-form-item label="Numero de carnet" prop="personal">
                                                <el-input size="small" v-model="nodip"></el-input>
                                            </el-form-item>
                                            <el-form-item>
                                                <el-button size="small" type="primary"
                                                    @click.prevent="search">Buscar</el-button>
                                                <el-button size="small" type="success"
                                                    @click.prevent="initLogin">Volver</el-button>
                                            </el-form-item>
                                        </el-form>
                                    </el-col>
                                </el-row>
                            </el-card>
                        </el-col>
                    </el-row>
                    <p></p>
                    <el-row>
                        <el-col :span="12" :offset="6">
                            <el-card class="box-card">
                                <div slot="header" class="clearfix">
                                    <span>Registre aca sus datos sino se encuentra registrado</span>
                                    <!--
                                    <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
                                    -->
                                </div>
                                <el-row>
                                    <el-col :span="20">
                                        <el-form ref="form" :model="person" :rules="rules" label-width="260px"
                                            :disabled="existe == true">
                                            <el-form-item size="small" label="numero de identificacion" prop="personal">
                                                <el-input size="small" v-model="person.personal"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="nombres" prop="nombres">
                                                <el-input size="small" v-model="person.nombres"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="apellido paterno">
                                                <el-input size="small" v-model="person.paterno"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="apellido materno" prop="materno">
                                                <el-input size="small" v-model="person.materno"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="fecha de nacimiento" prop="nacimiento">
                                                <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                                                    v-model="person.nacimiento" style="width: 100%"></el-date-picker>
                                            </el-form-item>
                                            <el-form-item size="small" label="genero">
                                                <el-radio-group v-model="person.sexo" size="small">
                                                    <el-radio-button label="M"></el-radio-button>
                                                    <el-radio-button label="F"></el-radio-button>
                                                </el-radio-group>
                                            </el-form-item>
                                            <el-form-item size="small" label="teléfono" prop="telefono">
                                                <el-input size="small" v-model="person.telefono"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="dirección" prop="dirección">
                                                <el-input size="small" v-model="person.direccion"></el-input>
                                            </el-form-item>
                                            <el-form-item size="small" label="e-mail" prop="correo">
                                                <el-input size="small" v-model="person.correo"></el-input>
                                            </el-form-item>
                                            <el-form-item>
                                                <el-button size="small" type="primary" @click.prevent="savePerson">Guardar</el-button>
                                            </el-form-item>
                                        </el-form>
                                    </el-col>
                                </el-row>
                            </el-card>
                        </el-col>
                    </el-row>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>
  
<script>

export default {
    name: "Bienvenido",
    data() {
        return {
            messages: {},
            dataPerson: {},

            nodip: null,
            existe: false,
            messages: {},
            person: {
                personal: "",
                nombres: "",
                paterno: "",
                materno: "",
                nacimiento: "",
                sexo: "M",
                telefono: "",
                direccion: "",
                correo: "",
            },
            rules: {
                personal: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede ser menos de 2 o mas de 100",
                        trigger: "blur",
                    },
                ],
                nombres: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede ser menos de 2 o mas de 100",
                        trigger: "blur",
                    },
                ],
                materno: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede ser menos de 2 o mas de 100",
                        trigger: "blur",
                    },
                ],
                nacimiento: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                ],
                telefono: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede mas de 8",
                        trigger: "blur",
                    },
                ],
                direccion: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede ser menos de 5 o mas de 100",
                        trigger: "blur",
                    },
                ],
                correo: [
                    {
                        required: true,
                        message: "El campo no puede estar vacio",
                        trigger: "blur",
                    },
                    {
                        min: 2,
                        max: 100,
                        message: "el tamaño no puede ser menos de 2 o mas de 100",
                        trigger: "blur",
                    },
                ],
            },

        };
    },
    mounted() {

    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        initLogin() {
            this.$router.push({ name: "login" });
        },
        async search() {
            var app = this;
            try {
                let response = await axios.post("/person", {
                    nodip: app.nodip
                });
                app.loading = false;
                app.dataPerson = response.data.dataPerson[0];
                app.$alert(app.dataPerson.des_per + ", puede ingresar a traves de la plataforma usando su ci y fecha de nacimiento si es la primera vez.", "INFORMACION", {
                    dangerouslyUseHTMLString: true,
                });
                console.log(app.dataPerson);
            } catch (error) {
                this.error = error.response.data;
                this.error.message = "La persona con el numero de carnet " + app.nodip + " no se encuentra en nuestros registros.";
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        async savePerson() {
            let app = this;
            try {
                let response = await axios.post("/storePerson", {
                    persona: app.person,
                    marker: "registrar",
                });
                app.$alert("Se ha creado el registro de la persona, puede ingresar a traves de la plataforma usando su ci y fecha de nacimiento si es la primera vez.", "INFORMACION", {
                    dangerouslyUseHTMLString: true,
                });
            } catch (error) {
                console.log(error);
                /*
                this.error = error.response.data;
                this.error.message = "La persona con el numero de carnet " + app.nodip + " no se encuentra en nuestros registros.";
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
                */
            }
        },
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#app {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: left;
}
</style>
  