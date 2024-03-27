<template>
    <Head title="Log In" />

    <main class="container mt-5">
        <section class="bg-white p-4 rounded-xl max-w-md mx-auto border">
            <h1 class="text-3xl mb-4 text-center">Log In</h1>

            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        v-model="form.email"
                        class="form-control"
                        type="email"
                        name="email"
                        id="email"
                        required
                    />

                    <div
                        v-if="form.errors.email"
                        v-text="form.errors.email"
                        class="text-danger mt-1"
                    ></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        v-model="form.password"
                        class="form-control"
                        type="password"
                        name="password"
                        id="password"
                    />

                    <div
                        v-if="form.errors.password"
                        v-text="form.errors.password"
                        class="text-danger mt-1"
                    ></div>
                </div>

                <div class="d-grid">
                    <button
                        type="submit"
                        class="btn btn-info mr-2"
                        :disabled="form.processing"
                    >
                        Log In
                    </button>
                    
                        
                    <a
                        class="btn btn-primary"
                        href="/users/create"
                    >
                        Register
                    </a>
                </div>
            </form>
        </section>
    </main>
</template>


<script>
export default {
    layout: null,
};
</script>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
const props = defineProps(['redirect']);
const redirect = ref(props.redirect);

let form = useForm({
    email: '',
    password: '',
    redirect: redirect.value,
});


let submit = () => {
    form.post('/login');
};

</script>
