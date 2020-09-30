<template>
<div>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>manejo de perfiles</span>
            <el-button style="float: right; padding: 3px 0" type="text">nuevos perfiles</el-button>
        </div>
        <h1>perfiles</h1>
        <h5>
            <p>{{ user.usuario }}</p>
            <p>{{ user.gestion }}</p>
            <p>{{ user }}</p>
        </h5>
        <el-table :data="tableData" style="width: 100%">
            <el-table-column label="Fecha" width="180">
                <template slot-scope="scope">
                    <i class="el-icon-time"></i>
                    <span style="margin-left: 10px">{{ scope.row.date }}</span>
                </template>
            </el-table-column>
            <el-table-column label="Nombre" width="180">
                <template slot-scope="scope">
                    <el-popover trigger="hover" placement="top">
                        <p>Name: {{ scope.row.name }}</p>
                        <p>Addr: {{ scope.row.address }}</p>
                        <div slot="reference" class="name-wrapper">
                            <el-tag size="medium">{{ scope.row.name }}</el-tag>
                        </div>
                    </el-popover>
                </template>
            </el-table-column>
            <el-table-column label="Operaciones">
                <template slot-scope="scope">
                    <el-button size="mini" type="danger" @click="handleDeleteProfile(scope.$index, scope.row)">Eliminar</el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>
</div>
</template>

<script>
export default {
    name: "Inventarios",
    data() {
        return {
            user: this.$store.state.user,
            messages: {},
            data: {},
            tableData: [{
                    date: "2016-05-03",
                    name: "Tom",
                    address: "No. 189, Grove St, Los Angeles",
                },
                {
                    date: "2016-05-02",
                    name: "Tom",
                    address: "No. 189, Grove St, Los Angeles",
                },
                {
                    date: "2016-05-04",
                    name: "Tom",
                    address: "No. 189, Grove St, Los Angeles",
                },
                {
                    date: "2016-05-01",
                    name: "Tom",
                    address: "No. 189, Grove St, Los Angeles",
                },
            ],
        };
    },
    mounted() {},
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        handleDeleteProfile(index, rows) {
            rows.splice(index, 1);
        }
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>
