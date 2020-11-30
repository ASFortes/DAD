<template>
<div>
    <div class="alert alert-danger" role="alert" v-if="showMessage">
    {{errorMessage}}
    </div> 
 
  <div class="jumbotron">
    <h2>Login</h2>
    <div class="form-group">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        v-model="credentials.email"
        name="email"
        id="inputEmail"
        placeholder="Email address"
      />
    </div>
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input
        type="password"
        class="form-control"
        v-model="credentials.password"
        name="password"
        id="inputPassword"
      />
    </div>
    <div class="form-group">
      <a class="btn btn-default" v-on:click.prevent="login">Login</a>
      <a href="/#/home" class="btn btn-default">Cancel</a>
    </div>
  </div>
  </div>
</template>
<script>
export default {
  data: function () {
    return {
      credentials: {
        email: "",
        password: "",
      },
      errorMessage:"",
      showMessage:false,
    };
  },
  methods: {
    login() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", this.credentials)
          .then((response) => {
           console.log(response.data.type)
            this.$router.push("/products");
            console.log(this.$route.path);
            // if(response.data.type=='EM'||response.data.type=='EC'||response.data.type=='ED'){
                
            // };
           
          })

         
          .catch((error) => {
            
            console.log(error.response);
            this.showMessage=true;
            this.errorMessage=error.response.data.message;
            
          });
      }); 
    },
  },
};
</script>