<template>
    <main class="login-container">
        <section class="left" aria-label="Decorative Image"></section>

        <section class="right">
            <img src="/images/logo.png" alt="Company Logo" class="logo" />
            <h1>LOGIN</h1>

            <!-- Flash / Auth Errors -->
            <div v-if="flashError" class="error-message">
                <span>{{ flashError }}</span>
            </div>

            <!-- Validation Errors -->
            <div v-if="Object.keys(form.errors).length" class="error-message">
                <ul>
                    <li v-for="(errorMessages, field) in form.errors" :key="field">
                        <span v-for="(error, index) in [].concat(errorMessages)" :key="index">
                            {{ error }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Login form -->
            <form @submit.prevent="submit">
                <label for="email">GSIS ID</label>
                <input type="text" id="email" v-model="form.email" placeholder="GSIS ID" required
                    autocomplete="username" />

                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password"
                        placeholder="Password" required autocomplete="current-password" />
                    <span class="toggle-password" @click="togglePassword">
                        <img src="images/icons/hide-pass.png" v-if="!showPassword" />
                        <img src="images/icons/show-pass.png" v-else />
                    </span>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" v-model="form.remember" />
                        <label for="remember">Remember Me</label>
                    </div>

                    <div class="forgot-password">
                        <a @click.prevent="openForgotPasswordModal" href="#">Forgot Password?</a>
                    </div>
                </div>

                <Button variant="primary" size="lg" type="submit" :disabled="form.processing">
                    <span v-if="form.processing">Logging in...</span>
                    <span v-else>Login</span>
                </Button>
            </form>

            <ForgotPasswordModal v-model="showForgotPasswordModal" />
        </section>
    </main>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Button from "@/components/Common/Button.vue";
import ForgotPasswordModal from "./ForgotPasswordModal.vue";

// Form state
const form = useForm({
    email: "",
    password: "",
});

const showPassword = ref(false);
const showForgotPasswordModal = ref(false);
const page = usePage();

// Flash error from backend (session('error'))
const flashError = computed(() => page.props.flash?.error || "");

// Toggle functions
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const openForgotPasswordModal = () => {
    showForgotPasswordModal.value = true;
};

// Submit form
const submit = () => {
    form.post("/login", {
        onError: (errors) => {
            console.error("Validation/Login errors:", errors);
        },
        onFinish: () => form.reset("password"),
    });
};
</script>
<style>
.form-options {
    display: flex;
    justify-content: space-between;
    /* push them to opposite sides */
    align-items: center;
    /* vertically align */
    margin: 0.5rem 0 1rem;
}

.remember-me {
    margin-top: -10px;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    /* small spacing between checkbox and label */
    font-size: 0.9rem;
}

.forgot-password {
    font-size: 0.9rem;
    text-decoration: none;
    color: #333;
}

.forgot-password:hover {
    text-decoration: underline;
}
</style>
