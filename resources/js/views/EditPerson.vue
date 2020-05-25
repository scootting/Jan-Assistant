<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>editar persona</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form ref="form" :model="person" :rules="rules" label-width="160px">
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
                <el-date-picker
                  size="small"
                  type="date"
                  placeholder="Pick a date"
                  v-model="person.nacimiento"
                  style="width: 100%;"
                ></el-date-picker>
              </el-form-item>
              <el-form-item size="small" label="genero">
                <el-radio-group v-model="person.sexo" size="small">
                  <el-radio-button label="M"></el-radio-button>
                  <el-radio-button label="F"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button size="small" type="primary" @click.prevent="savePerson" plain>Guardar</el-button>
                <el-button size="small" type="danger" @click="noPerson" plain>Cancelar</el-button>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "AniadirPersona",
  data() {
    return {
      
      person: {
        'marker': 'editar',
      },

      rules: {
        personal: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur"
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur"
          }
        ],
        nombres: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur"
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur"
          }
        ],
        materno: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur"
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur"
          }
        ],
        nacimiento: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur"
          }
        ]
      }
    };
  },
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    console.log("estes es el id: " + id);
    axios
      .get("/api/person/" + id)
      .then(function(response) {
        console.log(response.data);
        app.person = response.data[0];
      })
      .catch(function() {
        alert("No se puede hallar el registro de la persona indicada");
      });
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    savePerson() {
      //alert("hola");
      event.preventDefault();
      var app = this;
      var newPerson = app.person;
      axios
        .post("/api/person", {
          persona : newPerson ,
          marker: "editar"
        })
        .then(function(response) {
          alert("se ha creado el registro de la persona");
        })
        .catch(function(response) {
          console.log(response);
          alert("no se puede crear el registro de la persona");
        });
    },

    noPerson() {
      this.$router.push("/api");
    },

    resetPerson() {
      /*
            (this.person.nro_dip = ""),
                (this.person.nombres = ""),
                (this.person.paterno = ""),
                (this.person.materno = ""),
                (this.person.fec_nacimiento = ""),
                (this.person.id_sexo = "M");*/
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
}
.el-form {
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 60px;
}
</style>
