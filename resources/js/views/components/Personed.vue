<template>
  <div>
    <el-dialog
      title="Warning"
      :visible.sync="isCenterDialogVisible"
      width="30%"
      center
    >
      <span
        >It should be noted that the content will not be aligned in center by
        default</span
      >
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" @click="closeModal" @close="cierren">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "Personed",
  props: {
    centerDialogVisible: {
      required: true,
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isCenterDialogVisible: this.centerDialogVisible,
    };
  },
  computed: {},
  methods: {
    cierren(){
      console.log("pruebas de como se cierra");
    },
    closeModal() {
      this.isCenterDialogVisible = false;
      this.$emit("update-visible", this.isCenterDialogVisible);
    },
  },
  watch: {
    centerDialogVisible: function () {
      this.isCenterDialogVisible = this.centerDialogVisible;
    },
  },
};
</script>

<style>
</style>