<template>

</template>
<script>
export default {
  data() {
    return {
      orders: [],
    };
  },
  methods: {
    assignCooker: function (cookerID) {
      console.log("nabos com batatas");
      //   orders[orders.lenght].prepared_by = cookerID;
      axios
        .put("api/assignCook/" + cookerID, orders[orders.lenght].id)
        .then((response) => {
          console.log(response);
          this.orders = response.data;
        })
        .catch((error) => {
          console.log("erro aquii");
          console.log(error);
        });

      //   let teacherIdx = this.teachers.findIndex((value) => value.id === updatedTeacher.id)
      //   if (teacherIdx >= 0) {
      //     this.$set(this.teachers, teacherIdx, updatedTeacher)
    },
  },

//   sockets: {
//     cooker_ready(id) {
//       console.log("ramses com batatas");
//       this.assignCooker(id);
//     },
//   },
  mounted() {
    console.log("mounted");
    axios
      .get("api/orders")
      .then((response) => {
        console.log(response);
        this.orders = response.data;
      })
      .catch((error) => {
        console.log("erro aquii");
        console.log(error);
      });

 
  },
};
</script>