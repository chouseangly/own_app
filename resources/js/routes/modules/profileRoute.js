import ProfileChangePasswordComponent from '../../components/admin/components/profile/ProfileChangePasswordComponent.vue';
import ProfileEditProfileComponent from '../../components/admin/components/profile/ProfileEditProfileComponent.vue';
import OrderHistoryComponent from '../../components/frontend/account/orderHistory/OrderHistoryComponent.vue';
import ReturnOrderComponent from '../../components/frontend/account/orderHistory/ReturnOrderComponent.vue';

export default [
    {
        path: "/admin/profile/edit-profile",
        component: ProfileEditProfileComponent,
        name: "admin.profile.editProfile",
        meta: { isFrontend: false, auth: true, permissionUrl: "", breadcrumb: "edit_profile" },
    },
    {
        path: "/admin/profile/change-password",
        component: ProfileChangePasswordComponent,
        name: "admin.profile.changePassword",
        meta: { isFrontend: false, auth: true, permissionUrl: "", breadcrumb: "change_password" },
    },
    {
        path: '/order-history',
        name: 'frontend.account.orderHistory',
        component: OrderHistoryComponent
    },
    {
        path: '/return-orders',
        name: 'frontend.account.returnOrders',
        component: ReturnOrderComponent
    },
    // ADD THESE MISSING ROUTES REQUIRED BY YOUR NAVBAR:
    {
        path: '/account-info',
        name: 'frontend.account.accountInfo', // This matches the error
        component: ProfileEditProfileComponent // Or your specific frontend component
    },
    {
        path: '/change-password',
        name: 'frontend.account.changePassword', // Required for navbar link
        component: ProfileChangePasswordComponent // Or your specific frontend component
    },
    {
        path: '/address',
        name: 'frontend.account.address', // Required for navbar link
        component: ProfileEditProfileComponent // Replace with your Address component
    },
];
