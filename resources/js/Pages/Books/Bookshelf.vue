<template>
    <div>
        <Head title="Bookshelf" />
        <div class="py-4">

            <button class="btn btn-info mb-2" @click="toggleView">
               <span v-if="listView">
                    Bookshelf View
               </span>
               <span v-else>
                    List View
               </span>
            </button>

            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid">
                    <div v-for="book in books" :key="book.id">

                        <div v-if="listView">
                            {{ book.book_title }}                    
                        </div>

                        <div v-else class="row">
                            <div class="col-md-2">
                                <div :style="{
                                    backgroundImage: 'url(/images/book-layout-front.svg)',
                                    width: '100%',
                                    height: '100%',
                                    backgroundRepeat: 'no-repeat',
                                    maxHeight: '240px',
                                    maxWidth: '170px'
                                }">
                                    <img v-if="book.image" :src="book.image" alt="Book Cover Image" style="width: 100%; padding: 20px 25px 20px 20px; height: auto;" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <a :href="route('book.show', book.uuid)">
                                    <h3>{{ book.book_title }}</h3>
                                </a>
                                <h6 class="pb-2">{{ book.book_author }}</h6>

                                <div v-if="book.book_genre" class="d-flex">
                                    <img class="book-listing__info--icon" :src='this.bookIcon' />
                                    {{ book.book_genre }}
                                </div>
                                <div class="d-flex">
                                    <img class="book-listing__info--icon" :src='this.planeIcon' />
                                    {{ book.distance_travelled }} miles
                                </div>
                                <div class="d-flex">
                                    <img class="book-listing__info--icon" :src='this.worldIcon' />
                                    <span>
                                        {{ book.book_checkout[0] ? book.book_checkout[0]['city'] : null }}
                                    </span>
                                    <span v-if="book.book_checkout.length > 1">
                                        -> {{ book.book_checkout[book.book_checkout.length-1] ? book.book_checkout[book.book_checkout.length-1]['city'] : null }}
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <img class="book-listing__info--icon" :src='this.peopleIcon' alt="people viewed" />
                                    {{ book.book_checkout.length }}
                                </div>
                            </div>
                                
                            <div class="col-md-2 book-listing__info" v-if="book.isOwner">
                                <img class="book-listing__info--icon" :src='this.bookmarkStarIcon' alt="You are this books owner"/>
                            </div>
                        </div>

                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PlaneIcon from '../../Shared/Icons/plane.svg'
import WorldIcon from '../../Shared/Icons/world.svg'
import PeopleIcon from '../../Shared/Icons/people.svg'
import BookmarkStarIcon from '../../Shared/Icons/bookmarkStar.svg'
import BookIcon from '../../Shared/Icons/book.svg'

export default {    
    props: {
        books: Object,
    },

    data() {
        return {
            listView: false,
            planeIcon: PlaneIcon,
            worldIcon: WorldIcon,
            peopleIcon: PeopleIcon,
            bookmarkStarIcon: BookmarkStarIcon,
            bookIcon: BookIcon,

        };
    },

    methods: {
        toggleView() {
            this.listView = !this.listView;
        },
    },

}
</script>
