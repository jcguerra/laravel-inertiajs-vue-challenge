<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref, watch } from 'vue';
import { RotateCcw, Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';

const props = defineProps<{
    products: {
        data: Array<{
            id: number;
            name: string;
            description: string;
            price: number;
            stock: number;
            image: string;
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
}>();

const sortBy = ref('id');
const sortDirection = ref('asc');
const perPage = ref(props.products.per_page);
const searchQuery = ref('');

watch(() => props.products.per_page, (newValue) => {
    perPage.value = newValue;
});

const sort = (column: string) => {
    if (sortBy.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = column;
        sortDirection.value = 'asc';
    }

    router.get(
        route('products.index'),
        {
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1,
            perPage: perPage.value,
            search: searchQuery.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};

const changePerPage = (value: number) => {
    perPage.value = value;
    router.get(
        route('products.index'),
        {
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1,
            perPage: value,
            search: searchQuery.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};

const search = () => {
    router.get(
        route('products.index'),
        {
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1,
            perPage: perPage.value,
            search: searchQuery.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};

const resetSearch = () => {
    searchQuery.value = '';
    router.get(
        route('products.index'),
        {
            sort_by: sortBy.value,
            sort_direction: sortDirection.value,
            page: 1,
            perPage: perPage.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};

const getSortIcon = (column: string) => {
    if (sortBy.value !== column) return '↕️';
    return sortDirection.value === 'asc' ? '↑' : '↓';
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

console.log('Products data:', props.products);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Products" />
        
        <div class="flex flex-col h-full flex-1 gap-4">
            <Card>
                <CardHeader>
                    <CardTitle>Products List</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Show:</span>
                            <select 
                                v-model="perPage"
                                @change="changePerPage(Number(perPage))"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option :value="10">10</option>
                                <option :value="25">25</option>
                                <option :value="50">50</option>
                            </select>
                            <span class="text-sm text-gray-600">products per page</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-gray-600">
                                Total: {{ products.total }} products
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <Search class="absolute left-2 top-2.5 h-4 w-4 text-gray-500" />
                                    <Input
                                        type="text"
                                        v-model="searchQuery"
                                        @keyup.enter="search"
                                        placeholder="Search products..."
                                        class="pl-8"
                                    />
                                </div>
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Button
                                                @click="search"
                                                variant="default"
                                            >
                                                Search
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Search products</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Button
                                                @click="resetSearch"
                                                variant="outline"
                                                :disabled="!searchQuery"
                                                class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 border-gray-300"
                                            >
                                                <RotateCcw class="w-5 h-5" />
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Clear search</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('id')">
                                        ID {{ getSortIcon('id') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Imagen
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('name')">
                                        Product Name {{ getSortIcon('name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('description')">
                                        Description {{ getSortIcon('description') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('price')">
                                        Price {{ getSortIcon('price') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('stock')">
                                        Stock {{ getSortIcon('stock') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" class="odd:bg-white odd:dark:bg-gray-900 dark:border-gray-700">
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.id }}
                                    </td>
                                    <td class="px-6 py-2">
                                        <div 
                                            class="w-16 h-16 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                            :style="{
                                                backgroundColor: `hsl(${(product.id * 137.5) % 360}, 70%, 50%)`
                                            }"
                                        >
                                            {{ product.name.charAt(0).toUpperCase() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.name }}
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.description }}
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.price }}
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.stock }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <Pagination :links="products.links" />
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
