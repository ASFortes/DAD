<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <input v-model="searchTerm" placeholder="Filtrar por nome" />

      <b-dropdown id="dropdown-1" text="Tipo" variant="primary" class="m-2">
        <b-dropdown-item @click="changeType('drink')">Drink</b-dropdown-item>
        <b-dropdown-item @click="changeType('dessert')"
          >Dessert</b-dropdown-item
        >
        <b-dropdown-item @click="changeType('hot dish')"
          >Hot Dish</b-dropdown-item
        >
        <b-dropdown-item @click="changeType('dish')">Dish</b-dropdown-item>
        <b-dropdown-item @click="changeType('cold dish')"
          >Cold Dish</b-dropdown-item
        >
        <b-dropdown-item @click="changeType('')">All</b-dropdown-item>
      </b-dropdown>

      <b-table
        class="table table-striped"
        id="my-table"
        :items="filteredProducts"
        :per-page="perPage"
        :current-page="currentPage"
        small
        :fields="fields"
>
      

        <template #cell(photo)="data">
          <img
            class="img-usr-container"
            :src="'storage/products/' + data.item.photo"
            height="50"
            width="50"
          />
        </template>
        <template #cell(actions)="data">
        <button v-if="$store.state.user!=null && $store.state.user.type =='C'"  class="btn btn-sm btn-success"  v-on:click.prevent="addToShoppingCart(data.item)">Add</button>
        <a v-if="$store.state.user!=null && $store.state.user.type =='EM'" class="btn btn-sm btn-success" v-on:click.prevent="addToShoppingCart(data.item)">Edit</a>
        </template>

        <template #cell(type)="data">
          <span
            v-if="data.item.type == 'drink'"
            class="badge badge-pill badge-info text-center"
            >{{ data.item.type }}</span
          >
          <span
            v-if="data.item.type == 'hot dish'"
            class="badge badge-pill badge-danger text-center"
            >{{ data.item.type }}</span
          >
          <span
            v-if="data.item.type == 'dish'"
            class="badge badge-pill badge-success text-center"
            >{{ data.item.type }}</span
          >
          <span
            v-if="data.item.type == 'cold dish'"
            class="badge badge-pill badge-primary text-center"
            >{{ data.item.type }}</span
          >
          <span
            v-if="data.item.type == 'dessert'"
            class="badge badge-pill badge-warning text-center"
            >{{ data.item.type }}</span
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
    </div>
  </div>
</template>

<script>
import NavBarComponent from "./navBar";
import NavBar from "./navBar.vue";

export default {
  data: function () {
    return {
      user:null,
      products: [],
      //filteredProducts:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
       fields: [
        
          'photo',
          'name',
          'type',
          'description',
         
          {
          key: "price",
          label: "Price",
          formatter: (value, key, item) => {
            return value + "€";
          },},

          { key: 'actions', label:" " }, 
        ]
      
    };
  },
  methods: {
    changeType: function (type) {
      this.searchType = type;
    },

    getProducts: function () {
      if (this.$root.products.length === 0) {
        axios.get("api/products?page=" + this.currentPage).then((response) => {
          this.$root.products = response.data.data;
          this.products = this.$root.products;
          this.rows = this.products.length;
        });
      } else {
        this.products = this.$root.products;
      }
    },
    addToShoppingCart: function(item){
    
      this.$store.commit('addToCart', item);
      //alert("This item has been added to your cart");
      console.log(this.$store.state.shopCart);
      

    }
  },
  mounted() {
    this.getProducts();
      
    
      console.log(this.$store.state.user);
     
    
  },
  computed: {
    filteredProducts() {
      var filteredProducts = this.products.filter((product) => {
        return (
          product.type.toLowerCase().includes(this.searchType.toLowerCase()) &&
          product.name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });

      let orderedProducts = filteredProducts.sort((a, b) => {
        return b - a;
      });
      this.rows = orderedProducts.length;
      return orderedProducts;
    
    },
  },
  components: {
    navBar: NavBarComponent,
  },
};
</script>

<style scoped>

</style>

