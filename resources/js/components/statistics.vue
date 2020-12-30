<template>
  <div>
    <div>
      <nav-bar></nav-bar>
    </div>
    <br /><br /><br />
    <div>
      <b-container class="bv-example-row bv-example-row-flex-cols">
        <b-row align-v="center">
          <b-col>
            <div>
              <div class="row">
                <div class="col mb-4 text-center">
                  <h6>Number of Users</h6>
                  <div
                    class="card text-center container d-flex align-items-center justify-content-center"
                  >
                    <span
                      class="text-center"
                      style="font-size: 36px; color: #008ffb"
                      >{{ numberOfUsers }}<i class="fas fa-user fa-xs"></i
                    ></span>
                  </div>
                </div>
                <div class="col mb-4 text-center">
                  <h6>Number of Orders (last week)</h6>
                  <div
                    class="card text-center container d-flex align-items-center justify-content-center"
                  >
                    <span
                      class="text-center"
                      style="font-size: 36px; color: #008ffb"
                      >{{ numberoforders
                      }}<i
                        class="fas fa-exchange-alt fa-xs"
                        aria-hidden="true"
                      ></i
                    ></span>
                  </div>
                </div>
                <div class="col mb-4 text-center">
                  <h6>Amount in Transactions (last week)</h6>
                  <div
                    class="card text-center container d-flex align-items-center justify-content-center"
                  >
                    <span
                      class="text-center"
                      style="font-size: 36px; color: #008ffb"
                    >
                      {{ format(revenue) }} €</span
                    >
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
                  <h6>Average time of preparation</h6>
                  <div class="card">
                    <apexcharts
                      class="mt-2"
                      height="200"
                      :options="avgtime.chartOptions"
                      :series="avgtime.series"
                    ></apexcharts>
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
              <div class="col mb-4 text-center">
                <div class="col mb-4 text-center">
                  <h6>Average € Spent per User</h6>
                  <div
                    class="card text-center container d-flex align-items-center justify-content-center"
                  >
                    <span
                      class="text-center"
                      style="font-size: 36px; color: #008ffb"
                    >
                      {{ format(averageSpentCostumers) }} €</span
                    >
                  </div>
                </div>
              </div>

              <!-- <div class="col text-center" style="width: 100%; min-width: 400px;">
                <h6>Amount in Transactions</h6>
                <div class="card text-center">
                <apexcharts class="mt-2" :options="spentByMonth" :series="spentByMonth.series"></apexcharts>
                </div>
            </div> -->

              <div id="chart">
                <apexchart
                  type="line"
                  height="350"
                  :options="spentByMonth.chartOptions"
                  :series="spentByMonth.series"
                ></apexchart>
              </div>
              <div class="mb-3">
                <small class="text-secondary">Sales of the past year</small>
              </div>

              <div id="chart_order">
                <apexchart
                  type="line"
                  height="350"
                  :options="ordersByMonth.chartOptions"
                  :series="ordersByMonth.series"
                ></apexchart>
              </div>

              <div class="mb-3">
                <small class="text-secondary">Orders of the past year</small>
              </div>
              
              <div id="chart_order">
                <apexchart
                  type="bar"
                  height="350"
                  :options="totalProducts.chartOptions"
                  :series="totalProducts.series"
                ></apexchart>
              </div>

              <div class="mb-3">
                <small class="text-secondary">Number of products sold in the last year (hover over the bars to see the ID of the product)</small>
              </div>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </div>
  </div>
</template>

<script>
// https://github.com/gilbitron/laravel-vue-pagination/tree/master/src
// Vue.component("pagination", require("laravel-vue-pagination"));

// import store from "../../stores/global-store";
import NavBarComponent from "./navBar";
import NavBar from "./navBar";
import Vue from "vue";

// https://github.com/apexcharts/vue-apexcharts
import VueApexCharts from "vue-apexcharts";
Vue.use(VueApexCharts);

Vue.component("apexchart", VueApexCharts);

