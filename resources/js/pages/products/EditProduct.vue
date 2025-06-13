<script setup lang="ts">
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { watch, ref } from 'vue';
import Snackbar from '@/components/ui/snackbar/Snackbar.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Product',
        href: '/products',
    },
    {
        title: 'Edit',
        href: '/products',
    },
];

const props = defineProps({
    product: Object
});

const form = useForm({
    id: props.product?.id || '',
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price?.toString() || '0',
    stock: props.product?.stock?.toString() || '0',
    image: props.product?.image || '',
});

const showSnackbar = ref(false);

// Watch for product changes
watch(() => props.product, (newProduct) => {
    if (newProduct) {
        form.reset();
        form.id = newProduct.id;
        form.name = newProduct.name;
        form.description = newProduct.description;
        form.price = newProduct.price?.toString() ;
        form.stock = newProduct.stock?.toString() || '0';
        form.image = newProduct.image;
    }
}, { immediate: true });

function submit() {
    // Validate form data before sending
    const formData = form.data();

    // Ensure data is in the correct format
    form.name = form.name.trim();
    form.description = form.description.trim();
    form.price = Number(form.price).toFixed(2);
    form.stock = parseInt(form.stock);

    form.put(route('products.update', props.product?.id), {
        preserveScroll: true,
        onSuccess: () => {
            showSnackbar.value = true;
            // Wait a moment before reloading the page
            setTimeout(() => {
                router.visit(route('products.edit', props.product?.id));
            }, 3000);
        },
    });
};

const handleSnackbarClose = () => {
    showSnackbar.value = false;
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Product Edit" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-6">Edit Product</h2>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Name
                            </label>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required
                            />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Price
                            </label>
                            <input
                                id="price"
                                type="number"
                                step="0.01"
                                min="0"
                                v-model="form.price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required
                            />
                            <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">
                                {{ form.errors.price }}
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Stock
                            </label>
                            <input
                                id="stock"
                                type="number"
                                min="0"
                                v-model="form.stock"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required
                            />
                            <div v-if="form.errors.stock" class="text-red-500 text-sm mt-1">
                                {{ form.errors.stock }}
                            </div>
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Image
                            </label>
                            <!-- Show current image if exists -->
                            <div v-if="product?.image" class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current image:</p>
                                <img
                                    :src="`/storage/${product?.image}`"
                                    :alt="product?.name"
                                    class="w-32 h-32 object-cover rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                />
                            </div>
                            <div v-else
                                class="w-32 h-32 object-cover rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                :style="{
                                    backgroundColor: `hsl(${(product?.id * 137.5) % 360}, 70%, 50%)`
                                }"
                            >
                                {{ product?.name.charAt(0).toUpperCase() }}
                            </div>
                            <input
                                id="image"
                                type="file"
                                @input="(e) => form.image = (e.target as HTMLInputElement).files?.[0] || null"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-indigo-900 dark:file:text-indigo-300"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Leave this field empty if you don't want to change the current image
                            </p>
                            <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">
                                {{ form.errors.image }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <Link
                                :href="route('products.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :disabled="form.processing"
                            >
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <Snackbar
            v-if="showSnackbar"
            message="Product updated successfully"
            type="success"
            :duration="3000"
            @close="handleSnackbarClose"
        />
    </AppLayout>
</template>