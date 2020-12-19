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
      ordersH: [],
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
    axios
      .get("api/orders")
      .then((response) => {
        console.log(response);
        this.ordersH = response.data;
      })
      .catch((error) => {
        console.log("erro aquii");
        console.log(error);
      });
  },
  methods: {
    login() {
      this.$store.commit("clearUser");
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", this.credentials)
          .then((response) => {
            this.$store.commit("setUser", response.data);

            if (this.$store.state.user.type == "EC") {
              //ver se o cooker tem alguma order
              axios
                .get("api/cookOrdersInProgress/" + this.$store.state.user.id)
                .then((response) => {
                  console.log(response.data);
                  if (response.data.length == 0) {
                    axios
                      .put(
                        "api/assignCook/" +
                          this.$store.state.user.id +
                          "/" +
                          this.ordersH[0].id
                      )
                      .then((response) => {
                        console.log(response);
                        this.orders = response.data;
                      })
                      .catch((error) => {
                        console.log("erro aquii");
                        console.log(error);
                      });
                    this.$socket.emit("cooker_ready", this.ordersH[0].id);
                  }
                })
                .catch((error) => {
                  console.log("erro no login");
                  console.log(error);
                });
            }

            //this.$toasted.show('User is authenticated successfully',{type:'success'})
            if (this.$store.state.user.type != "EC") {
              this.$router.push("/products");
            }
            if (this.$store.state.user.type == "EC") {
              this.$router.push("/cookOrders");
              
            }
          })
          .catch((error) => {
            //this.$toasted.show('Invalid Authentication', { type: 'error' })
            this.showMessage = true;
            this.errorMessage = error.response.data.message;
          });
      });
    },
  },
};
</script>