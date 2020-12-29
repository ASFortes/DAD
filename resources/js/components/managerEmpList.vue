<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <br /><br /><br />


     <input v-model="searchTerm" placeholder="Filtrar por nome" />

      <b-dropdown id="dropdown-1" text="Filter By Role" variant="primary" class="m-2">
        <b-dropdown-item @click="changeType('EM')">Managers</b-dropdown-item>
        <b-dropdown-item @click="changeType('EC')">Cookers</b-dropdown-item>
        <b-dropdown-item @click="changeType('ED')">Deliverers</b-dropdown-item>
        
        <b-dropdown-item @click="changeType('')">All</b-dropdown-item>
      </b-dropdown>

      <b-dropdown id="dropdown-2" text="Filter By Work Action" variant="primary" class="m-2">
        <b-dropdown-item @click="changeState('offline')">Offline</b-dropdown-item>
        <b-dropdown-item @click="changeState('available')">Available</b-dropdown-item>
        <b-dropdown-item @click="changeState('cooking')">Cooking</b-dropdown-item>
        <b-dropdown-item @click="changeState('delivering')">Delivering</b-dropdown-item>
        
        <b-dropdown-item @click="changeState('')">All Status</b-dropdown-item>
      </b-dropdown>



      <!-- B TABLE COM LISTA PRINCIPAL DE TRABALHADORES -->
       <!-- B TABLE COM LISTA PRINCIPAL DE TRABALHADORES -->
        <!-- B TABLE COM LISTA PRINCIPAL DE TRABALHADORES -->

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
            :src="'storage/fotos/' + data.item.photo_url"
            height="50"
            width="50"
          />
        </template>
        <template #cell(actions)="data">
          <span
            v-if="data.item.logged_at == null"
            class="badge badge-pill badge-dark text-center"
            >offline</span
          >

          <span
            v-if="data.item.logged_at != null && data.item.available_at != null"
            class="badge badge-pill badge-dark text-center"
            >available</span
          >

          <span
            v-if="
              data.item.available_at == null &&
              data.item.logged_at != null &&
              data.item.type == 'EC'
            "
            class="badge badge-pill badge-dark text-center"
            >preparing</span
          >

          <span
            v-if="
              data.item.available_at == null &&
              data.item.logged_at != null &&
              data.item.type == 'ED'
            "
            class="badge badge-pill badge-dark text-center"
            >delivering</span
          >
          <!-- <button v-if="$store.state.user!=null && $store.state.user.type =='C'"  class="btn btn-sm btn-success"  v-on:click.prevent="addToShoppingCart(data.item)">Add</button>
        <button v-if="$store.state.user!=null && $store.state.user.type =='EM' && data.item.type!='C'" class="btn btn-sm btn-success" v-on:click.prevent="editUsers(data.item)">Edit</button> -->
        </template>

        <template #cell(actions1)="data">
          <button
            v-if="
              $store.state.user != null &&
              $store.state.user.type == 'EM' &&
              $store.state.user.id != data.item.id &&
              data.item.available_at == null && data.item.logged_at != null
              
            "
            class="btn btn-sm btn-success"
            v-on:click.prevent="seeOrderDetails(data.value); show = true;"
          >
            seeOrderDetails
          </button>
        </template>

        <template #cell(type)="data">
          <span
            v-if="data.item.type == 'EC'"
            class="badge badge-pill badge-info text-center"
            >Cooker</span
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

      <!-- ACABA AQUI B TABLE DE TRABALHADORES -->

      <p class="mt-3">Current Page: {{ currentPage }}</p>
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
      ></b-pagination>
      <b-modal id="my-modal" v-model="show">
          <!-- B TABLE DE VER DETALHES -->
            <b-table
              id="my-table"
              :items="orderItems"
              :per-page="perPage"
              :current-page="currentPage"
              small
              :fields="fields1"
              striped
              hover
              responsive="sm"
            >
            </b-table>
          </b-modal>



            <!-- B TABLE DE LISTA DE ORDERS ACTIVAS -->
            <!-- B TABLE DE LISTA DE ORDERS ACTIVAS -->

             <br /><br /><br />


            

      <b-dropdown id="dropdown-3" text="Filter By Status" variant="primary" class="m-2">
        <b-dropdown-item @click="changeStatus('H')">Holding</b-dropdown-item>
        <b-dropdown-item @click="changeStatus('P')">Preparing</b-dropdown-item>
        <b-dropdown-item @click="changeStatus('R')">Ready</b-dropdown-item>
         <b-dropdown-item @click="changeStatus('T')">Transit</b-dropdown-item>
       
        <b-dropdown-item @click="changeStatus('')">All</b-dropdown-item>
      </b-dropdown>



            <b-table
        class="table table-striped"
        id="my-table"
        :items="filteredActiveOrdersStatus"
        :per-page="perPage"
        :current-page="currentPage2"
        small
        :fields="fields2"
      >


        <template #cell(actions1)="data">
          <button
            v-if="
              $store.state.user != null &&
              $store.state.user.type == 'EM' &&
              $store.state.user.id != data.item.id 
              //data.item.available_at == null && data.item.logged_at != null
              
            "
            class="btn btn-sm btn-success"
            v-on:click.prevent="seeOrderDetails(data.item.id); show = true;"
          >
            seeOrderDetails
          </button>
        </template>


        </b-table>

     <p class="mt-3">Current Page: {{ currentPage2 }}</p>
      <b-pagination
        v-model="currentPage2"
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
      orders: [],
      activeOrders: [],
      userToEdit: null,
       show: false,
      //filteredUsers:[],
      currentPage: 1,
      currentPage2: 1,
      perPage: 10,
      rows: 0,
      rows2: 0,
      searchTerm: "",
      searchType: "",
      searchState: "",
      searchStatus: "",
      fields: [
        { key: "id", label: "ID",  sortable: true },
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

        { key: "actions", label: "Status" },

       

        {
          key: "order_id",
          label: "Order ID",
          formatter: (order_id_aux, key, item) => {
            if (item.logged_at != null && item.available_at == null) {
              for (let index = 0; index < this.orders.length; index++) {
                if (item.id == this.orders[index].prepared_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }

                if (item.id == this.orders[index].delivered_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }
              }
            } else {
              return "";
            }
          },
        },

        {
          key: "actions1",
          label: " ",
          formatter: (order_id_aux, key, item) => {
            if (item.logged_at != null && item.available_at == null) {
              for (let index = 0; index < this.orders.length; index++) {
                if (item.id == this.orders[index].prepared_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }

                if (item.id == this.orders[index].delivered_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }
              }
            } else {
              return "";
            }
          },
        },
      ],
      fields1: ["product_name", "quantity", "product_description"],
      orderItems: [],
        ///////////////////////////////////LISTA DE ORDERS ATIVAS//////////////////////////////
      fields2:[
         // { key: "date", sortable: true },
          "id",
          "status",
         // "name",
         {
           key: "name",
           label: "Name",
           formatter: (stempo, key, item) => {
             // if (item.logged_at != null) {
             //   return item.logged_at;
             // } else {
             //   return "";
             // }
             if (item.name == null){
                 return "Nao Atribuido"
             }
             return item.name;
           },
         },

          {key:"current_status_at",sortable: true},
         

        //    {
        //   key: "tempo",
        //   label: "Status Time", 
        //   formatter: (stempo, key, item) => {
        //     // if (item.logged_at != null) {
        //     //   return item.logged_at;
        //     // } else {
        //     //   return "";
        //     // }
        //     return item.current_status_at
        //   },
        //   sortable: true,
        // },

        {
          key: "actions1",
          label: "Detalhes ",
          formatter: (order_id_aux, key, item) => {
            if (item.logged_at != null && item.available_at == null) {
              for (let index = 0; index < this.orders.length; index++) {
                if (item.id == this.orders[index].prepared_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }

                if (item.id == this.orders[index].delivered_by) {
                  order_id_aux = this.orders[index].id;
                  return order_id_aux;
                }
              }
            } else {
              return "";
            }
          },
        },
          
        ],
    };
  },
  methods: {
    changeType: function (type) {
      this.searchType = type;
    },


     changeState: function (state) {
      this.searchState = state;
    },

     changeStatus: function (status) {
      this.searchStatus = status;
    },

    getUsers: function () {
      axios.get("api/usersEmp?page=" + this.currentPage + this.currentPage2).then((response) => {
        console.log("aparece csds");
        console.log(response.data);
        this.users = response.data.users;
         console.log(this.users[0].name);
        this.orders = response.data.orders;
        this.activeOrders = response.data.ordersActive;
        //console.log(this.activeOrders[0].name[0].name);
        this.rows = this.users.length;
       this.addStateToUser();
        console.log(this.users);
      });
    },

    calcula_data: function (current_status_time) {
      console.log(current_status_time);
      var diff = Math.abs(
        new Date() - new Date(current_status_time.replace(/-/g, "/"))
      );
      return Math.floor(diff / 1000);
    },

    seeOrderDetails: function (id) {
      console.log(id);
      axios.get("api/orderItems/" + id).then((response) => {
        this.orderItems = response.data;
        console.log(response.data);
      });
    },

      updateTable: function(userEdit){
    console.log("aqui estou eu cheio de pinta");
      console.log(this.filteredUsers[0]);
      this.userToEdit=false;
      console.log("antes");
      const index = this.filteredUsers.findIndex(item => item.id === userEdit.id);
      console.log(index);
      this.filteredUsers[index].name=userEdit.name;
      this.filteredUsers[index].type=userEdit.type;
     // this.filteredUsers[index].email=userEdit.email;
      this.filteredUsers[index].photo=userEdit.photo_url;
    },


     updateTableOrder: function(orderEdit){
    console.log("aqui estou eu cheio de pinta");
      console.log(this.filteredActiveOrdersStatus[0]);
      this.userToEdit=false;
      console.log("antes");
      const index = this.filteredActiveOrdersStatus.findIndex(item => item.id === orderEdit.id);
      console.log(index);
      this.filteredActiveOrdersStatus[index].status=orderEdit.status;
      //this.filteredActiveOrdersStatus[index].type=userEdit.type;
     // this.filteredUsers[index].email=userEdit.email;
     
    },


    addStateToUser: function () {
      for (let index = 0; index < this.users.length; index++) {
          if(this.users[index].logged_at == null){
              //cart.push({element: element});
              this.users[index].state = 'offline';
          }
           if(this.users[index].available_at != null && this.users[index].logged_at != null){
              this.users[index].state = 'available'
          }
         
           if(this.users[index].logged_at != null && this.users[index].available_at == null && this.users[index].type == 'EC') {
              this.users[index].state = 'cooking'
          }
          if(this.users[index].logged_at != null && this.users[index].available_at == null && this.users[index].type == 'ED'){
              this.users[index].state = 'delivering'
          }
          

      }
      
    },
     
  },

  mounted() {
    this.getUsers();
    

    console.log(this.users);
  },
  computed: {
     filteredUsers() {
      var filteredUsers = this.users.filter((user) => {
           //console.log(this.users);
        return (
          user.type.toLowerCase().includes(this.searchType.toLowerCase()) &&
          user.state.toLowerCase().includes(this.searchState.toLowerCase()) &&
          user.name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });

      let orderedUsers = filteredUsers.sort((a, b) => {
        return b - a;
      });
      this.rows = orderedUsers.length;
      return orderedUsers;
    
    },
    
    filteredActiveOrdersStatus() {
      var filteredUsers = this.activeOrders.filter((order) => {
        return (
         
         order.status.toLowerCase().includes(this.searchStatus.toLowerCase())
          
        );
      });

      let orderedUsers = filteredUsers.sort((a, b) => {
        return b - a;
      });
      this.rows = orderedUsers.length;
      return orderedUsers;
    
    }, 

    
  },


    sockets:{
       create_user_socket(iD) {
          //const index = this.users.findIndex(item => item.id === iD);
          this.users.splice(this.users.length,0,iD);
          //this.$set(this.users,index,iD);
         // this.$set(this.users,index);
         // array.splice(index, 1);
          //this.orders[index].status='T';
        },
        ////////////////A DAR/////////////////////
        cooker_ready(iD) {
          const index = this.activeOrders.findIndex(item => item.id === iD);
          this.activeOrders[index].status='P';
          //window.location.reload(true);
        },

        //////////////////A DAR ////////////////////
        order_cooked(iD) {
          const index = this.activeOrders.findIndex(item => item.id === iD);
          this.activeOrders[index].status='R';
          //window.location.reload(true);
        },
        ////////////A DAR /////////////////////
        deliveryMan_ready(iD) {
          const index = this.activeOrders.findIndex(item => item.id === iD);
          this.activeOrders[index].status='T';
          
        },
        ////////////A DAR /////////////////////
        change_Status_To_D(iD) {
          const index = this.activeOrders.findIndex(item => item.id === iD);
           this.activeOrders.splice(index,1);
            window.location.reload(true);
        },
         user_cooking(iD) {
          const index = this.users.findIndex(item => item.id === iD);
           this.users[index].state = 'cooking'
           window.location.reload(true);
           // this.addStateToUser();
           
        },
        //////////////////////done/////////////////////
        user_delivering(iD) {
          const index = this.users.findIndex(item => item.id === iD);
           this.users[index].state = 'delivering';
           window.location.reload(true);
           // this.addStateToUser();
           
        },
        ///////done/////////////
         user_offline(iD) {
            
          const index = this.users.findIndex(item => item.id === iD);
           this.users[index].state = 'offline';
            window.location.reload(true);
           
            //this.addStateToUser();
           
        },
        ////////////done///////////////////
        user_available(iD) {
          const index = this.users.findIndex(item => item.id === iD);
           this.users[index].state = 'available';
            window.location.reload(true);
            //this.addStateToUser();
           
        },

        //  cook_back_avaiable(iD) {
        //   const index = this.users.findIndex(item => item.id === iD);
        //    this.users[index].state = 'available';
        //     window.location.reload(true);
        //     //this.addStateToUser();
           
        // },

        new_order(iD) {
        window.location.reload(true);
        },

    
},



  components: {
    navBar: NavBarComponent,
    
  },
};
</script>

<style scoped>
</style>

