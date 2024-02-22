<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <Link class="text-indigo-400 hover:text-indigo-600" :href="route('books')">Books </Link>

            <span class="text-indigo-400 font-medium">/</span> Create
        </h1>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="store">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">


                    <label for="name">Name</label>
                    <input v-model="form.book_title" type="text" name="title" id="title" required />
                    <label for="book_author">Book Author</label>
                    <input v-model="form.book_author" type="text" name="book_author" id="book_author" required />

<!--                    <text-input v-model="form.book_title" :error="form.errors.book_title" class="pr-6 pb-8 w-full lg:w-1/2" label="Title" />-->
<!--                    <text-input v-model="form.book_author" :error="form.errors.book_author" class="pr-6 pb-8 w-full lg:w-1/2" label="Author" />-->
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                    <button class="btn-indigo" type="submit">Create Book</button>
                </div>
            </form>
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
                book_title: null,
                book_author: null,
            }),
        }
    },
    methods: {
        store() {
            this.form.post(this.route('book.store'))
        },
    },
}
</script>
