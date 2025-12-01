<template>
    <div :class="['card', sizeClass]">
        <!-- Header: slot o prop title -->
        <div v-if="$slots.header || title" class="card-header">
            <slot name="header">{{ title }}</slot>
        </div>

        <!-- Body -->
        <div class="card-body">
            <slot />
        </div>

        <!-- Footer: slot o prop footerText -->
        <div v-if="$slots.footer || footerText" class="card-footer">
            <slot name="footer">{{ footerText }}</slot>
        </div>
    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        size: { type: String, default: "md" }, // "sm" | "md" | "lg"
        title: { type: String, default: "" },
        footerText: { type: String, default: "" },
    });

    const sizeClass = computed(() => {
        switch (props.size) {
            case "sm":
                return "card-sm";
            case "lg":
                return "card-lg";
            default:
                return "card-md";
        }
    });
</script>
