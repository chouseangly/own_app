import ProfileChangePasswordComponent from '../../components/admin/components/profile/ProfileChangePasswordComponent.vue';
import ProfileEditProfileComponent from '../../components/admin/components/profile/ProfileEditProfileComponent.vue';
import OrderHistoryComponent from '../../components/frontend/account/orderHistory/OrderHistoryComponent.vue';
import ReturnOrderComponent from '../../components/frontend/account/orderHistory/ReturnOrderComponent.vue';

export default [
    {
        path: "/admin/profile/edit-profile",
        component: ProfileEditProfileComponent,
        name: "admin.profile.editProfile",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "",
            breadcrumb: "edit_profile",
        },
    },
    {
        path: "/admin/profile/change-password",
        component: ProfileChangePasswordComponent,
        name: "admin.profile.changePassword",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "",
            breadcrumb: "change_password",
        },
    },
    {
        path: '/order-history',
        name: 'frontend.account.orderHistory', // This must exist
        component: OrderHistoryComponent
    },
    {
        path: '/return-orders',
        name: 'frontend.account.returnOrders', // This must exist
        component: ReturnOrderComponent
    },
];
