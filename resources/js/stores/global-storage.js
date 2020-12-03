import Vue from "vue"
import Vuex from "vuex"
Vue.use(Vuex)
export default new Vuex.Store({
  state: {
    user: null,
    shopCart: [],

  },
  mutations: {
    clearUser(state) {
      state.user = null
    },
    setUser(state, user) {
      state.user = user
    },
    addToCart(state, product) {
      const record = state.shopCart.find(p => p.product.id === product.id)
      if (!record) {
        state.shopCart.push({
          product,
          quantity: 1

        })
      } else {
        record.quantity++
      }
    },
    removeFromCart(state, product) {
      const record = state.shopCart.find(p => p.product.id === product.id)
      if (record) {
        if (record.quantity == 1) {
          state.shopCart.splice(state.shopCart.indexOf(record), 1)
        }else{
        state.shopCart[state.shopCart.indexOf(record)].quantity--
        }
      }

    },
    clearCart(state){
      state.shopCart=[];
    }


  },



})