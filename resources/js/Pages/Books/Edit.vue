<template>
    <div>
        <div>
            <h1>{{ formData.book_title }}</h1>
        </div>

        <div>
            <form @submit.prevent="submitForm">
                <div class="form-row mb-3">
                    <label for="image">Book Cover:</label>
                    <input type="file" @change="handleFileUpload" />

<!--                    <img :src="'/storage/images/' + '7hJpO5ToX0qPaBEEQnfKpZeESdUFQ5pjmo019ABc.jpg'" alt="" title="" />-->

<!--                    <img :src="'/storage/images/7hJpO5ToX0qPaBEEQnfKpZeESdUFQ5pjmo019ABc.jpg'" alt="" title="" />-->

                    <img :src="book.image" alt="Profile Image" class="img-fluid rounded" />

                    <img :src="'/' + previewImage" alt="Book Cover Preview" v-if="previewImage" style="max-width: 200px; margin-top: 10px;" />
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-8 p-0">
                        <label for="title">Title:</label>
                        <input v-model="formData.book_title" type="text" class="form-control" id="title" placeholder="Title" name="title" required />
                    </div>
                    <div class="col-md-4 pr-0">
                        <label for="genre">Genre</label>
                        <input v-model="formData.book_genre" type="text" class="form-control" id="genre" placeholder="Genre" name="genre" required />
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label for="author">Author</label>
                    <input v-model="formData.book_author" type="text" class="form-control" id="author" placeholder="Author" name="author" required />
                </div>
                <div class="form-row mb-3">
                    <label for="description">Description</label>
                    <textarea v-model="formData.description" class="form-control" id="description" placeholder="Description" name="description" rows="3" required></textarea>
                </div>
                <div>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        book: Object,
    },
    data() {
        return {
            formData: {
                book_cover: this.book.book_cover,
                book_title: this.book.book_title,
                book_genre: this.book.book_genre,
                book_author: this.book.book_author,
                description: this.book.description,
            },
            previewImage: null,
        };
    },
    methods: {
        submitForm() {
            this.$inertia.post(`/submit-form/${this.book.id}`, this.formData);
        },
        handleFileUpload(event) {
            const file = event.target.files[0];

            if (file) {
                this.formData.book_cover = file;
                this.previewImage = URL.createObjectURL(file);
            }
        },
    },
};
</script>
