<template>
    <div class="py-4">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid">
                <h1>Edit Profile</h1>

                <form @submit.prevent="submitForm">
                    <div class="form-row mb-3">
                        <div class="col-md-8 p-0">
                            <label for="name">Name:</label>
                            <input v-model="formData.name" type="text" class="form-control" id="name" placeholder="Name" name="name" required />
                        </div>
                        <div class="col-md-4 pr-0">
                            <label for="genre">Email:</label>
                            <input v-model="formData.email" type="email" class="form-control" id="email" placeholder="Email" name="email" required />
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,
    },
    data() {
        return {
            formData: {
                name: this.user.name,
                email: this.user.email,
            },
        };
    },
    methods: {
        submitForm() {
            this.$inertia.post(`/users/profile/update/${this.user.id}`, this.formData);
        },
    },
};
</script>
