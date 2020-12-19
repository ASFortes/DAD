<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <br /><br /><br />

      <b-jumbotron
        v-if="this.orders.length == 0"
        lead="You don't have orders at the moment"
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
          label: "Tempo",
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
  methods: {
    getOrders: function () {
      axios
        .get(
          "api/cookOrders/" +
            this.$store.state.user.id +
            "?page=" +
            this.currentPage
        )
        .then((response) => {
          this.orders = response.data;
          this.rows = this.orders.length;
      
        });
    },

    seeOrderDetails: function (id) {
      axios.get("api/orderItems/" + id).then((response) => {
        this.orderItems = response.data;
        console.log(response.data);
      });
    },

    calcula_data: function (current_status_time) {
      var diff = Math.abs(
        new Date() - new Date(current_status_time.replace(/-/g, "/"))
      );
      return Math.floor(diff / 1000);
    },
  },
  mounted() {
    this.getOrders();
    // console.log(this.$orders[id].current_status_at);
    console.log(this.$store.state.user);
  },

  computed: {
   
  },
  components: {
    navBar: NavBarComponent,
  },
};
</script>

<style scoped>
</style>

