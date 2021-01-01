<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>
      <br><br><br>

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

      <b-button
            id="show-btn"
            v-if="$store.state.user != null && $store.state.user.type == 'EM'"
            href="/#/addProduct"
            class="btn btn-sm btn-info"
            >Add Product</b-button
          >

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
        <button v-if="$store.state.user!=null && $store.state.user.type =='EM'" class="btn btn-sm btn-success" v-on:click.prevent="editProducts(data.item)">Edit</button>
        </template>

        <template #cell(actions1)="data">
        <button v-if="$store.state.user!=null && $store.state.user.type =='EM'" class="btn btn-sm btn-danger" v-on:click.prevent="deleteProducts(data.item.id)">Delete</button>
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

      <edit-products
      :product="productToEdit"
      v-if="productToEdit"
      @updated="updateTable"
      @canceled="hideEdit"
      >
      </edit-products>
    </div>
  </div>
</template>

<script>
import NavBarComponent from "./navBar";
import NavBar from "./navBar";
import EditProducts from "./editProducts";

export default {
  data: function () {
    return {
      user:null,
      products: [],
      productToEdit: null,
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
          { key: 'actions1', label:" " }, 
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
          console.log(response.data.data);
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
      

    },

    updateTable: function(produto){
console.log("aqui estou eu cheio de pinta");
      console.log(this.filteredProducts[1]);
      this.productToEdit=false;
      
      console.log("yeaaaaaaaaaaaahhhhh");
      const index = this.filteredProducts.findIndex(item => item.id === produto.id);
      console.log(index);
      this.filteredProducts[index].name=produto.name;
      this.filteredProducts[index].description=produto.description;
      this.filteredProducts[index].type=produto.type;
      this.filteredProducts[index].price=produto.price;
      this.filteredProducts[index].photo=produto.photo_url;
    },

    hideEdit: function(){
      this.productToEdit=false;
      
    },

    editProducts: function(item){
      this.productToEdit=item;
      
    },

    deleteProducts: function(id){
        axios
        .put(
          "api/deleteProduct/" + id)
        .then((response) => {
          window.location.reload(true);

        });
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
    'edit-products' : EditProducts,
  },
};
</script>

<style scoped>

</style>

