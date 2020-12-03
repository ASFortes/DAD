<template>
  <b-container class="bv-example-row bv-example-row-flex-cols">
    <b-row align-h="center">
      <b-col>
        <div class="card mb-0 p-4" style="width: 100%; min-width: 400px">
          <div class="cart container mb-5">
            <b-jumbotron
              header="Food@Home"
              lead="Cart"
              class="text-center mt-2"
              style="background: none !important"
            ></b-jumbotron>
           
            <b-jumbotron
              v-if="this.products.length == 0"
              lead="Your cart is empty"
              class="text-center mt-2"
              style="background: none !important"
            ></b-jumbotron>

            <b-table
              v-if="this.products.length != 0"
              class="table table-striped"
              id="my-table"
              :items="products"
              :per-page="perPage"
              :current-page="currentPage"
              small
              :fields="fields"
            >
            <template #cell(actions)="data">
        <button   class="btn btn-sm btn-danger"  v-on:click.prevent="removeFromShoppingCart(data.item)">Remove</button>
         <button   class="btn btn-sm btn-success"  v-on:click.prevent="addToShoppingCart(data.item)">Add</button>
        </template>
          
            </b-table>  
            <b-row>
                <b-col colspan="4" class="text-right h3">Total: €{{Number(total).toFixed(2) + "€"}}</b-col>
            </b-row>

            <router-link to="/products">Go shopping</router-link>
           </div>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
export default {
  data: function () {
    return {
      products: [],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
      fields: [
        { key: "product.name", label: "Name" },
        "quantity",
        {
          key: "product.price",
          label: "Price",
          formatter: (value, key, item) => {
            return value + "€";
          },
        },
        {
          key: "subtotal",
          label: "Sub-Total",
          formatter: (value, key, item) => {
            return Number(item.quantity * item.product.price).toFixed(2) + "€";
          },
        },
          {
          key: "actions",
          label: " ",

         
        },

      ],
    };
  },

  computed: {
    total() {
        return this.products.reduce((total, p) => {
          return total + p.product.price * p.quantity
         }, 0)
    },
  },
  methods: {
    checkout() {
      alert("Pay us $" + this.total);
    },
    removeFromShoppingCart: function(item){
    
      this.$store.commit('removeFromCart', item.product);
      //alert("This item has been added to your cart");
       console.log(this.$store.state.shopCart);
       this.products = this.$store.state.shopCart;
      

    },
     addToShoppingCart: function(item){
    
      this.$store.commit('addToCart', item.product);
      //alert("This item has been added to your cart");
      console.log(this.$store.state.shopCart);
      this.products = this.$store.state.shopCart;
      

    }
  
  },
  mounted() {
    console.log("começo aqui ");
    console.log(this.$store.state.shopCart);
    this.products = this.$store.state.shopCart;

    //console.log(this.user);
  },
};
</script>
