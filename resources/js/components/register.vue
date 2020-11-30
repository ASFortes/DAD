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
                lead="REGISTER"
                class="text-center mt-2"
                style="background: none !important"
              ></b-jumbotron>

              <b-form @submit="onSubmit" @reset="onReset" v-if="show">
                <!--<b-form-group label-for="photo">
                  <b-img
                    center
                    thumbnail
                    fluid
                    width="125"
                    height="125"
                    :src="'api/fotos/default.jpg'"
                    alt="Photo"
                  ></b-img>
                </b-form-group>-->

                <!-- <div v-if="user && user.type == 'EC'" class="form-group">
                  <label for="type">Type</label>
                  <b-form-select v-model="form.type" :options="options"></b-form-select>
                </div> -->

                <b-form-group label="Name" label-for="name">
                  <b-form-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Name"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Email" label-for="email">
                  <b-form-input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    placeholder="Email"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Password" label-for="password">
                  <b-form-input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    placeholder="Password"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  label="ConfirmPassword"
                  label-for="confirmpassword"
                >
                  <b-form-input
                    id="confirmpassword"
                    v-model="form.confirmPassword"
                    type="password"
                    required
                    placeholder="Confirm Password"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Phone" label-for="phone">
                  <b-form-input
                    id="phone"
                    v-model="form.phone"
                    type="number"
                    required
                    placeholder="Phone number"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Address" label-for="address">
                  <b-form-input
                    id="address"
                    v-model="form.address"
                    type="text"
                    required
                    placeholder="Endereço"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="NIF" label-for="nif">
                  <b-form-input
                    id="nif"
                    v-model="form.nif"
                    type="text"
                    placeholder="(Optional) NIF "
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Photo">
                  <b-form-file
                    enctype="multipart/form-data"
                    v-model="form.photo"
                    :state="Boolean(form.photo)"
                    placeholder="(Optional) Pick a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  ></b-form-file>
                </b-form-group>

                <!-- <b-form-group label="Password:" label-for="password">
                  <b-form-input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    placeholder="Password"
                  ></b-form-input>
                </b-form-group> -->
                <div class="alert alert-danger" role="alert" v-if="showMessage">
                  {{ errorMessage }}
                </div>
                <b-button type="submit" variant="primary">Register</b-button>
                <b-button to="/login" variant="secondary">Cancel</b-button>
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
        email: "",
        name: "",
        nif: null,
        password: "",
        confirmPassword: "",
        file: null,
        phone: null,
        address:null,
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
    onSubmit(evt) {
      evt.preventDefault();

      // const file = new Blob([this.file]);
      // const formData = new FormData();
      // formData.append("photo", file, this.email + "_photo");
      // this.photo=this.email+"_photo";

      const fd = new FormData();
      fd.append("name", this.form.name);
      fd.append("email", this.form.email);
      fd.append("password", this.form.password);
      fd.append("nif", this.form.nif);
      fd.append("address", this.form.address);
      fd.append("phone", this.form.phone);
      if (this.form.photo != null) {
        fd.append("photo", this.form.photo);
      }

      if (this.password != this.confirmPassword) {
        this.showMessage = true;
        this.errorMessage = "The passwords must match";
      }
      axios
        .post("api/register", fd, {
          headers: {
            "Content-type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error.response);
          this.showMessage = true;
          this.errorMessage =error.response.data.error;
          //this.$refs.msgref.showAlert(error.response.data.error, "danger");
        });

      //alert(JSON.stringify(this.form));
    },

    // uploadPhoto() {
    //   const file = new Blob([this.file]);
    //   const formData = new FormData();
    //   formData.append("photo", this.file, this.nif + "_photo");

    //   axios({
    //     method: "post",
    //     url: "/api/photo",
    //     data: formData,
    //     headers: {
    //       "content-type": `multipart/form-data; boundary?${formData._boundary}`
    //     }
    //   }).then(response => {
    //     this.$refs.msgref.showAlert("Photo saved sucessfully.", "success");
    //   });
    // },
    onReset(evt) {
      evt.preventDefault();
      // Reset our form values
      this.form.email = "";
      this.form.name = "";
      this.form.nif = null;
      this.form.password = "";
      this.form.photo = null;
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
  },
  mounted() {
    axios.get("api/users/me").then((response) => {
      this.user = response.data.data;
    });

    //console.log(this.user);
  },
  components: {},
};
</script>