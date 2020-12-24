<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <input v-model="searchTerm" placeholder="Filtrar por nome" />

      <b-dropdown id="dropdown-1" text="Filter By Role" variant="primary" class="m-2">
        <b-dropdown-item @click="changeType('drink')">Managers</b-dropdown-item>
        <b-dropdown-item @click="changeType('dessert')">Cookers</b-dropdown-item>
        <b-dropdown-item @click="changeType('hot dish')">Deliverers</b-dropdown-item>
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
        :items="users"
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
      users: [],
      products: [],
      productToEdit: null,
      //filteredUsers:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
       fields: [
        
        'name',
        'email',
        'password',
        'type',
        'blocked',
        'photo_url',
        'available_at',
        'deleted_at',
         
        //   {
        //   key: "price",
        //   label: "Price",
        //   formatter: (value, key, item) => {
        //     return value + "€";
        //   },},

          { key: 'actions', label:" " }, 
          { key: 'actions1', label:" " }, 
        ]
      
    };
  },
  methods: {
    changeType: function (type) {
      this.searchType = type;
    },

    getUsers: function () {
      
        axios.get("api/users?page=" + this.currentPage).then((response) => {
          console.log(response.data.data);
          this.users = response.data.data;
          this.rows = this.users.length;
        });

    },


    updateTable: function(produto){
    console.log("aqui estou eu cheio de pinta");
      console.log(this.filteredUsers[1]);
      this.productToEdit=false;
      
      const index = this.filteredUsers.findIndex(item => item.id === produto.id);
      this.filteredUsers[index].name=produto.name;
      this.filteredUsers[index].description=produto.description;
      this.filteredUsers[index].type=produto.type;
      this.filteredUsers[index].price=produto.price;
      this.filteredUsers[index].photo=produto.photo_url;
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
    this.getUsers();
      
    
      console.log(this.$store.state.user);
     
    
  },
  computed: {
    filteredUsers() {
      var filteredUsers = this.products.filter((product) => {
        return (
          product.type.toLowerCase().includes(this.searchType.toLowerCase()) &&
          product.name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });

      let orderedProducts = filteredUsers.sort((a, b) => {
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

