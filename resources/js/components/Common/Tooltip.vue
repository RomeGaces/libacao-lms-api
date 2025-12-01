<template>
    <div class="tooltip" :class="`tooltip-${placement}`">
        <slot />
        <span class="tooltip-text">{{ text }}</span>
    </div>
</template>

<script setup>
    const props = defineProps({
        text: { type: String, required: true },
        placement: { type: String, default: "top" }, // top | bottom | left | right
    });
</script>
