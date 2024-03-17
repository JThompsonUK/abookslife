<template>
    <div class="py-4">
        <div class="p-5 mb-4 bg-light rounded-3">

            <h1>Add New Book</h1>
            <form @submit.prevent="store">

            <div class="container-fluid row">

                <div class="col-md-2">
                    <div :style="{
                        backgroundImage: 'url(/images/book-layout-front.svg)',
                        width: '100%',
                        height: '100%',
                        backgroundRepeat: 'no-repeat',
                        maxHeight: '240px'
                    }">
                        <img  v-if="imagePreview" :src="imagePreview" alt="Book Cover Image" style="width: 100%; padding: 20px 25px 20px 20px;" />
                    </div>                    
                    <div>
                        <input type="file" @change="handleFileUpload" />
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="form-row mb-3">
                            <div class="col-md-8 p-0">
                                <label for="title">Title:</label>
                                <input v-model="form.book_title" @input="debouncedSearch" type="text" class="form-control" id="title" placeholder="Title" name="title" required />
                                <!-- Books from Google Books API response -->
                                <ul v-if="searchResults.length">
                                    <li v-for="result in searchResults" :key="result.id" @click="selectBook(result)">
                                        {{ result.title }}
                                    </li>
                                </ul>
                            </div>
                            <!-- <div class="col-md-4 pr-0">
                                <label for="genre">Genre</label>
                                <input v-model="form.book_genre" type="text" class="form-control" id="genre" placeholder="Genre" name="genre" required />
                            </div> -->
                    </div>
                    <div class="form-row mb-3">
                        <label for="author">Author</label>
                        <input v-model="form.book_author" type="text" class="form-control" id="author" placeholder="Author" name="author" required />
                    </div>
                    <div class="form-row mb-3">
                        <label for="description">Description</label>
                        <textarea v-model="form.description" class="form-control" id="description" placeholder="Description" name="description" rows="3"></textarea>
                    </div>  

                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                        <button class="btn btn-primary" type="submit">Create Book</button>
                    </div>


                </div>










                <!-- <h1>Add New Book</h1>

                    <form @submit.prevent="store">
                        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                            <div class="form-row mb-3">
                                <div class="col-md-8 p-0">
                                    <label for="name">Title:</label>
                                    <input v-model="form.book_title" @input="debouncedSearch" type="text" class="form-control" id="title" placeholder="Name" name="title" required />
                                
                                    Books from Google Books API response
                                    <ul v-if="searchResults.length">
                                        <li v-for="result in searchResults" :key="result.id" @click="selectBook(result)">
                                            {{ result.title }}
                                        </li>
                                    </ul>

                                </div>
                                <div class="col-md-4 pr-0">
                                    <label for="genre">Author:</label>
                                    <input v-model="form.book_author" type="text" class="form-control" id="book_author" placeholder="Author" name="book_author" required />
                                </div>
                            </div> -->

                            <!-- <label for="name">Title</label>
                            <input v-model="form.book_title" type="text" class="form-control" id="title" placeholder="Name" name="title" required />

                            <label for="book_author">Book Author</label>
                            <input v-model="form.book_author" type="text" name="book_author" id="book_author" required /> -->

        <!--                    <text-input v-model="form.book_title" :error="form.errors.book_title" class="pr-6 pb-8 w-full lg:w-1/2" label="Title" />-->
        <!--                    <text-input v-model="form.book_author" :error="form.errors.book_author" class="pr-6 pb-8 w-full lg:w-1/2" label="Author" />-->
                        <!-- </div>
                        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                            <button class="btn btn-primary" type="submit">Create Book</button>
                        </div> -->
                </div>                    </form>

                </div>
    </div>
</template>

<script>
import Icon from '../../Shared/Forms/Icon.vue'
import TextInput from '../../Shared/Forms/TextInput'
import axios from 'axios';
import _ from 'lodash';

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
                book_cover: null,
                description: null,


            }),
            searchResults: [],
            imagePreview: null,

        }
    },
    methods: {
        // store() {
        //     this.form.post(this.route('book.store'))
        // },


        store() {
            this.form.post(this.route('book.store'));
        },
        // searchBook() {
        //     // setTimeout(() => {
        //         axios.post(route('book.store.search'), {
        //             book_title: this.form.book_title,
        //         })
        //         .then(res => {
        //                 console.log(res.data.data.items);
        //                 this.searchResults = res.data.data.items;

        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });

            // }, 1000);
            
            // this.searchResults = [
            //     { id: 'kLAoswEACAAJ', volumeInfo: { title: 'Harry Potter and the Cursed Child' } },
            //     // Add more results as needed
            // ];
        // },

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
            this.searchResults = [];
            this.imagePreview = book.thumbnail;

        },

        handleFileUpload(event) {
            const file = event.target.files[0];

            if (file) {
                this.form.book_cover = file;
                this.imagePreview = URL.createObjectURL(file);
            }
        },

    },
}
</script>
<style>
    input[type="file"] {
    color: transparent;
    }
</style>
