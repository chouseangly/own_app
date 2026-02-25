import axios from "axios";
import appService from "../../../services/appService";

export const slider = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEdit: false,
        }
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        page: function (state) {
            return state.page;
        },
        pagination: function (state) {
            return state.pagination;
        },
        show: function (state) {
            return state.show;
        },
        temp: function (state) {
            return state.temp;
        }
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = '/api/admin/setting/slider'
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === 'undefined' || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                })
            })
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = '/api/admin/setting/slider'
                if (this.state['slider'].temp.isEdit) {
                    method = axios.post;
                    url = `/api/admin/setting/slider${this.state['slider'].temp.temp_id}`;
                }
                method(url,payload.form).then((res)=>{
                    context.dispatch('lists',payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err)=>{
                    reject(err)
                })
            })
        },
        edit: function(context,payload){
            context.commit('temp',payload)
        },
        destroy: function(context,payload){
            return new Promise((resolve,reject)=>{
                axios.delete(`/api/admin/setting/slider${payload.id}`)
                .then((res)=>{
                    context.dispatch('lists',payload.search).then().catch();
                    resolve(res);
                }).catch((err)=>{
                    reject(err)
                })
            })
        },
        show: function(context,payload){
            return new Promise((resolve,reject)=>{
                axios.get(`/api/admin/setting/slider/show/${payload}`)
                .then((res)=>{
                    context.commit('show',res.data.data);
                    resolve(res);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        reset: function(context){
            context.commit('reset')
        }
    },
    mutations:{
        lists: function(state,payload){
        state.lists = payload;
        },
        page: function(state,payload){
            if(typeof payload !== 'undefined' && payload !==null){
                state.page = {
                    form: payload.form,
                    to: payload.to,
                    total: payload.total
                }
            }
        },
        pagination: function(state,payload){
             state.pagination = payload
        },
        show: function(state,payload){
            state.show = payload
        },
        temp: function(state,payload){
            state.temp.temp_id = payload,
            state.temp.isEdit = true
        },
        reset: function(state){
            state.temp.temp = null,
            state.temp.isEdit = false
        }
    }
}
