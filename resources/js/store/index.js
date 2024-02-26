import {createStore} from "vuex";

export default createStore({
    state: {
        temporaryReservationId: -1
    },
    mutations: {
        SET_TEMPORARY_RESERVATION_ID(state, id) {
            state.temporaryReservationId = id;
        },
    },
    actions: {
        setTemporaryReservationId({ commit }, day) {
            commit('SET_TEMPORARY_RESERVATION_ID', day);
        },
    },
    getters: {
        getSelectedDay: (state) => state.temporaryReservationId,
        // 다른 게터들 추가 가능
    },
});
