<template>
    <div class="flex flex-wrap -mb-1">
        <template v-for="(link, key) in links" :key="key">
            <div v-if="link.url === null" 
                 class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                 :innerHTML="link.label" />
            <Link v-else
                  class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-gray-100 focus:border-indigo-500 focus:text-indigo-500"
                  :class="{ 'bg-indigo-50': link.active }"
                  :href="addPerPageToUrl(link.url)"
                  :innerHTML="link.label" />
        </template>
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@inertiajs/core';

const { links } = defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}>();

const addPerPageToUrl = (url: string | null): string => {
    if (!url) return '';
    const page = usePage<PageProps & { products: { per_page: number } }>();
    const currentPerPage = page.props.products.per_page;
    const urlObj = new URL(url);
    urlObj.searchParams.set('perPage', currentPerPage.toString());
    return urlObj.toString();
};
</script> 