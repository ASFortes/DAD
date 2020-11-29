<template>
  <div class="top-right links">
    <a v-if="this.$route.path == '/home'" href="/#/products">Menu</a>
    <a v-if="this.$route.path == '/products'" href="/#/home">Home</a>
    <a href="/#/login">Login</a>
    <a href="/#/products" @click.prevent="logout">Logout</a>
    <a href="/#/register">Register</a>
    <a href="/#/myself" @click.prevent="myself">Myself</a>
  </div>
</template>
<script>
export default {
    
  methods: {
    logout() {
      axios
        .post("/api/logout")
        .then((response) => {
          //   console.log("User has logged out");
          if (this.$route.path != "/home") {
            this.$router.push("/home");
          }
        })
        .catch((error) => {
          console.log("Invalid Logout");
        });
    },
    myself() {
      axios
        .get("/api/users/me")
        .then((response) => {
          console.log("User currently logged:");
          console.dir(response.data.name);
          this.$router.push("/myself");
          
        })
        .catch((error) => {
          console.log("Invalid Request");
        });
    },
  },
};
</script>