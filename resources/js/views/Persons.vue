<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>personas</span>
                <el-button
                    style="text-align: right; float: right"
                    size="small"
                    type="primary"
                    icon="el-icon-plus"
                    @click="initAddPerson"
                    >nueva persona</el-button
                >
                <!--
                <span>personas</span>
                <div style="text-align: right">
                </div>
                <el-button style="float: right; padding: 3px 0" type="text"
                    >nueva persona</el-button
                >
                -->
            </div>
            <div style="margin-top: 15px;">
                <el-input
                    placeholder="Please input"
                    v-model="writtenTextParameter"
                    class="input-with-select"
                >
                    <el-select
                        v-model="selectParameter"
                        slot="prepend"
                        placeholder="Select"
                    >
                        <el-option
                            v-for="item in parameters"
                            :label="item.attribute"
                            :value="item.value"
                            :key="item.value"
                        ></el-option>
                    </el-select>
                    <el-button slot="append" icon="el-icon-search"></el-button>
                </el-input>
            </div>
            <br />
            <div>
                <el-table
                    v-loading="loading"
                    :data="people"
                    style="width: 100%"
                >
                    <el-table-column prop="nro_dip" label="nro. dip">
                    </el-table-column>
                    <el-table-column prop="paterno" label="ap. paterno">
                    </el-table-column>
                    <el-table-column prop="materno" label="ap. materno">
                    </el-table-column>
                    <el-table-column prop="nombres" label="nombres" width="180">
                    </el-table-column>
                    <el-table-column align="right" width="180">
                        <template slot-scope="scope">
                            <el-button
                                @click="initEditPerson(scope.$index, scope.row)"
                                type="primary"
                                size="mini"
                                plain
                                >Editar</el-button
                            >
                            <el-button
                                @click="initShowPerson(scope.$index, scope.row)"
                                type="danger"
                                plain
                                size="mini"
                                >Mostrar</el-button
                            >
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination
                    :page-size="pagination.per_page"
                    layout="prev, pager, next"
                    :current-page="pagination.current_page"
                    :total="pagination.total"
                    @current-change="getDataPageSelected"
                >
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
export default {
    name: "Personas",
    data() {
        return {
            selectParameter: "",
            parameters: [
                { attribute: "carnet de identidad", value: "no_dip" },
                { attribute: "apellido paterno", value: "paterno" },
                { attribute: "apellido materno", value: "materno" },
                { attribute: "nombres", value: "nombres" }
            ],
            messages: {},
            people: [],
            pagination: { page: 1 },
            writtenTextParameter: "",
            loading: true
        };
    },
    mounted() {
        let app = this;
        app.selectParameter =  this.parameters[0].value;
        /*
            axios.get('/persona/buscar', 
            { params: { 'description': app.description, 'page': page} })
            .then ...
        */
        axios
            .post("/api/persons", {
                descripcion: app.descripcion
            })
            .then(response => {
                app.loading = false;
                app.people = response.data.data;
                app.pagination = response.data;
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
        getDataPageSelected(page) {
            let app = this;
            app.loading = true;
            axios
                .post("/api/persons", {
                    descripcion: app.descripcion,
                    page: page
                })
                .then(response => {
                    app.loading = false;
                    app.people = Object.values(response.data.data);
                    app.pagination = response.data; 
                })
                .catch(error => {
                    console.log(error);
                });
        },
        initAddPerson() {
            this.$router.push({ name: "addperson" });
        },
        initEditPerson(index, row){
            console.log(index, row);
            let personal = row.nro_dip;
            this.$router.push({ name: 'editperson', params: { id: personal.trim() }})

        },
        initShowPerson(index, row){
            let personal = row.nro_dip;
            //router.push({ name: 'editperson', params: { userId: personal }})
        }
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
