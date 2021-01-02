<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <br /><br /><br />

      <b-table
        id="my-table"
        :items="orders"
        :per-page="perPage"
        :current-page="currentPage"
        small
        :fields="fields"
        striped
        hover
        responsive="sm"
        ref="orderTable"
      >

      <template #cell(status)="data">
          <span
            v-if="data.item.status == 'H'"
            class="text-center"
            >Holding</span
          >
          <span
            v-if="data.item.status == 'P'"
            class="text-center"
            >Preparing</span
          >
          <span
            v-if="data.item.status == 'R'"
            class="text-center"
            >Cooked</span
          >
          <span
            v-if="data.item.status == 'T'"
            class="text-center"
            >In Transit</span
          >
          <span
            v-if="data.item.status == 'D'"
            class="text-center"
            >Delivered</span
          >
          <span
            v-if="data.item.status == 'C'"
            class="text-center"
            >Canceled</span
          >
        </template>

        <template #cell(actions)="data">
          <b-button
            id="show-btn"
            v-if="$store.state.user != null && $store.state.user.type == 'C'"
            class="btn btn-sm btn-info"
            @click.prevent="
              show = true;
              seeOrderDetails(data.item.id);
            "
            >See Details</b-button
          >
          
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
      <b-row>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="my-table"
        ></b-pagination>
      </b-row>
      <div class="text-right">
        <b-button cols="12" href="/#/ordersNotInProgress"
          >Order History</b-button
        >
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
      show: false,
      user: null,
      products: [],
      orderItems: [],
      orders: [],
      //filteredProducts:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
      fields: [
        { key: "id", sortable: true },
        { key: "status", sortable: false },
        { key: "total_price", sortable: true },
        { key: "date", sortable: true },
        { key: "actions", label: " " },
      ],
      fields1: ["product_name", "quantity", "unit_price", "sub_total_price"],
    };
  },
  methods: {
    getOrders: function () {
      axios
        .get(
          "api/orders/" +
            this.$store.state.user.id +
            "?page=" +
            this.currentPage
        )
        .then((response) => {
          console.log(response);
          console.log(this.$store.state.user);
          this.orders = response.data;
          this.rows = this.orders.length;
        });
    },

    seeOrderDetails: function (id) {
      //alert("This item has been added to your cart");

      axios.get("api/orderItems/" + id).then((response) => {
        this.orderItems = response.data;
        console.log(response);
      });
    },
  },
  
  mounted() {
    this.getOrders();
  

    console.log(this.$store.state.user);
  },

  sockets: {
        cooker_ready(iD) {
          const index = this.orders.findIndex(item => item.id === iD);
          this.orders[index].status='P';
        },




         order_delivered(iD) {
          const index = this.orders.findIndex(item => item.id === iD);
          this.orders[index].status='D';
        },

        
        deliveryMan_ready(iD) {
          const index = this.orders.findIndex(item => item.id === iD);
          this.orders[index].status='T';
        },
        order_cooked(iD) {
          const index = this.orders.findIndex(item => item.id === iD);
          this.orders[index].status='R';
        },

    },

  computed: {
    // orderedProducts() {
    //   // var filteredOrders = this.orders.filter((order) => {
    //   //   return (
    //   //     order.date.toLowerCase().includes(this.searchType.toLowerCase()) &&
    //   //     product.name.toLowerCase().includes(this.searchTerm.toLowerCase())
    //   //   );
    //   // });
    //   let orderedProducts = this.orders.sort((a, b) => {
    //     return b-a;
    //   });
    //   console.log(orderedProducts);
    //   return orderedProducts;
    // },
    //  orderedProducts(){
    //       let  orderedProducts = this.orders.sort(function(a, b) {
    //           return b.id - a.id;
    //         });
    //         },
  },
  components: {
    navBar: NavBarComponent,
  },
};
</script>

<style scoped>
</style>

