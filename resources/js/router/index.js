import { createRouter, createWebHistory } from "vue-router";

import CompaniesPage from "../modules/company/pages/CompaniesPage.vue";

const routes = [
    {
        path: '/companies',
        name: 'companies',
        component: CompaniesPage
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router