<template>
  <table class="table table-striped">
    <thead>
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
    </tbody>
  </table>
</template>

<script>
export default {
  data: function () {
    return {
      products: []
    }
  },
  mounted () {
      console.log(this.$root.products)
    if (this.$root.products.length === 0) {
      axios.get('api/products')
        .then(response => {
          console.log(response)
          this.$root.products = response.data.data
          this.products = this.$root.products
        })
    } else {
      this.products = this.$root.products
    }
  }
}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>
