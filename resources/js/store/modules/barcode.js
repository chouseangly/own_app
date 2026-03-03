
import axios from 'axios';
import appService from '../../../services/appService';
import { k } from 'vue-router/dist/router-CWoNjPRp.mjs';
export const barcode = {
    namespaced : true,
    state:{
        lists: [],
        page: {},
        paginate: [],
        temp: {
            temp_id : null
        },
        getters: {
            lists: function(state){
                return state.lists;
            },
            page: function(state){
                return state.page;
            },
            paginate: function(state){
                return state.paginate;
            },
            temp: function(state){
                return state.temp;
            }
        },
        actions:{
            lists: function(context,payload){
                return new Promise((resolve,reject){
                     let url = '/api/admin/setting/barcode';
                    if(payload){
                        url = url + appService.requestHandler(payload)
                    }
                    axios.get(url).then((res)=>{
                        if(typeof payload.vuex === 'undefined' || payload.vuex === true){
                            context.commit('lists',res.data.data);
                            context.commit('page',res.data.meta);
                            context.commit('paginate',res.data);
                        }
                        resolve(res);
                    }).catch((err)=>{
                        reject(err);
                    })
                })
            },
            reset: function(context){
                context.commit('reset');
            }
        },
        mutations:{
            lists: function(state,payload){
                state.lists = payload;
            },
            page: function(state,payload){
                state.page = payload;
            },
            paginate: function(state,payload){
                state.page = payload;
            },
            reset: function(state){
                state.temp.temp_id = null
            }
        }
    }
}
