import { createApp, h } from "vue";
import Notification from "@/components/Common/Notification.vue";

const showNotification = (type, message, options = {}) => {
    const { duration = 3000, position = "top-right" } = options;
    const container = document.createElement("div");
    document.body.appendChild(container);

    const app = createApp({
        render() {
            return h(Notification, {
                type,
                message,
                duration,
                position,
                onClose: () => {
                    app.unmount();
                    document.body.removeChild(container);
                },
            });
        },
    });

    app.mount(container);
};

export default showNotification;
