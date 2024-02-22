<template>
    <div>
        <Head title="Books" />
        <div class="flex justify-between mb-6">
            <div class="flex items-center">
                <h1 class="text-3xl">Books</h1>

                <Link :href="route('book.create')" class="text-blue-500 text-sm ml-3">Add Book</Link>
            </div>

            <input v-model="search" type="text" placeholder="Search..." class="border px-2 rounded-lg" />
        </div>



                <div>
                    <div class="row book-listing" v-for="book in books.data" :key="book.id">
                        <div class="col-md-3 book-listing__cover">
                            <img src='/images/book-layout-spine.svg' alt="book"/>
                            <img src='/images/book-layout-front.svg' alt="book"/>
                        </div>
                        <div class="col-md-9 book-listing__info">
                            <a :href="route('book.show', book.id)">
                                <h1>{{ book.book_title }}</h1>
                            </a>
                            <h2>{{ book.book_author }}</h2>
                            <span>{{ book.book_genre ?? 'genre' }}</span>
                            <div>
                                <img class="book-listing__info--icon" :src='PlaneIcon' />
                                    3 miles
                                </div>
                            <div>
                                <img class="book-listing__info--icon" :src='WorldIcon' />
                                    London
                                </div>
                            <div>
                                <img class="book-listing__info--icon" :src='PeopleIcon' alt="people viewed" />
                                    10
                                </div>
<!--                            <div class="book-listing__info&#45;&#45;rating">-->
<!--                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500">-->
<!--                                    <path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-gray-400"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path>-->
<!--                                </svg>-->
<!--                                <span class="ml-2">-->
<!--                                    34 ratings-->
<!--                                </span>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>


                                <!--        <div class="container">-->
<!--            <div class="component">-->
<!--                <ul class="align">-->
<!--                    <li v-for="book in books.data" :key="book.id">-->
<!--                        <figure class='book'>-->
<!--                            <ul class='hardcover_front'>-->
<!--                                <li>-->
<!--                                    <img src="img/cover.jpg" alt="" width="100%" height="100%">-->
<!--                                </li>-->
<!--                                <li></li>-->
<!--                            </ul>-->
<!--                            <ul class='page'>-->
<!--                                <li></li>-->
<!--                                <li>-->
<!--                                    <Link class="px-6 py-4 flex items-center" :href="route('book.show', book.id)" tabindex="-1">-->
<!--                                        Explore book-->
<!--                                    </Link>-->
<!--                                </li>-->
<!--                                <li></li>-->
<!--                                <li></li>-->
<!--                                <li></li>-->
<!--                            </ul>-->
<!--                            <ul class='hardcover_back'>-->
<!--                                <li></li>-->
<!--                                <li></li>-->
<!--                            </ul>-->
<!--                            <ul class='book_spine'>-->
<!--                                <li></li>-->
<!--                                <li></li>-->
<!--                            </ul>-->
<!--                            <figcaption>-->
<!--                                <h1>{{ book.book_title }}</h1>-->
<!--                                <h2>{{ book.book_author }}</h2>-->
<!--                                <span class="inline-block px-2 py-1 bg-blue-200 text-grey-800 rounded-full font-semibold uppercase text-xs">{{ book.book_genre }}</span>-->
<!--                                <div class="flex h-10"><img :src='PlaneIcon' class="w-10"/> 3 miles</div>-->
<!--                                <div class="flex h-10"><img :src='WorldIcon' class="w-10" /> London</div>-->
<!--                                <div class="flex h-10"><img :src='PeopleIcon' class="w-10" /> 10</div>-->
<!--                                <div class="p-4 pl-0 flex items-center text-sm text-gray-600"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-gray-400"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><span class="ml-2">34 Bewertungen</span></div>-->
<!--                            </figcaption>-->
<!--                        </figure>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->









