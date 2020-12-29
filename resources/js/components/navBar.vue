<template>
  <div class="top-right links">
    <a v-if="this.$route.path != '/home'" href="/#/home">Home</a>
    <a v-if="this.$route.path != '/products'" href="/#/products">Menu</a>
    <a v-if="this.$store.state.user==null" href="/#/login">Login</a>
    <a v-if="this.$store.state.user==null" href="/#/register">Register</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='C'" href="/#/cart" >Cart</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='C'" href="/#/ordersInProgress" >My orders</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='EC'" href="/#/cookOrders" >My Orders</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='ED'" href="/#/deliveryManOrders" >My OrdersD</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='EM'" href="/#/manageUsers" >Manage Users</a>
    <a v-if="this.$store.state.user!=null && this.$store.state.user.type=='EM'" href="/#/managerEmpList" >Manage Workers</a>
    <a v-if="this.$store.state.user!=null" href="/#/userEdit" @click.prevent="myself">Profile</a>
    <a v-if="this.$store.state.user!=null" href="/#/home" @click="logout">Logout</a>
   









     
  </div>
</template>
<script>
export default {
    data(){
      return{
      user:null,
      }
    },
  methods: {
       logout() {
      axios
        .put("/api/userUnavailable/"+this.$store.state.user.id)
        .then((response) => {
           this.$socket.emit("user_offline", this.$store.state.user.id);
          //   console.log("User has logged out");
         axios
        .post("/api/logout")
        .then((response) => {
          //   console.log("User has logged out");
          
           this.$store.commit('clearUser');
           this.$store.commit('clearCart');
          


        })
        .catch((error) => {
          console.log("Invalid Logout");
        });

        })
        .catch((error) => {
          console.log("unavailable didnt change");
        });



    }
  ,
    myself(){
      if(this.$store.state.user!=null){
      axios
        .get("/api/users/me")
        .then((response) => {
          console.log("User currently logged:");
          console.dir(response.data.name);
          this.user= this.$store.state.user;
          this.$router.push("/userEdit");
          // this.$store.state.user.name + '<br>' +
          // this.$store.state.user.email, { type: 'info' }
          
        })
        .catch((error) => {
          console.log("Invalid Request");
        });
      }

       

    }
 
  },
  
    mounted(){
        //console.log(window.localStorage);
      
          

    //console.log(this.user);
  }
};
</script>