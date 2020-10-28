<template>
  <div>
    <el-dialog
      title="Warning"
      :visible.sync="centerDialogVisible"
      width="30%"
      center
    >
      <span
        >It should be noted that the content will not be aligned in center by
        default</span
      >
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" @click="closeModal">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "ModalPerson",
  /*
  props: {
    centerDialogVisible: {
      required: true,
      type: Boolean,
      default: false,
    },
  },*/
  data() {
    return {
      centerDialogVisible: false,
    };
  },
  computed: {},
  methods: {
    openModal() {
      console.log("Probando que ingresa aca!!!");
      this.centerDialogVisible = true;
    },
    closeModal() {
      this.centerDialogVisible = false;
    },
  },
};
</script>

<style>
</style>