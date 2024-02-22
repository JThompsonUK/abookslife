<template>
    <div>
        <Head title="Book Item" />

            <div class="book-item">
                <a v-if="isOwner" :href="route('book.edit', book.id)">
                    <button>Edit</button>
                </a>
                <div class="row book-item__info">
                    <div class="col-md-2">
                        <img src='/images/book-layout-front.svg' />
                    </div>

                    <div class="col-md-10">
                        <div class="book-item__right">

                            <h2>{{ book.book_title }}</h2>
                            <h5>{{ book.book_author }}</h5>
                            <span>Genre: {{ book.book_genre }}</span>
                            <div>
                                <img class="book-item__right--icon" src='../../Shared/Icons/plane.svg' />
                            </div>
                            <div>
                                <img class="book-item__right--icon" src='../../Shared/Icons/plane.svg' />
                                {{ this.distanceTravelled }} miles
                            </div>
                            <div>
                                <img class="book-item__right--icon" src='../../Shared/Icons/world.svg' />
                                {{ book.book_checkout[0]['city'] }}
                                <span v-if="book.book_checkout.length > 1">
                                    -> {{ book.book_checkout[book.book_checkout.length-1]['city'] }}
                                </span>
                            </div>
                            <div>
                                <img class="book-item__right--icon" src='../../Shared/Icons/people.svg' alt="people viewed" />
                                {{ book.book_checkout.length }}
                            </div>
<!--                            <div class="book-item__right&#45;&#45;rating">-->
<!--                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500">-->
<!--                                    <path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-gray-400"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path>-->
<!--                            </svg>-->
<!--                                <span class="ml-2">-->
<!--                                        34 ratings-->
<!--                                    </span>-->
<!--                            </div>-->
                            <p>{{ book.description }}</p>



                            <div class="book-item__right--qr">
                                Attach this qr to the book, or write the unique code, so people can easily find this page.
                                ref: {{ book.reference }}
                                qr: <qrcode-vue :value="value" :size="size" background="#FFF" foreground="#333" level="L" />
                            </div>

                            <div>
                                if logged in and your the last person to checkout book:
                                reference: 12345
                                pincode (used when someone clicks checkout): 1234
                            </div>

                            <div class="book-item__right--checkout">
                                <div v-if="this.isCheckedOut">
                                    <div v-if="this.isWithUser">
                                        this is checked out by you
                                        <button>Return book</button>
                                    </div>
                                    <div v-else>
                                        this is still checked out by another user.
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="this.lastWithUser">
                                        this was last checked out by you! pass this book to someone else for a read.
                                    </div>
                                    <div v-else>
                                        <button type="submit" @click="checkout">Checkout book</button>
                                    </div>

                                </div>
                            </div>




                        </div>
                    </div>
                </div>

                <hr>



                <div class="row book-item__checkout">
                    <div class="col-md-6">
                        <Checkouts :bookCheckouts="book.book_checkout" :book-id="book.id" />
                    </div>





                    <div class="col-md-6">
                        <h5>Books Journey</h5>


<!--                        <GoogleMap-->
<!--                            api-key="AIzaSyCYhCxQiuTzX8x1FtUJX9ngVSkzaavqY3Q"-->
<!--                            style="width: 100%; height: 500px"-->
<!--                            :center="center"-->
<!--                            :zoom="zoom"-->
<!--                        >-->
<!--                            <MarkerCluster>-->
<!--                                <Marker v-for="(location, i) in locations" :options="{ position: location }" :key="i" />-->
<!--                            </MarkerCluster>-->
<!--                        </GoogleMap>-->


                    </div>
                </div>

                <hr>

                <div class="row book-item__comments">
                    <div class="col-md-12">
                        <h5>Comments:</h5>
                        <Comments :comments="comments" :book-id="book.id" />
                    </div>
                </div>

                <hr>

                <div class="row book-item__related">
                    <div class="col-md-12">
                        Relate Books:
                    </div>
                </div>


            </div>

    </div>
</template>

<script>
import Icon from '../../Shared/Forms/Icon.vue'
import QrcodeVue from 'qrcode.vue'
import PlaneIcon from '../../Shared/Icons/plane.svg'
import WorldIcon from '../../Shared/Icons/world.svg'
import PeopleIcon from '../../Shared/Icons/people.svg'
import { GoogleMap, Marker, MarkerCluster } from "vue3-google-map";
import { reactive, watch } from 'vue';
import Comments from './Comments'
import Checkouts from "./Checkouts";

export default {
    metaInfo() {
        return { title: this.book.book_title }
    },
    components: {
        Checkouts,
        Icon,
        QrcodeVue,
        GoogleMap,
        Marker,
        MarkerCluster,
        Comments,
    },
    setup(props) {
        const state = reactive({
            center: props.mapCenter,
            zoom: props.zoom,
            locations: [],
        });
        state.locations = props.markers;
        return state;
    },

    props: {
        book: Object,
        comments: Object,
        isCheckedOut: Boolean,
        isWithUser: Boolean,
        lastWithUser: Boolean,
        isOwner: Boolean,
        distanceTravelled: Number,
        markers: {
            type: Array,
            default: () => [],
        },
        mapCenter: {
            type: Object,
            default: () => ({ lat: 0, lng: 0 }),
        },
        zoom: {
            type: Number,
            default: 2,
        },
    },

    methods: {
        checkout() {
            this.$inertia.post(this.route('book.checkout', this.book.id))
        },
    },
}
</script>
