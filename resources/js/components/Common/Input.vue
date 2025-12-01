<template>
    <input :type="type" :name="name" :placeholder="placeholder" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" :class="[`input-${size}`]" />
</template>

<script setup>
    const props = defineProps({
        modelValue: { type: String, default: "" },
        placeholder: { type: String, default: "" },
        type: { type: String, default: "text" },
        name: { type: String, default: null },
        size: { type: String, default: "md" }, // sm | md | lg
    });

    const emit = defineEmits(["update:modelValue"]);
</script>
