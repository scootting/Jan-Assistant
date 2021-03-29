<template>
  <el-select
    v-model="value"
    placeholder="seleccione la sub oficina"
    :multiple="multiple"
    :loading="SubUnidadLoading"
    @change="onChange()"
  >
    <el-option
      v-for="so in SubUnidad"
      :key="so.id"
      :label="so.descripcion"
      :value="so.id"
    >
    </el-option>
  </el-select>
</template>

<script>
export default {
  name: "selectSubUnidad",
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
      SubUnidad: [],
      SubUnidadLoading: false,
    };
  },
  created() {
    this.getSubUnidad();
  },
  watch: {
    ofcCod(newVal){
      if(newVal){
        this.getSubUnidad();
      }
      else{
        this.ofcCod='';
        this.getSubUnidad();
      }
    },
  },
  methods: {
    onChange() {
      this.$emit("input", this.value);
    },
    getSubUnidad() {
      this.SubUnidadLoading = true;
      axios
        .get("/api/inventory2/sub_unidad", {
          params: { cod_soa: this.ofcCod },
        })
        .then((data) => {
          this.SubUnidadLoading = false;
          this.SubUnidad = data.data;
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