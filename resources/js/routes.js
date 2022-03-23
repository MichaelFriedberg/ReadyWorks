import ChartComponent from './components/ChartComponent.vue';
import ExampleComponent from './components/ExampleComponent.vue';
import TableComponent from './components/TableComponent.vue';

export const routes = [
    {
        name: 'Dashboard',
        path: '/',
        component: ExampleComponent
    },
    {
        name: 'Chart',
        path: '/chart',
        component: ChartComponent
    },
    {
        name: 'Data Table',
        path: '/table',
        component: TableComponent
    }
];
