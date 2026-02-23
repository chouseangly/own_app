import { p } from "vue-router/dist/router-CWoNjPRp.mjs";
import dist from "vuex-persistedstate";

export const productCategory = {
    namespaced: true,
    state:{
        depthTrees: [],
        lists: [],
        page: {},
        pagination: [],
        show:{},
        temp:{
            temp_id: null,
            isEdit: false,
        }

    },
    getters:{
        depthTrees: function(state){
            return state.depthTrees;
        },
        lists: function(state){
            return state.lists;
        },
        page: function(state){
            return state.page;
        },
        pagination: function(state){
            return state.pagination;
        },
        show: function(state){
            return state.show;
        },
        temp: function(state){
            return state.temp;
        }
    },
    actions:{
        depthTrees: function(context){
            return new Promise((resolve,reject)=>{
                axios.get('/api/admin/setting/product-categories/depth-tree').then((res)=>{
                    context.commit('depthTrees', res.data.data);
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        lists: function(context, payload){
            return new Promise((resolve,reject)=>{
                axios.get('/api/admin/setting/product-categories', {params: payload}).then((res)=>{
                    context.commit('lists', res.data.data);
                    context.commit('page', res.data.page);
                    context.commit('pagination', res.data.pagination);
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        show: function(context, id){
            return new Promise((resolve,reject)=>{
                axios.get('/api/admin/setting/product-categories/' + id).then((res)=>{
                    context.commit('show', res.data.data);
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        save: function(context, payload){
            return new Promise((resolve,reject)=>{
                axios.post('/api/admin/setting/product-categories', payload).then((res)=>{
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        edit: function(context, payload){
           context.commit('temp', payload);
        },
        distroy: function(context, id){
            return new Promise((resolve,reject)=>{
                axios.delete('/api/admin/setting/product-categories/' + id).then((res)=>{
                    context.dispatch('lists', payload.search).then().catch();
                    context.dispatch('depthTrees').then().catch();
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        reset: function(context){
            context.commit('reset');

        },
        export: function(context, payload){
            return new Promise((resolve,reject)=>{
                axios.get('/api/admin/setting/product-categories/export', {params: payload}).then((res)=>{
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        import: function(context, payload){
            return new Promise((resolve,reject)=>{
                axios.post('/api/admin/setting/product-categories/import', payload).then((res)=>{
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        },
        download: function(context){
            return new Promise((resolve,reject)=>{
                axios.get('/api/admin/setting/product-categories/download-attachment/{fileName}').then((res)=>{
                    resolve(res.data);
                }).catch((err)=>{
                    reject(err);
                })
            })
        }
    },
    mutations:{
        depthTrees: function(state, payload){
            state.depthTrees = payload;
        },
        lists: function(state, payload){
            state.lists = payload;
        },
        page: function(state, payload){
            state.page = payload;
        },
        pagination: function(state, payload){
            state.pagination = payload;
        },
        show: function(state, payload){
            state.show = payload;
        },
        temp: function(state, payload){
            state.temp = payload;
        },
        reset: function(state){
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        }
    }
}
