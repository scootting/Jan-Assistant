<template>
  <div>
    <el-dialog
      title="Warning"
      v-show="centerDialogVisible"
      :visible.sync="centerDialogVisible"
      width="30%"
      center
    >
      <span
        >No me molestes mas por favor</span
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
    closeModal() {
      this.isCenterDialogVisible = false;
      this.$emit("update:centerDialogVisible", false);
    },
  },
};
</script>

<style>
</style>