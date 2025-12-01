<template>
    <div class="playground-container">
        <h1>Playground</h1>
        <div class="playground-content">
            
            <div class="components-wrapper">
                <h3>Modals</h3>
                <Button variant="secondary" size="md" @click="showModal = true">
                    Open Modal
                </Button>
            </div>

            <div class="components-wrapper">
                <h3>Buttons</h3>
                <Button variant="secondary" size="lg">Button Large</Button>
                <Button variant="secondary" size="md">Button Medium</Button>
                <Button variant="secondary" size="sm">Button Small</Button>
                <Button variant="secondary" size="xs">Button X-Small</Button>
            </div>

            <div class="components-wrapper">
                <h3>Cards</h3>
                <Card size="sm" title="Small Card" footer-text="Last updated: Today">
                    <p>This is a small card.</p>
                </Card>

                <Card size="md">
                    <template #header>Medium Card</template>
                    <p>This is a medium card.</p>
                    <template #footer>Footer</template>
                </Card>

                <Card size="lg" title="Large Card">
                    <p>This is a large card.</p>
                </Card>
            </div>
           
            <div class="components-wrapper">
                <h3>Dropdown</h3>
                <Dropdown style="margin-right: 20px;" label="Bottom Right" placement="bottom-right">
                    <a href="#">Account Settings</a>
                    <a href="#">Logout</a>
                </Dropdown>

                <!-- Appear on bottom left -->
                <Dropdown style="margin-right: 20px;" label="Bottom Left" placement="bottom-left">
                    <a href="#">Message 1</a>
                    <a href="#">Message 2</a>
                </Dropdown>

                <!-- Appear on top left -->
                <Dropdown label="Top Left" placement="top-left">
                    <a href="#">Message 1</a>
                    <a href="#">Message 2</a>
                </Dropdown>
            </div>
            
            <div class="components-wrapper">
                <h3>Tooltip</h3>
                <Tooltip text="Tooltip on Top" placement="top">
                    <Button variant="secondary" size="sm">Top Tooltip</Button>
                </Tooltip>

                <Tooltip text="Tooltip on Bottom" placement="bottom">
                    <Button variant="secondary" size="sm">Bottom Tooltip</Button>
                </Tooltip>

                <Tooltip text="Tooltip on Left" placement="left">
                    <Button variant="secondary" size="sm">Left Tooltip</Button>
                </Tooltip>

                <Tooltip text="Tooltip on Right" placement="right">
                    <Button variant="secondary" size="sm">Right Tooltip</Button>
                </Tooltip>
            </div>

            <div class="components-wrapper">
                <h3>Input</h3>
                <Input v-model="name" name="awdaw" placeholder="Enter your name" size="sm" />
                <Input v-model="name" name="drgdrg" placeholder="Enter your name" size="md" />
                <Input v-model="name" name="jijo" placeholder="Enter your name" size="lg" />
            </div>

            <!-- Modal -->
            <Modal v-model:modelValue="showModal" />
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue";
    import Modal from "@/components/Common/Modal.vue";
    import Button from "@/components/Common/Button.vue";
    import notify from "@/Services/NotificationService.js";
    import Card from "@/components/Common/Card.vue";
    import Dropdown from "../Components/Common/Dropdown.vue";
    import Tooltip from "../Components/Common/Tooltip.vue";
    import Input from "../Components/Common/Input.vue";

    const showModal = ref(false);
    const isSuccessFetch = true;

    // ✅ Show notification automatically on mount
    onMounted(() => {
        /*Sample Notification*/
        /*
    notify("success", "Welcome to the Playground!");
    notify("error", "This is an error example!");
    notify("warning", "This is a warning example!");
    notify("success", "Top Left Example", { position: "top-left" });
    */

        if (isSuccessFetch) {
            notify("success", "Data Fetch Successfully!");
        } else {
            notify("error", "Data Failed to Fetch!");
        }
    });
</script>

<style scoped>
    .playground-container {
        margin: 40px;
    }

    .playground-content {
        margin-top: 40px;
    }

    /* 🔽 Stack buttons vertically */
    .components-wrapper {
        display: flex;
        gap: 12px; /* spacing between buttons */
        width: 100%; /* optional: limit width */
        margin-bottom: 40px;
        align-items: flex-start;
    }
</style>
