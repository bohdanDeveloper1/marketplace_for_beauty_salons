import { createApp } from 'vue'
import { createStore } from 'vuex'

// Create a new store instance.
const store = createStore({
    // for data
    state () {
        let chosen;
        return {
            reservation:{
                serviceId: 100,
                stylistId: null,
                date: null,
                chosenTime: [],
            }


        }
    },
    // for functions to change data
    mutations: {
        increment (state) {
            state.count++
        }
    }
})

const app = createApp({ app })

// Install the store instance as a plugin
app.use(store)