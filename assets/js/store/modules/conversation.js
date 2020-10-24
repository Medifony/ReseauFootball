import Vue from 'vue';

export default {
    state: {
        conversations: [],
        hubUrl: null
    },
    getters: {
        CONVERSATIONS: state => {
            return state.conversations.sort((a, b) => {
                return a.contDate < b.contDate ? 1 : -1
            })
        },
        MESSAGES: state => conversationId => {
            return state.conversations.find(i => i.conversationId === conversationId).messages
        },
        HUBURL: state => state.hubUrl
    },
    mutations: {
        SET_CONVERSATIONS: (state, payload) => {
            state.conversations = payload
        },
        SET_MESSAGES: (state, {conversationId, payload}) => {
            Vue.set(
                state.conversations.find(i => i.conversationId === conversationId),
                'messages',
                payload
            )
        },
        ADD_MESSAGE: (state, {conversationId, payload}) => { console.log(conversationId, payload)
            state.conversations.find(i => i.conversationId === conversationId).messages.push(payload)
        },
        SET_CONVERSATION_LAST_MESSAGE: (state, {conversationId, payload}) => {
            let rs = state.conversations.find(i => i.conversationId === conversationId);
            rs.contenu = payload.contenu;
            rs.contDate = payload.contDate;
        },
        SET_HUBURL: (state, payload) => state.hubUrl = payload,
        UPDATE_CONVERSATIONS: (state, payload) => {
            let rs = state.conversations.find(i => i.conversationId === payload.conversations.id);
            rs.contenu = payload.contenu;
            rs.contDate = payload.contDate;
        }
    },
    actions: {
        GET_CONVERSATIONS: ({commit}) => {
            return fetch('/conversations')
                .then(result => {
                    const hubUrl = result.headers.get('Link').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1]
                    commit("SET_HUBURL", hubUrl)
                    return result.json()
                })
                .then((result) => {
                    commit("SET_CONVERSATIONS", result)
                })
        },
        GET_MESSAGES: ({commit, getters}, conversationId) => {
            if (getters.MESSAGES(conversationId) === undefined) {
                return fetch(`/messages/${conversationId}`)
                    .then(result => result.json())
                    .then((result) => {
                        commit("SET_MESSAGES", {conversationId, payload: result})
                    });
            }
        },
        POST_MESSAGE: ({commit}, {conversationId, contenu}) => {
            let formData = new FormData();
            formData.append('contenu', contenu);

            return fetch(`/messages/${conversationId}`, {
                method:"POST",
                body: formData
            })
                .then(result => result.json())
                .then((result) => {
                    commit("ADD_MESSAGE", {conversationId, payload: result})
                    commit("SET_CONVERSATION_LAST_MESSAGE", {conversationId, payload: result})
                })
        }
    }
}