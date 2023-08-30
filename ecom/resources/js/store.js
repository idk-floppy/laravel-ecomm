import { createStore } from 'vuex';
import { GetCartService } from './components/services/GetCartService';

const store = createStore({
    state() {
        return {
            cartItemCount: 0,
            cartItems: [],
            isAuthenticated: false,
            cartTotal: 0,
            isLoading: 0,
        }
    },
    mutations: {
        setIsAuthenticated(state, status) {
            state.isAuthenticated = status;
        },
        setCartItems(state, items) {
            state.cartItems = items;
            state.cartItemCount = state.cartItems ? state.cartItems.length : 0;
        },
        setCartTotal(state, amount) {
            state.cartTotal = amount;
        },
        setIsLoading(state, value) {
            state.isLoading += value;
            console.log(state.isLoading);
        }
    },
    actions: {
        async fetchIsAuthenticated({ commit }) {
            const response = await axios.get('auth/check');
            commit('setIsAuthenticated', response.data.isAuthenticated);
        },
        async fetchCartItems() {
            this.dispatch('modifyIsLoading', true);
            const cart = await GetCartService();
            this.dispatch('updateCartItems', cart)
            this.dispatch('modifyIsLoading', false);
        },
        modifyIsLoading({commit}, bool) {
            if (bool) {
                commit('setIsLoading', 1);
            }else{
                commit('setIsLoading', -1);
            }
        },
        updateCartItems({ commit }, cart) {
            commit('setCartTotal', cart.total);
            commit('setCartItems', cart.items);
        }
    },
    getters: {
        getIsAuthenticated: state => state.isAuthenticated,
        getCartTotal: state => state.cartTotal,
        getCartItemCount: state => state.cartItemCount,
        getCartItems: state => state.cartItems,
        getIsLoading: state => state.isLoading > 0,
    }
});

export { store };
