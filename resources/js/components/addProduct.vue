<template>
  <div>
    <!-- <nav-bar v-if="user && user.type == 'EM'"></nav-bar> -->
    <b-container class="bv-example-row bv-example-row-flex-cols">
      <b-row align-h="center">
        <b-col>
          <div class="card mb-0 p-4" style="width: 100%; min-width: 400px">
            <div class="container mb-5">
              <b-jumbotron
                v-if="!logged"
                header="Food@Home"
                lead="Add Product"
                class="text-center mt-2"
                style="background: none !important"
              ></b-jumbotron>

              <b-form @submit="onSubmit" @reset="onReset" v-if="show">

                <b-form-group label="Name" label-for="name">
                  <b-form-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Name"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Type" label-for="type">
                  <span>{{this.form.type}}</span>
                  <b-dropdown
                    id="type"
                    v-model="form.type"
                    text="Type"
                    variant="primary"
                    class="m-2"
                  >

                    <b-dropdown-item @click="changeType('drink')"
                      >Drink</b-dropdown-item
                    >
                    <b-dropdown-item @click="changeType('dessert')"
                      >Dessert</b-dropdown-item
                    >
                    <b-dropdown-item @click="changeType('hot dish')"
                      >Hot Dish</b-dropdown-item
                    >
                    <b-dropdown-item @click="changeType('dish')"
                      >Dish</b-dropdown-item
                    >
                    <b-dropdown-item @click="changeType('cold dish')"
                      >Cold Dish</b-dropdown-item
                    >
                  </b-dropdown>
                  
                </b-form-group>

                <b-form-group label="Description" label-for="description">
                  <b-form-input
                    id="description"
                    v-model="form.description"
                    type="text"
                    required
                    placeholder="Description"
                  ></b-form-input>
                </b-form-group>


                <b-form-group label="Price" label-for="price">
                  <b-form-input
                    id="price"
                    v-model="form.price"
                    type="text"
                    required
                    placeholder="Price"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Photo">
                  <b-form-file
                    enctype="multipart/form-data"
                    v-model="form.photo"
                    :state="Boolean(form.photo)"
                    placeholder="Pick a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  ></b-form-file>
                </b-form-group>

                <div class="alert alert-danger" role="alert" v-if="showMessage">
                  {{ errorMessage }}
                </div>
                <b-button type="submit" variant="primary">Add Product</b-button>
                <b-button to="/products" variant="secondary">Cancel</b-button>
              </b-form>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
export default {
  data() {
    return {
      user: {},
      errorMessage: "",
      showMessage: false,
      logged: false,
      form: {
        name: "",
        type: "Select a type",
        description: null,
        price: null,
        photo: null,
      },

      options: [
        // { value: "a", text: "Administrator" },
        // { value: "o", text: "Operador" }
      ],

      show: true,
    };
  },
  methods: {
changeType: function (type) {
      this.form.type = type;
},
    onSubmit(evt) {
      evt.preventDefault();


      const fd = new FormData();
      fd.append("name", this.form.name);
      fd.append("type", this.form.type);
      fd.append("description", this.form.description);
      fd.append("price", this.form.price);
      if (this.form.photo != null) {
        fd.append("photo", this.form.photo);
      }
      axios
        .post("api/newProduct", fd, {
          headers: {
            "Content-type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log(response);
          this.$router.push("/products");
        })
        .catch((error) => {
          console.log(error.response);
          this.showMessage = true;
          this.errorMessage =error.response.data.error;
          //this.$refs.msgref.showAlert(error.response.data.error, "danger");
        });

      //alert(JSON.stringify(this.form));
    },

    onReset(evt) {
      evt.preventDefault();
      // Reset our form values
      this.form.name = "";
      this.form.type = "";
      this.form.description = null;
      this.form.price = "";
      this.form.photo = null;
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
  },
  mounted() {

  },
  components: {},
};
</script>