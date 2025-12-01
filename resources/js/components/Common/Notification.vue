<template>
    <transition name="notification" appear>
        <div v-if="visible" :class="['notification', type, position]" role="alert">
            <span class="message-text">{{ message }}</span>
            <button class="close-btn" @click="close" aria-label="Close">
                <img src="images/icons/close.png" />
            </button>
        </div>
    </transition>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount } from "vue";

    const props = defineProps({
        type: { type: String, default: "info" },
        message: { type: String, required: true },
        duration: { type: Number, default: 3000 },
        position: {
            type: String,
            default: "top-right",
            validator: (v) => ["inline", "top-left", "top-right", "bottom-left", "bottom-right"].includes(v),
        },
    });

    const emit = defineEmits(["close"]);

    const visible = ref(true);
    let timer = null;

    const close = () => {
        visible.value = false;
        if (timer) clearTimeout(timer);
        setTimeout(() => emit("close"), 300); // 300ms = match sa transition leave
    };

    onMounted(() => {
        if (props.duration > 0) {
            timer = setTimeout(() => {
                close();
            }, props.duration);
        }
    });

    onBeforeUnmount(() => {
        if (timer) clearTimeout(timer);
    });
</script>
