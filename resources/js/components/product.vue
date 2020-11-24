<template>
<div class ="overflow-auto">
  <b-table class="table table-striped" 
      id="my-table"
      :items="products"
      :per-page="perPage"
      :current-page="currentPage"
      small
  >
 <template #cell(photo)="data">
         <img
               
                class="img-usr-container"
                :src="'api/fotos/'+ data.item.photo "
                height="50"
                width="50"
              />
      </template>
 
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
  </b-table>
      <p class="mt-3">Current Page: {{ currentPage }}</p>
     <b-pagination
      
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="my-table"
    ></b-pagination>
    </div>
</template>

<script>
export default {
  data: function () {
    return {
      products: [],
      currentPage:1,
      perPage:10,
      rows:0
    }
  },
  methods:{
      getProducts:function(){
      console.log(this.$root.products)
    if (this.$root.products.length === 0) {
      axios.get('api/products?page='+this.currentPage)
        .then(response => {
          console.log(response)
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
       
            
  }

}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>
