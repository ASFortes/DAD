<template>
  <div class="top-right links">
    <a v-if="this.$route.path == '/home'" href="/#/products">Home</a>
    <a v-if="this.$route.path == '/products'" href="/#/home">Home</a>
    <a v-if="this.user==null" href="/#/login">Login</a>
    <a v-if="this.user!=null" href="/#/products" @click.prevent="logout">Logout</a>
    <a  v-if="this.user==null" href="/#/register">Register</a>
    <a v-if="this.user!=null" href="/#/userEdit" @click.prevent="myself">Profile</a>
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
        .post("/api/logout")
        .then((response) => {
          //   console.log("User has logged out");
          if (this.$route.path != "/home") {
            this.$router.push("/home");
            this.user = null;
          }
        })
        .catch((error) => {
          console.log("Invalid Logout");
        });
    },
    myself(){
      axios
        .get("/api/users/me")
        .then((response) => {
          console.log("User currently logged:");
          console.dir(response.data.name);
          this.user=response;
          this.$router.push("/userEdit");
          
        })
        .catch((error) => {
          console.log("Invalid Request");
        });
    }
 
  },
  
    mounted(){
    axios.get("api/users/me").then((response) => {
      this.user = response.data;
      console.log(this.user);
    });

    //console.log(this.user);
  }
};
</script>