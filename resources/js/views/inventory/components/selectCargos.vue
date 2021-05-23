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
    defaultAll: {
      type: Boolean,
      default: false,
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
      let cod_soa = this.ofcCod;
      if(cod_soa)
      axios
        .get("/api/inventory/cargos/" + cod_soa)
        .then((data) => {
          this.cargosLoading = false;
          this.cargos = data.data;
          if(this.defaultAll){
            this.selected = this.cargos.map(c => c.id);
            this.onChange();
          }
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