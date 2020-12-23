<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <br /><br /><br />

      <b-jumbotron
        v-if="this.orders.length == 0"
        lead="You don't have any orders at the moment. Please wait!"
        class="text-center mt-2"
        style="background: none !important"
      ></b-jumbotron>

      <div v-if="this.orders.length > 0">
        <b-table
          class="table table-striped"
          id="my-table"
          :items="orders"
          :per-page="perPage"
          :current-page="currentPage"
          small
          :fields="fields"
          ref="table"
        >
          <template #cell(actions)="data">
            <b-button
              id="show-btn"
              v-if="$store.state.user != null && $store.state.user.type == 'EC'"
              class="btn btn-sm btn-info"
              @click.prevent="
                show = true;
                seeOrderDetails(data.item.id);
              "
              >See details</b-button
            >

            <b-button
              id="show-btn"
              v-if="$store.state.user != null && $store.state.user.type == 'EC'"
              class="btn btn-sm btn-success"
              @click.prevent="changeStatusToReady(data.item.id)"
              >Ready</b-button
            >
          </template>
        </b-table>

        <p class="mt-3">Current Page: {{ currentPage }}</p>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="my-table"
        ></b-pagination>
        <b-modal id="my-modal" v-model="show">
          <b-table
            id="my-table"
            :items="orderItems"
            :per-page="perPage"
            :current-page="currentPage"
            small
            :fields="fields1"
            striped
            hover
            responsive="sm"
          >
          </b-table>
        </b-modal>
      </div>
    </div>
  </div>
</template>

<script>
import NavBarComponent from "./navBar";
import NavBar from "./navBar.vue";

export default {
  data: function () {
    return {
      user: null,
      current_status_at: null,
      products: [],
      orders: [],
      ordersH: [],
      //filteredProducts:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
      show: false,
      fields: [
        "id",
        "opened_at",
        "customer_name",
        {
          key: "notes",
          label: "Notes",
          formatter: (value, key, item) => {
            if (value == null) {
              value = "No notes";
            }
            return value;
          },
        },

        {
          key: "tempo",
          label: "Time",
          formatter: (stempo, key, item) => {
            stempo = this.calcula_data(item.current_status_at);
            return Math.floor(stempo / 60) + " min";
          },
        },
        { key: "actions", label: " " },
      ],
      fields1: ["product_name", "quantity", "product_description"],
      orderItems: [],
    };
  },
  sockets: {
        new_order(iD) {
        this.orderReceived(iD);
        },
       
  },

  methods: {
    seeOrderDetails: function (id) {
      axios.get("api/orderItems/" + id).then((response) => {
        this.orderItems = response.data;
        console.log(response.data);
      });
    },

    changeStatusToReady: function (id) {
      axios
        .put("api/changeOrderPtoR/" + id)
        .then((response) => {
          console.log(response);
          this.orders = [];

          axios
            .put("api/assignCook/" + this.$store.state.user.id)
            .then((response) => {
              if (response.data.length == 0) {
                this.orders = [];
              } else {
                alert("New order assigned to you");
                this.orders = [];
                this.orders[0] = response.data;
                this.$socket.emit("order_cooked", this.orders.id);
              }
            })
            .catch((error) => {
              console.log("erro aquii");
              console.log(error);
            });
          console.log(this.orders);
        })
        .catch((error) => {
          console.log("erro aquii1");
          console.log(error);
        });
      this.$socket.emit("order_cooked", this.orders[0].id);
    },

    calcula_data: function (current_status_time) {
      var diff = Math.abs(
        new Date() - new Date(current_status_time.replace(/-/g, "/"))
      );
      return Math.floor(diff / 1000);
    },
    orderReceived : function (id){
         axios
            .put("api/assignOnlineCook/"+id)
            .then((response) => {
              this.orders = [];
              alert("New order assigned to you");
              this.orders[0] = response.data;
              // if (response.data.length == 0) {
              //   this.orders = [];
              // } else {
              //   alert("New order assigned to you");
              //   this.orders = [];
              //   this.orders[0] = response.data;
              //   this.$socket.emit("order_cooked", this.orders.id);
              // }
            })
            .catch((error) => {
              console.log("erro aquii");
              console.log(error);
            });

    }
  },
  mounted() {
    axios
      .get("api/cookOrders/" + this.$store.state.user.id)
      .then((response) => {
        console.log(response);
        this.orders = response.data;
      })
      .catch((error) => {
        console.log("erro aquii");
        console.log(error);
      });
    // console.log(this.$orders[id].current_status_at);
    console.log(this.$store.state.user);
  },

  computed: {},
  components: {
    navBar: NavBarComponent,
  },
};
</script>

<style scoped>
</style>

