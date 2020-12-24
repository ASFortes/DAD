<template>
  <div class="mt-5 d-flex justify-content-center">
    <div class="alert alert-danger" role="alert" v-if="showMessage">
      {{ errorMessage }}
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
import orderSocketCookera from "./ordersH";
export default {
  data: function () {
    return {
      order: null,
      credentials: {
        email: "",
        password: "",
      },
      errorMessage: "",
      showMessage: false,
    };
  },
  components: {
    orderSocketCooker: orderSocketCookera,
  },
  mounted() {
    
  },
  methods: {
    login() {
      this.$store.commit("clearUser");
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", this.credentials)
          .then((response) => {
            this.$store.commit("setUser", response.data);

            if (this.$store.state.user.type == "EC" ) {

                    axios
                      .put(
                        "api/assignCook/" +
                          this.$store.state.user.id 

                      )
                      .then((response) => {
                        console.log("ganda cafeteira");
                        console.log(response.data);
                        this.order = response.data;
                        console.log("media cafeteira");
                        console.log(this.order);
                        
                        if(this.order.length!=0){
                        this.$socket.emit("cooker_ready", this.order.id);
                        
                        }
                        this.$router.push("/cookOrders");

                      })
                      .catch((error) => {
                        console.log("erro aquii");
                        console.log(error);
                      });

            }

            //this.$toasted.show('User is authenticated successfully',{type:'success'})
            if (this.$store.state.user.type != "EC") {
              this.$router.push("/products");
            }
            
          })
          .catch((error) => {
            //this.$toasted.show('Invalid Authentication', { type: 'error' })
            console.log(error);
            this.showMessage = true;
            this.errorMessage = error.response.data.message;
          });
      });
    },
  },
};
</script>