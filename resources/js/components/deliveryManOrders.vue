<template>
  <div>
    <div class="overflow-auto container align-middle">
      <div>
        <nav-bar></nav-bar>
      </div>

      <br /><br /><br />

      <b-jumbotron
        v-if="this.orders.length == 0"
        lead="You don't have orders at the moment"
        class="text-center mt-2"
        style="background: none !important"
      ></b-jumbotron>

      <div v-if="this.orders.length > 0">
        <b-table
          class="table table-striped"
          id="my-table"
          :items="orders"
          :per-page="perPage"
          :current-page="currentPage"
          small
          :fields="fields"
        >
          <template #cell(actions)="data">
            <b-button
              id="show-btn"
              v-if="$store.state.user != null && $store.state.user.type == 'ED'"
              class="btn btn-sm btn-info"
              @click.prevent="
                show = true;
                seeOrderDetails(data.item.id);
              "
              >See details</b-button
            >
            
          </template>



          <template  #cell(actions1)="data">
           
            
                       <b-button
              id="show-btn"
              v-if="$store.state.user != null && $store.state.user.type == 'ED' && showFlag == 0 "
              class="btn btn-sm btn-success"
              @click.prevent="changeStatusToTransit(data.item.id)"
              >Deliver</b-button
            > 

            
                       <b-button
              id="show-btn"
              v-if="$store.state.user != null && $store.state.user.type == 'ED' && showFlag == 1"
              class="btn btn-sm btn-success"
              show = true;
              @click.prevent="changeStatusToDelivered(data.item.id)"
              >DeliveREDDDDDDD</b-button
            > 
               
          </template>


           <template v-if="show == true"  #cell(tempo)>
           
            
             
              
              
               
          </template>


          
        





          <template #cell(customer_photo)="data">
          <img
            class="img-usr-container"
            :src="'storage/fotos/' + getPhoto(data.item.customer_photo)"
            height="50"
            width="50"
          />

       

        </template>
        </b-table>

        <p class="mt-3">Current Page: {{ currentPage }}</p>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="my-table"
        ></b-pagination>
        <b-modal id="my-modal" v-model="show">
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
          
      </div>
    </div>
  </div>
</template>



<script>
import NavBarComponent from "./navBar";
import NavBar from "./navBar.vue";


export default {
  data: function () {
    return {
      user: null,
      showFlag:0,
      current_status_at: null,
      products: [],
      orders: [],
      ordersAUX: [],
      //filteredProducts:[],
      currentPage: 1,
      perPage: 10,
      rows: 0,
      searchTerm: "",
      searchType: "",
      show: false,
      photo_url:"user_undefined.png",
      fields: [
        "id",
        "opened_at",
        "customer_name",
        "customer_address",
        "customer_phone",
        "customer_email",
         "customer_photo", 
        
        {
          key: "notes",
          label: "Notes",
          formatter: (value, key, item) => {
            if (value == null) {
              value = "No notes";
            }
            return value;
          },
        },

         {
          key: "tempo",
          label: "Time",
          formatter: (stempo, key, item) => {
            stempo = this.calcula_data(item.current_status_at);
            return Math.floor(stempo / 60) + " min";
          },
        }, 
        { key: "actions", label: " " },
        { key: "actions1", label: " " },
      ],
      fields1: ["product_name", "quantity", "product_description"],
      orderItems: [],
      
    };
  },
  methods: {
    getOrders: function () {
      axios
        .get(
          "api/deliveryManOrders/" /*+
            this.$store.state.user.id*/ +
            "?page=" +
            this.currentPage
        )
        .then((response) => {
            console.log(response.data);
            if(response.data[0]['status'] == 'T'){
            this.showFlag = 1;
            }else{
              this.showFlag = 0;
            }
            /* for (let index = 0; index < response.data.length; index++) {
                 if(response.data[index].customer_photo!=null){
      this.photo_url = response.data[index].customer_photo;
                
            }
              

      } */
          this.orders = response.data;
          this.rows = this.orders.length;
      
        });

        

    },

    getPhoto(photo){
            if(photo != null){
                return photo;
            }
            return this.photo_url;
        },

    seeOrderDetails: function (id) {
      axios.get("api/orderItems/" + id).then((response) => {
        this.orderItems = response.data;
        console.log(response.data);
      
      });
    },

    


    changeStatusToTransit: function (id) {
       axios
        .put("api/assignDeliveryMan/" + id)
        .then((response) => {
          console.log(response.data);
           this.$socket.emit("deliveryMan_ready", id);
          this.orders = [];
          this.orders[0] = response.data;
           console.log(this.orders);
           this.showFlag = 1;
          
          
            })
            .catch((error) => {
              console.log("erro aquiiXXXXXXXXXXXXX");
              console.log(error);
       
        });
       
      
    },



    changeStatusToDelivered: function (id) {
      axios
        .put("api/changeOrderTtoD/" + id)
        .then((response) => {
          console.log(response);
           this.$socket.emit("order_delivered", id);
           this.getOrders();
          
            })
            .catch((error) => {
              console.log("erro aquii");
              console.log(error);
       
        });
      
    },



    calcula_data: function (current_status_time) {
      var diff = Math.abs(
        new Date() - new Date(current_status_time.replace(/-/g, "/"))
      );
      return Math.floor(diff / 1000);
    },
  },
  mounted() {
    this.getOrders();
    // console.log(this.$orders[id].current_status_at);
    console.log(this.$store.state.user);
  },

  computed: {
   
  },
  components: {
    navBar: NavBarComponent,
  },

  sockets:{
       deliveryMan_ready(iD) {
          const index = this.orders.findIndex(item => item.id === iD);
          this.orders.splice(index,1);
         // array.splice(index, 1);
          //this.orders[index].status='T';
        },
    
},
};




</script>

<style scoped>
</style>