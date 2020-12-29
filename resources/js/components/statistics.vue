<template>
  <div>
    <b-container class="bv-example-row bv-example-row-flex-cols">
      <b-row align-v="center">
        <b-col>
          <div>
            <div class="row">
                <div class="col mb-4 text-center">
                    <h6>Number of Users</h6>
                    <div class="card text-center container d-flex align-items-center justify-content-center">
                        <span class="text-center" style="font-size: 36px; color: #008ffb">{{ numberOfUsers }}<i class="fas fa-user fa-xs"></i></span>
                    </div>
                </div>
                <div class="col mb-4 text-center">
                    <h6>Number of Orders (last week)</h6>
                    <div class="card text-center container d-flex align-items-center justify-content-center">
                        <span class="text-center" style="font-size: 36px; color: #008ffb">{{ numberoforders }}<i class="fas fa-exchange-alt fa-xs" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="col mb-4 text-center">
                    <h6>Amount in Transactions (last week)</h6>
                    <div class="card text-center container d-flex align-items-center justify-content-center">
                        <span class="text-center" style="font-size: 36px; color: #008ffb"> {{ format(revenue) }} €</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-4 text-center">
                    <h6>Customers/Cookers/Deliverers/Managers</h6>
                    <div class="card">
                        <apexcharts
                            class="mt-2"
                            height="200"
                            :options="users_operators_admins.chartOptions"
                            :series="users_operators_admins.series"
                        ></apexcharts>
                    </div>
                </div>
                <div class="col mb-4 text-center">
                    <div class="col mb-4 text-center">
                    <h6>Average € Spent per User</h6>
                    <div class="card text-center container d-flex align-items-center justify-content-center">
                        <span class="text-center" style="font-size: 36px; color: #008ffb"> {{ format(averageSpentCostumers) }} €</span>
                    </div>
                </div>
                </div>
                <div class="col mb-4 text-center">
                    <h6>Top Type Selled Products</h6>
                    <div class="card">
                        <apexcharts
                            class="mt-2"
                            height="200"
                            :options="type_of_product.chartOptions"
                            :series="type_of_product.series"
                        ></apexcharts>
                    </div>
                </div>
            </div>
            <div class="col text-center" style="width: 100%; min-width: 400px;">
                <h6>Amount in Transactions</h6>
                <div class="card text-center">
                <apexcharts class="mt-2" :options="yearMovsByMonth" :series="yearMovsByMonth.series"></apexcharts>
                </div>
            </div>
            <div class="mb-3">
              <small class="text-secondary">Expenses and incomes represent last year</small>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
// https://github.com/gilbitron/laravel-vue-pagination/tree/master/src
// Vue.component("pagination", require("laravel-vue-pagination"));

// import store from "../../stores/global-store";

import Vue from 'vue';
// https://github.com/apexcharts/vue-apexcharts
import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)


export default {
  name: "teste",
  props: ["user", "wallet", "movements"],
  userID: null,
  data() {
    return {
      usr: {},
      movs: {},
      numberOfUsers: 0,
      numberoforders: 0,
      revenue: 0,
      wall: {},
      myYear: {
        chartOptions: {
          chart: {
            id: "vuechart-year",
            height: 350,
            type: "line",
            zoom: {
              enabled: false
            }
          },
          stroke: {
            curve: "straight"
          },
          grid: {
            row: {
              colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
              opacity: 0.7
            }
          },
          xaxis: {
            categories: [
              "jan",
              "fev",
              "mar",
              "apr",
              "may",
              "june",
              "july",
              "aug",
              "sept",
              "oct",
              "nov",
              "dec"
            ]
          }
        },
        series: [
          {
            name: "Euros",
            data: [30, 40, 45, 50, 49, 60, 70, 91, 2, 3, 1, 3]
          }
        ]
      },
      users_operators_admins: {
        chartOptions: {
          chart: {
            id: "users_operators_admins",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false
            }
          },
          labels: ["customers", "cookers","deliverers","managers"]
        },
        series: [0, 0, 0]
      },
      type_of_product:{
          chartOptions: {
          chart: {
            id: "type_of_product",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false
            }
          },
          labels: ["Drink", "Dessert", "Hot Dish", "Cold Dish"]
        },
        series: [0, 0]
      },
      averageSpentCostumers:{
          chartOptions: {
          chart: {
            id: "averageSpentCostumers",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false
            }
          },
          labels: ["Bank Transfer", "MB Payent", "Credit"]
        },
        series: [0, 0, 0]
      },
      yearMovsByMonth: {
        series: [
          {
            name: "Debits",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          },
          {
            name: "Credits",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          }
        ],
        chart: {
          height: 350,
          type: "area"
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        xaxis: {
          type: "string",
          categories: [
            "jan",
            "fev",
            "mar",
            "apr",
            "may",
            "june",
            "july",
            "aug",
            "sept",
            "oct",
            "nov",
            "dec"
          ]
        },
        tooltip: {
          x: {
            format: "dd/MM/yy HH:mm"
          }
        }
      }
    };
  },
  methods: {
    format(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },

    getUserStats: function() {
      axios.get("api/numberofusers").then(response => {
        this.numberOfUsers = response.data.total;
        //  console.log(response.data);
        this.users_operators_admins.series = [
            response.data.customers,
            response.data.cookers,
            response.data.deliverers,
            response.data.managers
        ];
        // console.log(this.users_operators_admins.series);
      });
        axios
        .get("api/numberoforders")
        .then(response => {
            this.numberoforders = response.data;
            //console.log("Number of orders: " + this.numberoforders);
        });

        axios
        .get("api/revenue")
        .then(response => {
            this.revenue = response.data;
            //console.log("Amount in orders: " + this.revenue);
        });
    },
    getTypeOfCategoryStats: function() {
      axios.get("api/typeofcategory").then(response => {
console.log(response);
        this.type_of_product.series = [
            response.data.drink,
            response.data.dessert,
            response.data.hotdish,
            response.data.colddish
        ];
      });
    },

    teste: function() {
      axios.get("api/numberoforders").then(response => {
        console.log(response);
      });
    },
    /////////////////////
    getAverageSpentCustomer: function() {
      axios.get("api/averageSpentCustomer").then(response => {
          console.log(response)
        this.averageSpentCostumers = response.data;
      });
    },
    getMovements: function() {
      axios
        .get("api/movements")
        .then(response => {
          this.movs = response.data.data;
          //console.log(this.movs);
        });
    },
    getStats: function() {
      axios.get("api/movementspermonth").then(response => {
          //console.log("STATS: " + response.data);
        var series = [
          {
            name: "Debits",
            data: response.data.data.debits
          },
          {
            name: "Credits",
            data: response.data.data.credits
          }
        ];

        this.yearMovsByMonth.series = series;
      });
    },
    viewAllMyMovements() {
      this.$router.push('/my/movements/');
    }
  },
  mounted() {
    this.userID = 20;
    //
    this.getUserStats();
    
    
    this.getTypeOfCategoryStats();
    this.getAverageSpentCustomer();
    //
    //this.getMovements();
    // this.getStats();
  },
  components: {
    apexcharts: VueApexCharts
  }
};
</script>
<style scoped>
.img-usr-container {
  width: 40px;
  height: 40px;
}
</style>