<!--        <h1 class="mb-8 font-bold text-3xl">Book</h1>-->
<!--        <div class="mb-6 flex justify-between items-center">-->
<!--            &lt;!&ndash;            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">&ndash;&gt;-->
<!--            &lt;!&ndash;                <label class="block text-gray-700">Trashed:</label>&ndash;&gt;-->
<!--            &lt;!&ndash;                <select v-model="form.trashed" class="mt-1 w-full form-select">&ndash;&gt;-->
<!--            &lt;!&ndash;                    <option :value="null" />&ndash;&gt;-->
<!--            &lt;!&ndash;                    <option value="with">With Trashed</option>&ndash;&gt;-->
<!--            &lt;!&ndash;                    <option value="only">Only Trashed</option>&ndash;&gt;-->
<!--            &lt;!&ndash;                </select>&ndash;&gt;-->
<!--            &lt;!&ndash;            </search-filter>&ndash;&gt;-->
<!--            <Link class="btn-indigo" :href="route('book.create')">-->
<!--                <span>Create</span>-->
<!--                <span class="hidden md:inline">Book</span>-->
<!--            </Link>-->
<!--        </div>-->
<!--        <div v-for="book in books" :key="book.id" class="hover:bg-gray-100 focus-within:bg-gray-100">-->

<!--            <div class="p-1">-->
<!--                <div class="w-full flex border border-gray-400 rounded">-->
<!--                    <div class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden" style="background-image: url('https://ordinarycoders.com/_next/image?url=https%3A%2F%2Fd2gdtie5ivbdow.cloudfront.net%2Fmedia%2Fuser%2Fimages%2Ftailwind_card_example_2.png&w=1080&q=75')" title="Mountain">-->
<!--                    </div>-->
<!--                    <div class="w-full p-4 flex flex-col justify-between">-->
<!--                        <div class="mb-8">-->
<!--                            <p class="text-sm text-gray-600 flex items-center">-->
<!--                                <svg class="fill-current text-gray-500 w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">-->
<!--                                    <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />-->
<!--                                </svg>-->
<!--                                Members only-->
<!--                            </p>-->
<!--                            <div class="text-gray-900 font-bold text-xl mb-2">-->
<!--                                <Link class="px-6 py-4 flex items-center" :href="route('book.show', book.id)" tabindex="-1">-->
<!--                                    {{ book.book_title }}-->
<!--                                </Link>-->
<!--                            </div>-->
<!--                            <p class="text-gray-700 text-base">Description needs adding to db.</p>-->
<!--                        </div>-->
<!--                        <div class="flex items-center">-->
<!--                            <img class="w-10 h-10 rounded-full mr-4" src="/ben.png" alt="Avatar of Writer">-->
<!--                            <div class="text-sm">-->
<!--                                <p class="text-gray-900 leading-none">{{ book.book_author }}</p>-->
<!--                                <p class="text-gray-600">{{ book.book_genre }}</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <div v-if="books.length === 0">
            <td class="border-t px-6 py-4" colspan="4">No books found.</td>
        </div>


        <Pagination :links="books.links" class="mt-6" />
    </div>
</template>

<script setup>
// import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
// import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
// import Pagination from 'resources/Shared/Pagination.vue'
// import SearchFilter from '@/Shared/SearchFilter'
import PlaneIcon from '../../Shared/Icons/plane.svg'
import WorldIcon from '../../Shared/Icons/world.svg'
import PeopleIcon from '../../Shared/Icons/people.svg'
import Pagination from '../../Shared/Pagination';
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import debounce from "lodash/debounce";

let props = defineProps({
    books: Object,
    filters: Object,
    can: Object
});

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
    Inertia.get('/books', { search: value }, { preserveState: true, replace: true });
}, 300));

// export default {
//     metaInfo: { title: 'Book' },
//     components: {
//         // Icon,
//         // Pagination,
//         // SearchFilter,
//     },
    // layout: Layout,
    // props: {
    //     // filters: Object,
    //     books: Array,
    // },
    // data() {
    //     return {
    //         form: {
    //             // search: this.filters.search,
    //             // trashed: this.filters.trashed,
    //         },
    //     }
    // },
    // watch: {
    //     form: {
    //         deep: true,
    //         handler: throttle(function() {
    //             this.$inertia.get(this.route('books'), pickBy(this.form), { preserveState: true })
    //         }, 150),
    //     },
    // },
    // methods: {
    //     reset() {
    //         this.form = mapValues(this.form, () => null)
    //     },
    // },
// }
</script>
