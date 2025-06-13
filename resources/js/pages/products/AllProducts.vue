<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref, watch, reactive, computed } from 'vue';
import { RotateCcw, Search, Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";

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

const dialogState = reactive({
    isOpen: false,
    productId: null as number | null
});

const handleDelete = (productId: number) => {
    dialogState.productId = productId;
    dialogState.isOpen = true;
};

const confirmDelete = () => {
    if (dialogState.productId) {
        router.delete(route('products.destroy', dialogState.productId), {
            onSuccess: () => {
                dialogState.isOpen = false;
                dialogState.productId = null;
            }
        });
    }
};

const cancelDelete = () => {
    dialogState.isOpen = false;
    dialogState.productId = null;
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Products" />
        
        <div class="flex flex-col h-full flex-1 gap-4">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <CardTitle>Products List</CardTitle>
                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger asChild>
                                    <Link
                                        :href="route('products.create')"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-green-600 hover:bg-green-700 text-white h-10 px-4 py-2"
                                    >
                                        Create Product
                                    </Link>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>Create new product</p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
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
                                            <Link
                                                :href="route('products.index', {
                                                    sort_by: sortBy,
                                                    sort_direction: sortDirection,
                                                    page: 1,
                                                    perPage: perPage,
                                                    search: searchQuery
                                                })"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                                            >
                                                Search
                                            </Link>
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
                                        Image
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
                                    <th scope="col" class="px-6 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" :key="product.id" class="odd:bg-white odd:dark:bg-gray-900 dark:border-gray-700">
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.id }}
                                    </td>
                                    <td class="px-6 py-2">
                                        <div v-if="product.image">
                                            <img
                                            :src="`/storage/${product.image}`"
                                            :alt="`${product.name}`"
                                            class="w-16 h-16 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                            />
                                        </div>
                                        <div v-else
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
                                    <td class="px-6 py-2 font-medium text-gray-900 dark:text-white max-w-[200px] truncate">
                                        {{ product.description }}
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.price }}
                                    </td>
                                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ product.stock }}
                                    </td>
                                    <td class="px-6 py-2">
                                        <div class="flex items-center gap-2">
                                            <Link
                                                :href="route('products.edit', product.id)"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                                            >
                                                <Pencil class="w-4 h-4" />
                                            </Link>
                                            <button
                                                @click="() => handleDelete(product.id)"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                    <!-- Delete Confirmation Dialog -->
                                    <Dialog :open="dialogState.isOpen" @update:open="dialogState.isOpen = $event">
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle>Delete Product?</DialogTitle>
                                                <DialogDescription>
                                                    Are you sure you want to delete this product?
                                                    <p><strong>{{ product.name }}</strong></p>
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter>
                                                <Link
                                                    href="#"
                                                    @click.prevent="cancelDelete"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-10 px-4 py-2"
                                                >
                                                    Cancel
                                                </Link>
                                                <Link
                                                    href="#"
                                                    @click.prevent="confirmDelete"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2"
                                                >
                                                    Delete
                                                </Link>
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
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
