<template>
    <div v-if="modelValue" class="modal" @click.self="close">
        <div :class="['modal-content', sizeClass, heightClass]">
            <!-- Header -->
            <div class="modal-header">
                <h2>{{ title }}</h2>
                <span class="close" @click="close">
                    <img class="icon" src="images/icons/close.png"/>
                </span>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <slot />
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, watch } from "vue";

const props = defineProps({
    modelValue: { type: Boolean, default: false },
    title: { type: String, default: "Modal Title" },
    size: { type: String, default: "md" }, // sm, md, lg, full (width)
    height: { type: String, default: "auto" }, // sm, md, lg, full, auto
});

const emit = defineEmits(["update:modelValue", "save"]);

const close = () => {
    emit("update:modelValue", false);
};

// Width class
const sizeClass = computed(() => {
    switch (props.size) {
        case "sm": return "modal-sm";
        case "lg": return "modal-lg";
        case "full": return "modal-full";
        default: return "modal-md";
    }
});

// Height class
const heightClass = computed(() => {
    switch (props.height) {
        case "sm": return "modal-h-sm";
        case "lg": return "modal-h-lg";
        case "full": return "modal-h-full";
        case "md": return "modal-h-md";
        default: return "";
    }
});

// ✅ Watch for modal open/close to disable body scroll
watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal) {
            document.body.classList.add("modal-open");
        } else {
            document.body.classList.remove("modal-open");
        }
    },
    { immediate: true }
);
</script>
