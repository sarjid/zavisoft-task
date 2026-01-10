import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/stores/auth'
import AdminLogin from '@/views/auth/AdminLogin.vue'
import AdminLayout from '@/views/layouts/AdminLayout.vue'
import AdminDashboard from '@/views/AdminDashboard.vue'
import CategoryList from '@/views/CategoryList.vue'
import ProductList from '@/views/product/ProductList.vue'
import ProductCreate from '@/views/product/ProductCreate.vue'
import ProductEdit from '@/views/product/ProductEdit.vue'
import OrderList from '@/views/order/OrderList.vue'
import AttributeList from '@/views/attribute/AttributeList.vue'
import PathNotFound from '@/views/PathNotFound.vue'


const routes = [
    {
        path: '/admin/login',
        name: 'admin-login',
        component: AdminLogin,
        meta: { guest: true }
    },

    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: { name: 'admin-dashboard' }
            },
            {
                path: 'dashboard',
                name: 'admin-dashboard',
                component: AdminDashboard,
            },

            {
                path: 'categories',
                name: 'admin-categories',
                component: CategoryList,
            },

             {
                path: 'products',
                name: 'admin-products',
                component: ProductList,
            },

            {
                path: 'products/create',
                name: 'admin-products-create',
                component: ProductCreate,
            },
            {
                path: 'products/:productId/edit',
                name: 'admin-products-edit',
                component: ProductEdit,
            },
            {
                path: 'orders',
                name: 'admin-orders',
                component: OrderList,
            },
            {
                path: 'attributes',
                name: 'admin-attributes',
                component: AttributeList,
            },



        ],
    },

    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: PathNotFound,
    },
];



const router = createRouter({
    history: createWebHistory(),
    scrollBehavior() {
        return { top: 0 }
    },
    routes
})


router.beforeEach((to, from, next) => {
    const auth = useAuth();
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (!auth.getAuthStatus) {
            next({ name: "admin-login" });
        } else {
            next();
        }
    } else if (to.matched.some((record) => record.meta.guest)) {
        if (auth.getAuthStatus) {
            next({ name: "admin-dashboard" });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router
