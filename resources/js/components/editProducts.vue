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
                lead="EDIT PRODUCTS"
                class="text-center mt-2"
                style="background: none !important"
              ></b-jumbotron>

              <b-form @submit="onSubmit" v-if="show">
                <b-form-group label="Name" label-for="name">
                  <b-form-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
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
                  <b-form-textarea
                    id="description"
                    v-model="form.description"
                    type="text"
                    required
                    placeholder="Description"
                    no-resize
                  ></b-form-textarea>
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
                  <img
                    class="img-usr-container rounded-circle"
                    :src="'storage/products/' + form.photo_url"
                    height="50"
                    width="50"
                  />
                  <b-form-file
                    enctype="multipart/form-data"
                    v-model="form.photo"
                    :state="Boolean(form.photo)"
                    placeholder="(Optional) Pick a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  ></b-form-file>
                </b-form-group>

                <div class="alert alert-danger" role="alert" v-if="showMessage">
                  {{ errorMessage }}
                </div>
                <b-button type="submit" variant="primary">Update</b-button>
                <b-button @click="cancel()" to="/products" variant="secondary">Cancel</b-button>
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
  props: ["product"],
  data() {
    return {
      user: {},
      errorMessage: "",
      showMessage: false,
      logged: false,
      form: {
        id: "",
        name: "",
        type: "",
        description: null,
        price: null,
        photo: null,
        photo_url: "user_undefined.png",
      },

      options: [],

      show: true,
    };
  },
  methods: {

    changeType: function (type) {
      this.form.type = type;
    },

    cancel: function () {
      this.$emit("canceled");
    },

    onSubmit(evt) {
      evt.preventDefault();

      const fd = new FormData();
      fd.append("id", this.form.id);
      fd.append("name", this.form.name);
      fd.append("type", this.form.type);
      fd.append("description", this.form.description);
      fd.append("price", this.form.price);
      if (this.form.photo != null) {
        fd.append("photo", this.form.photo);
      }

      axios
        .post("api/updateProducts", fd, {
          headers: {
            "Content-type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log("aqui em cima");
          console.log(response);
          alert("Product updated");
          this.$emit("updated",response.data);

        })
        .catch((error) => {
          console.log(error.response);
          this.showMessage = true;
          this.errorMessage = error.response.data.error;
        });
    },
  },
  mounted() {
    console.log("aqui em baixo menino");
    console.log(this.product);
    this.form.id = this.product.id;
    this.form.name = this.product.name;
    this.form.type = this.product.type;
    this.form.description = this.product.description;
    this.form.price = this.product.price;
    this.form.photo_url = this.product.photo;
    // if (this.product.photo != null) {
      
    // }
  },
  components: {},
};
</script>