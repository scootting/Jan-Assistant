<template>
  <el-select
    v-model="selected"
    placeholder="seleccione el cargo"
    :multiple="multiple"
    :loading="cargosLoading"
    @change="onChange()"
  >
    <el-option
      v-for="cargo in cargos"
      :key="cargo.id"
      :label="cargo.descripcion"
      :value="cargo.id"
    >
    </el-option>
  </el-select>
</template>

<script>
export default {
  name: "selectCargo",
  props: {
    ofcCod: {
      type: String,
      default: '',
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    value: {
      default: null,
    },
  },
  data() {
    return {
      cargos: [],
      cargosLoading: false,
      selected:'',
    };
  },
  created() {
    this.getCargos();
    this.selected = this.value;
  },
  watch: {
    ofcCod(newVal){
      if(newVal){
        this.getCargos();
      }
      else{
        this.ofcCod='';
        this.getCargos();
      }
    },
  },
  methods: {
    onChange() {
      this.$emit('input',this.selected)
    },
    getCargos() {
      this.cargosLoading = true;
      axios
        .get("/api/inventory2/cargos", {
          params: { cod_soa: this.ofcCod },
        })
        .then((data) => {
          this.cargosLoading = false;
          this.cargos = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style>
</style>