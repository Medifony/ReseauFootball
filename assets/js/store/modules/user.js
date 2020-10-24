export default {
    state: {
        pseudo: null
    },
    getters: {
        PSEUDO: state => state.pseudo
    },
    mutations: {
        SET_USERNAME: (state, payload) => state.pseudo = payload
    },
    actions: {}
}