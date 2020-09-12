import Vue from 'vue'
import Router from 'vue-router'
import Test from "./components/Test";
import BIndex from "./components/pages/BIndex"
import Badd from "./components/pages/Badd";
import BTIndex from "./components/pages/BTIndex";
import BTadd from "./components/pages/BTadd";
Vue.use(Router)

const routes = [
    {
        path: '/',
        name: 'test',
        component: Test
    },
    {
        path: '/batches',
        name: 'batch.index',
        component: BIndex
    },
    {
        path: '/batch',
        name: 'batch.add',
        component: Badd
    },
    {
        path: '/batchTypes',
        name: 'batchType.index',
        component: BTIndex
    },
    {
        path: '/batchType',
        name: 'batchType.add',
        component: BTadd
    }

]

export default new Router({
    mode: 'history',
    routes
})
