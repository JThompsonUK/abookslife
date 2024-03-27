<template>
    <Head title="Book Item Edit" />
    <div class="py-4">
        <div class="mb-2">
            Edit book        
        </div>
        <div class="p-5 mb-4 bg-light rounded-3">
            <form @submit.prevent="submitForm">
                <div class="container-fluid row">
                    <div class="col-md-2">
                        <div :style="{
                            backgroundImage: 'url(/images/book-layout-front.svg)',
                            width: '100%',
                            height: '100%',
                            backgroundRepeat: 'no-repeat',
                            maxHeight: '240px'
                        }">
                            <img v-if="imagePreview" :src="imagePreview" alt="Book Cover Image" style="width: 100%; padding: 20px 25px 20px 20px;" />
                        </div>                    
                        <div>
                            <input type="file" @change="handleFileUpload" />
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="form-row mb-3">
                            <div class="col-md-8 p-0">
                                <label for="title">Title (start typing to auto populate):</label>
                                <input v-model="form.book_title" @input="debouncedSearch" type="text" class="form-control" id="title" placeholder="Title" name="title" required />
                                
                                <!-- Books from Google Books API response -->
                                <div v-if="searchResults.length" style="overflow: scroll; max-height: 500px;">
                                    <div class="row search-results" v-for="result in searchResults" :key="result.id" @click="selectBook(result)">
                                        <div class="col-md-1">
                                            <img v-if="result.thumbnail" :src="result.thumbnail" alt="Book Cover Image" style="width: 40px;" />
                                        </div>
                                        <div class="col-md-10">
                                            <span>{{ result.title }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 pr-0">
                                <label for="genre">Genre</label>
                                <input v-model="form.book_genre" type="text" class="form-control" id="genre" placeholder="Genre" name="genre" />
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-8 p-0">
                                <label for="author">Author</label>
                                <input v-model="form.book_author" type="text" class="form-control" id="author" placeholder="Author" name="author" required />
                            </div>
                            <div class="col-md-4 pr-0">
                                <label for="published">Published Date</label>
                                <input v-model="form.published" type="text" class="form-control" id="author" placeholder="Published Date" name="published" />
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <label for="description">Description</label>
                            <textarea v-model="form.description" class="form-control" id="description" placeholder="Description" name="description" rows="3"></textarea>
                        </div>

                        <div>
                            <a :href="route('book.show', book.uuid)" class="btn btn-info mr-2">
                                Cancel
                            </a>    
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';

export default {
    props: {
        book: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                _method: 'post',
                book_cover: this.book.book_cover,
                book_title: this.book.book_title,
                book_genre: this.book.book_genre,
                book_author: this.book.book_author,
                description: this.book.description,
                published: this.book.published,
            }),
            searchResults: [],
            imagePreview: this.book.image,
        };
    },
    methods: {
        submitForm() {
            // this.$inertia.post(`/submit-form/${this.book.uuid}`, this.form);
            this.form.post(this.route('submit.form', this.book.uuid));
            
        },

        handleFileUpload(event) {
            const file = event.target.files[0];

            if (file) {
                this.form.book_cover = file;
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        debouncedSearch: _.debounce(function () {
            // Only trigger the search if the input length is greater than 3
            if (this.form.book_title.length > 3) {
                this.searchBook();
            }
        }, 500),
        
        searchBook() {
            this.searchResults = []
            axios.post(route('book.store.search'), {
                book_title: this.form.book_title,
            })
            .then(res => {
                this.searchResults = res.data.data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        },
        
        selectBook(book) {
            // Set the selected book title to the form and clear search results
            this.form.book_title = book.title;
            this.form.book_author = book.author;
            this.form.book_cover = book.thumbnail;
            this.form.description = book.description;
            this.form.book_genre = book.genre;
            this.form.published = book.published;
            this.searchResults = [];
            this.imagePreview = book.thumbnail;

        },
    },
};
</script>
<style>
    input[type="file"] {
        color: transparent;
    }

    .search-results {
        margin: 5px 0px;
        border: 1px solid lightgrey;
        border-radius: 5px;
        padding: 5px;
        cursor: pointer;
    }

    .search-results:hover {
        background-color: lightgray;
    }
</style>