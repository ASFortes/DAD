<template>
  <div>
        
    <div class="overflow-auto container align-middle">
      
    <div>
        <nav-bar></nav-bar>
      </div>

     <br><br><br>

      <b-table
        class="table table-striped"
        id="my-table"
        :items="orders"
        :per-page="perPage"
        :current-page="currentPage"
        small
        :fields="fields">

      </b-table>
      
      <b-table
        class="table table-striped"
        id="my-table"
        :items="orderedProducts"
        :per-page="perPage"
        :current-page="currentPage"
        small
        :fields="fields">

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
      orders:[],
      //filteredProducts:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
       fields: [
        
          'id',
          'status',
          'notes',
         'total_price',
          'date',
         
     
        ]
      
    };
  },
  methods: {
 
    getOrders: function () {
     
        axios.get("api/orders/"+this.$store.state.user.id+"?page="+this.currentPage).then((response) => {
            console.log(response);
            console.log(this.$store.state.user);         
               this.orders=response.data;
                this.rows = this.orders.length;
        });
        
     
    },  
    



        
    
    
  },
  mounted() {
    this.getOrders();
      
    
      console.log(this.$store.state.user);
     
    
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
     orderedProducts(){
       
            this.orders.sort(function(a, b) {
              return b.id - a.id;
            });
            
            },
            
    
   
    
  },
  components: {
    navBar: NavBarComponent,
  },
};
</script>

<style scoped>

</style>

