import Vue from "vue"
import Vuex from "vuex"
Vue.use(Vuex)
export default new Vuex.Store({
  state: {
    user: null,
    shopCart: { products: [], notes: "" },

  },
  mutations: {
    clearUser(state) {

      if (state.user) {
        this._vm.$socket.emit('user_logged_out', state.user)
      }
      state.user = null
    },
    setUser(state, user) {
      // if state.user is the same as the new user, do nothing
            if (state.user !== user) {
                if (state.user) {
                    this._vm.$socket.emit('user_logged_out', state.user)
                }
                state.user = user
                if (state.user) {
                    this._vm.$socket.emit('user_logged', state.user)
                }
            }
    },
    addToCart(state, product) {
      const record = state.shopCart.products.find(p => p.product.id === product.id)
      if (!record) {
        state.shopCart.products.push({
          product,
          quantity: 1

        })
      } else {
        record.quantity++
      }
    },
    removeFromCart(state, product) {
      const record = state.shopCart.products.find(p => p.product.id === product.id)
      if (record) {
        if (record.quantity == 1) {
          state.shopCart.products.splice(state.shopCart.products.indexOf(record), 1)
        } else {
          record.quantity--
        }
      }

    },
    clearCart(state) {
      state.shopCart.products = [];
      state.shopCart.notes = "";
    }


  },
  actions: {
    loadUserLogged(context) {
      axios.get('/api/users/me')
        .then(response => {
          context.commit('setUser', response.data)
        })
        .catch(error => {
          context.commit('clearUser')
        })
    }
  }



})