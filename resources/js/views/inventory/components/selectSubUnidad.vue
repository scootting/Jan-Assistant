<template>
  <el-select
    v-model="selected"
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
    defaultAll: {
      type: Boolean,
      default: false,
    }
  },
  data() {
    return {
      SubUnidad: [],
      SubUnidadLoading: false,
      selected:'',
    };
  },
  created() {
    this.getSubUnidad();
    this.selected = this.value;
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
       this.$emit('input',this.selected)
    },
    getSubUnidad() {
      this.SubUnidadLoading = true;
      let cod_soa = this.ofcCod;
      if(cod_soa)
      axios
        .get("/api/inventory/sub_offices/" + cod_soa)
        .then((data) => {
          this.SubUnidadLoading = false;
          this.SubUnidad = data.data;
          if(this.defaultAll){
            this.selected = this.SubUnidad.map(e=>e.id);
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