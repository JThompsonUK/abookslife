<template>
    <div>
        <h1>Create User</h1>
        <div class="py-4">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid row">            
                    <form @submit.prevent="store">
                        <div class="container-fluid row">
                            <label for="name">Name:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Name" name="name" required />

                            <label for="email">Email</label>
                            <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email" name="email" required />

                            <label for="password">Password</label>
                            <input v-model="form.password" type="password" class="form-control" id="password" placeholder="Password" name="password" required />
                        
                        </div>
                        <div class="py-4">
                            <button class="btn btn-primary" type="submit">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from '../../Shared/Forms/Icon.vue'
import TextInput from '../../Shared/Forms/TextInput'

export default {
    components: {
        Icon,
        TextInput,
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: 'post',
                name: null,
                email: null,
                password: null,
            }),
        }
    },
    methods: {
        store() {
            this.form.post(this.route('users.store'))
        },
    },
}
</script>