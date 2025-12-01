<template>
    <div class="cards-container">
        <a href="#">
            <Card footerText="Footer" size="md">
                <template #header>
                    <div>
                        <img src="images/icons/recruitment.png" alt="Recruitment Icon"/>
                        <span>Recruitment Selection and Payment</span>
                    </div>
                </template>
                <p>Description...</p>
            </Card>
        </a>

        <a href="#">
            <Card footerText="Footer" size="md">
                <template #header>
                    <div>
                        <img src="images/icons/self-development.png" alt="Recruitment Icon"/>
                        <span>Learning and Development</span>
                    </div>
                </template>
                <p>Description...</p>
            </Card>
        </a>

        <a href="#">
            <Card footerText="Footer" size="md">
                <template #header>
                    <div>
                        <img src="images/icons/software.png" alt="Recruitment Icon"/>
                        <span>Performance Management</span>
                    </div>
                </template>
                <p>Description...</p>
            </Card>
        </a>

        <a href="#">
            <Card footerText="Footer" size="md">
                <template #header>
                    <div style="display: flex; align-items: center;">
                        <img src="images/icons/reward.png" alt="Recruitment Icon"/>
                        <span>Reward and Recognition</span>
                    </div>
                </template>
                <p>Description...</p>
            </Card>
        </a>
    </div>
</template>

<script>
    import Card from "@/components/Common/Card.vue";
    import Tooltip from "@/components/Common/Tooltip.vue";

    export default {
        components: {
            Card,
            Tooltip,
        },
    };
</script>
