<template>
    <Modal v-model="showForgotPasswordModal" title="Forgot Password" size="sm" height="sm">
        <div>
            Enter Your Employee ID
            <Input name="Employee ID" placeholder="Employee ID" size="md" />
        </div>

        <template #footer>
            <Button variant="secondary" size="md">Change Password</Button>
        </template>
    </Modal>
</template>

<script setup>
    import { ref } from "vue";
    import Modal from "@/components/Common/Modal.vue";
    import Button from "@/components/Common/Button.vue";
    import Input from "@/Components/Common/Input.vue";

    const showForgotPasswordModal = ref(false);

    const open = () => {
        showForgotPasswordModal.value = true;
    };
    const close = () => {
        showForgotPasswordModal.value = false;
    };

    defineExpose({
        open,
        close,
    });
</script>
