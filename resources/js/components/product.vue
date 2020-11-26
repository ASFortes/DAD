<template>
<div>
    
       
    <div class ="overflow-auto container  align-middle">
          <div  >
          

                <div class="top-right links">
                     <a href="/#/home">Página Inicial</a>
                        <a href="/#/login">Login</a>
                            <a href="/#/register">Register</a>
                </div>
         </div>
    
    <input  v-model="searchTerm" placeholder="Filtrar por nome">
   
  <b-dropdown id="dropdown-1" text="Tipo"  variant="primary" class="m-2">
  <b-dropdown-item @click="changeType('drink')" >Drink</b-dropdown-item> 
  <b-dropdown-item @click="changeType('dessert')">Dessert</b-dropdown-item> 
  <b-dropdown-item @click="changeType('hot dish')">Hot Dish</b-dropdown-item>
  <b-dropdown-item @click="changeType('dish')">Dish</b-dropdown-item>
  <b-dropdown-item @click="changeType('cold dish')">Cold Dish</b-dropdown-item>
  <b-dropdown-item @click="changeType('')">All</b-dropdown-item>  
 </b-dropdown>
  
  

    
  <b-table class="table table-striped" 
      id="my-table"
     
      :items="filteredProducts"
      :per-page="perPage"
      :current-page="currentPage"
      small
  >
 <template #cell(photo)="data">
         <img
               
                class="img-usr-container"
                :src="'storage/products/'+ data.item.photo"
                height="50"
                width="50"
              />
              
      </template>


   <template #cell(type)="data">
       <span v-if="data.item.type=='drink'"  class="badge badge-pill badge-info text-center ">{{data.item.type}}</span>
       <span v-if="data.item.type=='hot dish'"  class="badge badge-pill badge-danger text-center ">{{data.item.type}}</span>
       <span v-if="data.item.type=='dish'"  class="badge badge-pill badge-success text-center ">{{data.item.type}}</span>
       <span v-if="data.item.type=='cold dish'"  class="badge badge-pill badge-primary text-center ">{{data.item.type}}</span>
       <span v-if="data.item.type=='dessert'"  class="badge badge-pill badge-warning text-center ">{{data.item.type}}</span>
      

    
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
export default {
  data: function () {
    return {
      products: [],
      //filteredProducts:[],
      currentPage:1,
      perPage:10,
      rows:0,
      searchTerm:'',
      searchType:'',
    
    }
  },
  methods:{
      changeType:function(type){
          this.searchType=type;
      },
      

      getProducts:function(){
     
    if (this.$root.products.length === 0) {
      axios.get('api/products?page='+this.currentPage)
        .then(response => {
         
          this.$root.products = response.data.data
          this.products = this.$root.products
          this.rows=this.products.length;
        })
    } else {
      this.products = this.$root.products
    }
  }
  },
  mounted () {
      this.getProducts();


      
    }
    ,
    computed:{
          filteredProducts() {

              var filteredProducts= this.products.filter((product)=>{
                  
               
                  return product.type.toLowerCase().includes(this.searchType.toLowerCase()) && product.name.toLowerCase().includes(this.searchTerm.toLowerCase());})

                  
              
               let orderedProducts = filteredProducts.sort((a, b) => {
        return b - a;
      });
       this.rows=orderedProducts.length;
       return orderedProducts;
        // return this.products.filter(item => {
        //     console.log(item);
        //     console.log(item);
        //  return item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1
    //   })
    }
       
            
  }

}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>


  <!-- <thead>
      <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Photo</th>
        <th>Price</th>
        <th>Description</th>
        
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="product in products"
        :key="product.id"
      >
        <td>{{ product.name }}</td>
        <td>{{ product.type }}</td>

         <img
                 v-if="product.photo_url != null"
                class="img-usr-container"
                :src="'api/fotos/' + product.photo_url"
                height="50"
                width="50"
              />
        <td>{{ product.price}}</td>
        <td>{{ product.description }}</td>
        
      </tr>
  
    </tbody> -->