export default {
  name: "teste",
  props: [],
  userID: null,
  data() {
    return {
      x: [],
      y:[],
      numberOfUsers: 0,
      numberoforders: 0,
      revenue: 0,
      avgtime: 0,
      spentByMonth: {
        chartOptions: {
          chart: {
            height: 350,
            type: "line",
            zoom: {
              enabled: false,
            },
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            curve: "straight",
          },
          title: {
            text: "Last Year Sales by Month",
            align: "left",
          },
          grid: {
            row: {
              colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
              opacity: 0.5,
            },
          },
          xaxis: {
            categories: [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "May",
              "Jun",
              "Jul",
              "Aug",
              "Sep",
              "Oct",
              "Nov",
              "Dec",
            ],
          },
        },

        series: [
          {
            name: "Euros",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          },
        ],
      },
//grafico linha para numero de orders por mes no ultimo ano
      ordersByMonth: {
        chartOptions: {
          chart: {
            height: 350,
            type: "line",
            zoom: {
              enabled: false,
            },
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            curve: "straight",
          },
          title: {
            text: "Last Year Orders by Month",
            align: "left",
          },
          grid: {
            row: {
              colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
              opacity: 0.5,
            },
          },
          xaxis: {
            categories: [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "May",
              "Jun",
              "Jul",
              "Aug",
              "Sep",
              "Oct",
              "Nov",
              "Dec",
            ],
          },
        },

        series: [
          {
            name: "Euros",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          },
        ],
      },

//grafico barras numero de produtos vendidos
     totalProducts: {
        chartOptions: {
          chart: {
            height: 350,
            type: "bar",
            zoom: {
              enabled: false,
            },
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            curve: "straight",
          },
          title: {
            text: "Total by Products",
            align: "left",
          },
          grid: {
            row: {
              colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
              opacity: 0.5,
            },
          },
          xaxis: {
            categories: [
            ],
          },
        },

        series: [
          {
            name: "Euros",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          },
        ],
      },


      
// totalProducts: {
          
//           series: [{
//             name: 'Euros',
//             data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
//           }],
//           chartOptions: {
//             chart: {
//               height: 350,
//               type: 'bar',
//             },
//             plotOptions: {
//               bar: {
//                 dataLabels: {
//                   position: 'top', // top, center, bottom
//                 },
//               }
//             },
//             dataLabels: {
//               enabled: true,
//               formatter: function (val) {
//                 return val;
//               },
//               offsetY: -20,
//               style: {
//                 fontSize: '12px',
//                 colors: ["#304758"]
//               }
//             },
            
//             xaxis: {
//               categories: [],
//               position: 'top',
//               axisBorder: {
//                 show: false
//               },
//               axisTicks: {
//                 show: false
//               },
//               crosshairs: {
//                 fill: {
//                   type: 'gradient',
//                   gradient: {
//                     colorFrom: '#D8E3F0',
//                     colorTo: '#BED1E6',
//                     stops: [0, 100],
//                     opacityFrom: 0.4,
//                     opacityTo: 0.5,
//                   }
//                 }
//               },
//               tooltip: {
//                 enabled: true,
//               }
//             },
//             yaxis: {
//               axisBorder: {
//                 show: false
//               },
//               axisTicks: {
//                 show: false,
//               },
//               labels: {
//                 show: false,
//                 formatter: function (val) {
//                   return val;
//                 }
//               }
            
//             },
//             title: {
//               text: 'Total number of product sales',
//               floating: true,
//               offsetY: 330,
//               align: 'center',
//               style: {
//                 color: '#444'
//               }
//             }
//           },
          
          
//         },



      users_operators_admins: {
        chartOptions: {
          chart: {
            id: "users_operators_admins",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false,
            },
          },
          labels: ["customers", "cookers", "deliverers", "managers"],
        },
        series: [0, 0, 0],
      },
      type_of_product: {
        chartOptions: {
          chart: {
            id: "type_of_product",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false,
            },
          },
          labels: ["Drink", "Dessert", "Hot Dish", "Cold Dish"],
        },
        series: [0, 0],
      },
      averageSpentCostumers: {
        chartOptions: {
          chart: {
            id: "averageSpentCostumers",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false,
            },
          },
          labels: [0, 0, 0],
        },
        series: [0, 0, 0],
      },
      avgtime: {
        chartOptions: {
          chart: {
            id: "avgtime",
            height: 150,
            type: "donut",
            zoom: {
              enabled: false,
            },
          },
          labels: ["Cookers AVG Time", "Deliverers AVG Time"],
        },
        series: [0, 0],
      },
    };
  },
  methods: {
    format(value) {
      let val = (value / 1).toFixed(2).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    getUserStats: function () {
      axios.get("api/numberofusers").then((response) => {
        this.numberOfUsers = response.data.total;
        //  console.log(response.data);
        this.users_operators_admins.series = [
          response.data.customers,
          response.data.cookers,
          response.data.deliverers,
          response.data.managers,
        ];
        // console.log(this.users_operators_admins.series);
      });
      axios.get("api/numberoforders").then((response) => {
        this.numberoforders = response.data;
        console.log("Number of orders: " + this.numberoforders);
      });

      axios.get("api/revenue").then((response) => {
        this.revenue = response.data;
        //console.log("Amount in orders: " + this.revenue);
      });
    },
    getTypeOfCategoryStats: function () {
      axios.get("api/typeofcategory").then((response) => {
        // console.log(response);
        this.type_of_product.series = [
          response.data.drink,
          response.data.dessert,
          response.data.hotdish,
          response.data.colddish,
        ];
      });
    },

    /////////////////////
    getAverageSpentCustomer: function () {
      axios.get("api/averageSpentCustomer").then((response) => {
        // console.log(response);
        this.averageSpentCostumers = response.data;
      });
    },
    getAverageTimeSpent: function () {
      axios.get("api/avgtime").then((response) => {
        //  console.log(response.data);
        this.avgtime.series = [response.data.medPrep, response.data.medDeliver];
      });
    },
    getStats: function () {
      axios.get("api/salespermonth").then((response) => {
        console.log(response.data.data);
        this.spentByMonth.series = [
          {
            name: "Number of Sales(€)",
            data: response.data.data.total_price,
          },
        ];
        this.ordersByMonth.series = [
          {
            name: "Number of Orders",
            data: response.data.data.total_order,
          },
        ];

        response.data.data.total_products.forEach(prod => {
          
          // adicionar aos arrays temporários
          this.x.push(prod.product_id);
          this.y.push(prod.quantity);
        });


        this.totalProducts.series = [
           {
            name: "Total Products",
            data: this.y,
          }
        ];
                  
        this.totalProducts.chartOptions.xaxis.categories = this.x;
        
      });
    },

  },
  mounted() {
    this.userID = 20;
    //
    this.getUserStats();

    this.getTypeOfCategoryStats();
    this.getAverageSpentCustomer();
    this.getAverageTimeSpent();
    this.getStats();
  },
  components: {
    navBar: NavBarComponent,
    apexcharts: VueApexCharts,
  },
};
</script>

