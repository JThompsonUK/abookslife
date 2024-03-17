<template>
    <div>
        <Head title="Books" />
        <div class="flex justify-between mb-6">
            <div class="flex items-center">
                <Link :href="route('book.create')" class="text-blue-500 text-sm ml-3">Add Book</Link>
            </div>
            <input v-model="search" type="text" placeholder="Search..." class="border px-2 rounded-lg" />
        </div>

        <div class="py-4">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid row" v-for="book in books.data" :key="book.id">
                    <div class="col-md-2">
                        <div :style="{
                            backgroundImage: 'url(/images/book-layout-front.svg)',
                            width: '100%',
                            height: '100%',
                            backgroundRepeat: 'no-repeat',
                            maxHeight: '240px'
                        }">
                            <img :src="book.image" alt="Book Cover Image" style="width: 100%; padding: 20px 25px 20px 20px;" />
                        </div>
                    </div>
                    <div class="col-md-10">
                        <a :href="route('book.show', book.uuid)">
                            <h3>{{ book.book_title }}</h3>
                        </a>
                        <h6>{{ book.book_author }}</h6>
                        <span>{{ book.book_genre ?? 'genre' }}</span>
                        <div>
                            <img class="book-listing__info--icon" :src='PlaneIcon' />
                            {{ book.distance_travelled }} miles
                        </div>
                        <div>
                            <img class="book-listing__info--icon" :src='WorldIcon' />
                            <span>
                                {{ book.book_checkout[0] ? book.book_checkout[0]['city'] : null }}
                            </span>
                            <span v-if="book.book_checkout.length > 1">
                                -> {{ book.book_checkout[book.book_checkout.length-1] ? book.book_checkout[book.book_checkout.length-1]['city'] : null }}
                            </span>
                        </div>
                        <div>
                            <img class="book-listing__info--icon" :src='PeopleIcon' alt="people viewed" />
                            {{ book.book_checkout.length }}
                        </div>

                        <hr>
                    
                    </div>
                </div>
            </div>
        </div>

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
