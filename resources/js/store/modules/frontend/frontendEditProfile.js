import axios from "axios";


export const frontendEditProfile = {
    namespaced: true,
    state:{
        profile:{}
    },
    getters:{
        profile: function(state){
            return state.profile;
        }
    },
    actions:{
        updateProfile: function(context,payload){
            return new Promise((resolve,reject)=>{
                axios.post('/api/profile').then((res)=>{
                    context.commit('profile',res.data.data);
                    resolve(res);
                }).catch((err)=>{
                    reject(err)
                })
            })
        },
        changeImage: function(context,payload){
            return new Promise((resolve,reject)=>{
                axios.post('/api/profile/change-image',payload.form,{
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    },
                }).then((res)=>{
                    context.commit("profile",res.data.data);
                    resolve(res);
                }).catch((err)=>{
                    reject((err));
                })
            })
        },
        changePassword: function(context,payload){
            return new Promise((resolve,reject)=>{
                axios.put('/api/profile/change-password').then((res)=>{
                    context.commit('profile',res.data.data);
                    resolve(res);
                }).catch((err)=>{
                    reject(err)
                })
            })
        }
    },
    mutations:{
        profile: function(state,payload){
            state.profile = payload;
        }
    }
}
