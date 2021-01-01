<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>
      <br><br><br>

      <input v-model="searchTerm" placeholder="Filtrar por nome" />

      <b-dropdown
        id="dropdown-1"
        text="Filter By Role"
        variant="primary"
        class="m-2"
      >
        <b-dropdown-item @click="changeType('EM')">Managers</b-dropdown-item>
        <b-dropdown-item @click="changeType('EC')">Cookers</b-dropdown-item>
        <b-dropdown-item @click="changeType('ED')">Deliverers</b-dropdown-item>
        <b-dropdown-item @click="changeType('C')">Customers</b-dropdown-item>
        <b-dropdown-item @click="changeType('')">All</b-dropdown-item>
      </b-dropdown>

      <b-button
        id="show-btn"
        v-if="$store.state.user != null && $store.state.user.type == 'EM'"
        href="/#/addUser"
        class="btn btn-sm btn-info"
        >Add User</b-button
      >

      <b-table
        class="table table-striped"
        id="my-table"
        :items="filteredUsers"
        :per-page="perPage"
        :current-page="currentPage"
        small
        :fields="fields"
      >
        <template #cell(photo)="data">
          <img
            class="img-usr-container"
            :src="'storage/fotos/' + data.item.photo"
            height="50"
            width="50"
          />
        </template>
        <template #cell(actions)="data">
          <button
            v-if="$store.state.user != null && $store.state.user.type == 'C'"
            class="btn btn-sm btn-success"
            v-on:click.prevent="addToShoppingCart(data.item)"
          >
            Add
          </button>
          <button
            v-if="
              $store.state.user != null &&
              $store.state.user.type == 'EM' &&
              data.item.type != 'C'
            "
            class="btn btn-sm btn-success"
            v-on:click.prevent="editUsers(data.item)"
          >
            Edit
          </button>
        </template>

        <template #cell(actions1)="data">
          <button
            v-if="
              $store.state.user != null &&
              $store.state.user.type == 'EM' &&
              $store.state.user.id != data.item.id
            "
            class="btn btn-sm btn-danger"
            v-on:click.prevent="deleteUser(data.item.id, data.item.type)"
          >
            Delete
          </button>
          <button
            v-if="
              $store.state.user != null &&
              data.item.blocked == 0 &&
              $store.state.user.type == 'EM' &&
              $store.state.user.id != data.item.id
            "
            class="btn btn-sm btn-warning"
            v-on:click.prevent="blockUser(data.item.id)"
          >
            Block
          </button>
          <button
            v-if="
              $store.state.user != null &&
              data.item.blocked == 1 &&
              $store.state.user.type == 'EM' &&
              $store.state.user.id != data.item.id
            "
            class="btn btn-sm btn-dark"
            v-on:click.prevent="unblockUser(data.item.id)"
          >
            Unblock
          </button>
        </template>

        <template #cell(type)="data">
          <span
            v-if="data.item.type == 'EC'"
            class="badge badge-pill badge-info text-center"
            >Cooker</span
          >
          <span
            v-if="data.item.type == 'C'"
            class="badge badge-pill badge-danger text-center"
            >Customer</span
          >
          <span
            v-if="data.item.type == 'ED'"
            class="badge badge-pill badge-success text-center"
            >Delivery Man</span
          >
          <span
            v-if="data.item.type == 'EM'"
            class="badge badge-pill badge-primary text-center"
            >Manager</span
          >
        </template>
      </b-table>

      <p class="mt-3">Current Page: {{ currentPage }}</p>
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
      ></b-pagination>

      <edit-users
        :userEdit="userToEdit"
        v-if="userToEdit"
        @updated="updateTable"
        @canceled="hideEdit"
      >
      </edit-users>
    </div>
  </div>
</template>

<script>
import NavBarComponent from "./navBar";
import NavBar from "./navBar";
import editUsers from "./managerEditUsers";

export default {
  data: function () {
    return {
      user: null,
      users: [],
      userToEdit: null,
      //filteredUsers:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
      fields: [
        { key: "id", label: " " },
        "photo",
        "name",
        "email",
        "type",

        //   {
        //   key: "price",
        //   label: "Price",
        //   formatter: (value, key, item) => {
        //     return value + "€";
        //   },},

        { key: "actions", label: " " },
        { key: "actions1", label: " " },
      ],
    };
  },
  methods: {
    changeType: function (type) {
      this.searchType = type;
    },

    getUsers: function () {
      axios.get("api/users?page=" + this.currentPage).then((response) => {
        console.log(response.data.data);
        this.users = response.data.data;
        this.rows = this.users.length;
      });
    },

    updateTable: function (userEdit) {
      console.log("aqui estou eu cheio de pinta");
      console.log(this.filteredUsers[0]);
      this.userToEdit = false;
      console.log("antes");
      const index = this.filteredUsers.findIndex(
        (item) => item.id === userEdit.id
      );
      console.log(index);
      this.filteredUsers[index].name = userEdit.name;
      this.filteredUsers[index].type = userEdit.type;
      this.filteredUsers[index].email = userEdit.email;
      this.filteredUsers[index].photo = userEdit.photo_url;
    },

    hideEdit: function () {
      this.userToEdit = false;
    },

    editUsers: function (item) {
      this.userToEdit = item;
    },

    deleteUser: function (id, type) {
      axios.put("api/deleteUser/" + id + "/" + type).then((response) => {
        window.location.reload(true);
        
      });
    },
    blockUser: function (id) {
      axios.put("api/blockUser/" + id).then((response) => {
        const index = this.filteredUsers.findIndex(
        (item) => item.id === id);
        // window.location.reload(true);
        this.filteredUsers[index].blocked = 1;
        this.$socket.emit("user_blocked", id);
      });

    },
    unblockUser: function (id) {
      axios.put("api/unblockUser/" + id).then((response) => {
           const index = this.filteredUsers.findIndex(
        (item) => item.id === id);
        // window.location.reload(true);
        this.filteredUsers[index].blocked = 0;
      });
    },
  },

  mounted() {
    this.getUsers();

    console.log(this.$store.state.user);
  },
  computed: {
    filteredUsers() {
      var filteredUsers = this.users.filter((user) => {
        if (this.searchType != "") {
          return (
            user.type.toLowerCase() == this.searchType.toLowerCase() &&
            user.name.toLowerCase().includes(this.searchTerm.toLowerCase())
          );
        } else {
          return user.name
            .toLowerCase()
            .includes(this.searchTerm.toLowerCase());
        }
      });

      let orderedUsers = filteredUsers.sort((a, b) => {
        return b - a;
      });
      this.rows = orderedUsers.length;
      return orderedUsers;
    },
  },
  components: {
    navBar: NavBarComponent,
    "edit-users": editUsers,
  },
};
</script>

<style scoped>
</style>

