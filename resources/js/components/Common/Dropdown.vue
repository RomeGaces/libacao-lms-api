<template>
    <div class="dropdown" :class="placement">
        <div class="dropdown-trigger">
            <!-- If may trigger slot, use it -->
            <slot name="trigger">
                {{ label }}
            </slot>
        </div>
        <div class="dropdown-menu">
            <slot />
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        label: { type: String, default: "Menu" },
        placement: {
            type: String,
            default: "bottom-right",
        },
    });
</script>
