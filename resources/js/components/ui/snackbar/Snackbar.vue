<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { cn } from '@/lib/utils';
import { X } from 'lucide-vue-next';

interface Props {
    message: string;
    type?: 'success' | 'error' | 'info' | 'warning';
    duration?: number;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'success',
    duration: 3000,
    class: '',
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const isVisible = ref(true);
let timeout: number;

const typeStyles = {
    success: 'bg-green-500 text-white',
    error: 'bg-red-500 text-white',
    info: 'bg-blue-500 text-white',
    warning: 'bg-yellow-500 text-white',
};

onMounted(() => {
    setTimeout(() => {
        timeout = window.setTimeout(() => {
            isVisible.value = false;
            setTimeout(() => {
                emit('close');
            }, 300);
        }, props.duration);
    }, 100);
});

onUnmounted(() => {
    if (timeout) {
        clearTimeout(timeout);
    }
});

const close = () => {
    isVisible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
};
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isVisible"
            :class="cn(
                'fixed bottom-4 right-4 z-50 flex items-center gap-2 rounded-lg px-4 py-3 shadow-lg',
                typeStyles[type],
                props.class
            )"
        >
            <span>{{ message }}</span>
            <button
                @click="close"
                class="ml-2 inline-flex h-5 w-5 items-center justify-center rounded-full hover:bg-black/10"
            >
                <X class="h-4 w-4" />
                <span class="sr-only">Cerrar</span>
            </button>
        </div>
    </Transition>
</template> 