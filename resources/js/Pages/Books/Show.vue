<template>
    <div>
        <Head title="Book Item" />
        <div class="py-4">

            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2">
                            <div :style="{
                                backgroundImage: 'url(/images/book-layout-front.svg)',
                                width: '100%',
                                height: '100%',
                                backgroundRepeat: 'no-repeat',
                                maxHeight: '240px'
                            }">
                                <img v-if="book.image" :src="book.image" alt="Book Cover Image" style="width: 100%; padding: 20px 25px 20px 20px;" />
                            </div>     
                        </div>

                        <div class="col-md-8">
                            <div class="d-flex justify-content-between">
                                <h1>{{ book.book_title }}</h1>
                                <div>
                                    <span>
                                        <img v-if="isOwner" class="book-listing__info--icon mr-2" src='../../Shared/Icons/bookmarkStar.svg' alt="You are this books owner"/>
                                        <img v-if="userHasRead" class="book-listing__info--icon" src='../../Shared/Icons/bookmarkHeart.svg' alt="You have read this book"/>
                                    </span>
                                </div>
                            </div>
                            
                            <h6 class="pb-2">
                                {{ book.book_author }}
                            </h6>

                            <div v-if="book.book_genre" class="d-flex">
                                <img class="book-listing__info--icon" src='../../Shared/Icons/book.svg' />
                                {{ book.book_genre }}
                            </div>
                            <div class="d-flex">
                                <img class="book-item__right--icon" src='../../Shared/Icons/plane.svg' />
                                {{ this.distanceTravelled }} miles
                            </div>
                            <div class="d-flex">
                                <img class="book-item__right--icon" src='../../Shared/Icons/world.svg' />
                                <span>
                                    {{ book.book_checkout[0] ? book.book_checkout[0]['city'] : null }}
                                </span>
                                <span v-if="book.book_checkout.length > 1">
                                    -> {{ book.book_checkout[book.book_checkout.length-1] ? book.book_checkout[book.book_checkout.length-1]['city'] : null }}
                                </span>
                            </div>
                            <div class="d-flex">
                                <img class="book-item__right--icon" src='../../Shared/Icons/people.svg' alt="people viewed" />
                                {{ book.book_checkout.length }}
                            </div>
                            <!-- <div class="book-item__right&#45;&#45;rating">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500">
                                    <path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-yellow-500"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path></svg><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current text-gray-400"><path d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-.662 1.515 1.515 0 0 1-.096-.837l.736-4.247-3.13-3a1.514 1.514 0 0 1-.39-1.569c.09-.271.254-.513.475-.698.22-.185.49-.306.776-.35L8.66 7.73l1.925-3.862c.128-.26.328-.48.577-.633a1.584 1.584 0 0 1 1.662 0c.25.153.45.373.577.633l1.925 3.847 4.334.615c.29.042.562.162.785.348.224.186.39.43.48.704a1.514 1.514 0 0 1-.404 1.58l-3.13 3 .736 4.247c.047.282.014.572-.096.837-.111.265-.294.494-.53.662a1.582 1.582 0 0 1-1.643.117l-3.865-2-3.865 2z"></path>
                                </svg>
                                <span class="ml-2">
                                    34 ratings
                                </span>
                            </div> -->
                            <div class="d-flex py-2">
                                <p>{{ book.description }}</p>
                            </div>
                        </div>

                        <!-- QR CODE AND REFERENCE -->
                        <div class="col-md-2 text-right">

                            <!-- show qr code and reference if current user is book owner is checked out to -->
                            <div v-if="isOwner || isWithUser" class="text-center p-3" style="border: 1px dashed #000;">
                                <qrcode-vue :value="value" :size="size" background="#FFF" foreground="#333" level="L" />
                                Reference: {{ book.reference }}
                            </div>

                            <div class="mt-2 d-flex justify-content-end">
                                <!-- if book is with current user -->
                                <div v-if="this.isCheckedOut" class="mr-1">
                                    <div v-if="this.isWithUser">
                                        <img src='../../Shared/Icons/checkoutReturn.svg' alt="return" class="custom-icon" @click="returnModal" />
                                    </div>
                                    <div v-else>
                                        <img src='../../Shared/Icons/checkout.svg' alt="checkout book" class="custom-icon" @click="checkoutModal" />
                                    </div>
                                </div>

                                <!-- if user is logged in an wasnt the last person to check out book -->
                                <!-- <div v-else-if="!this.lastWithUser && loggedIn" class="mr-1">
                                    <img src='../../Shared/Icons/checkout.svg' alt="checkout book" class="custom-icon" @click="checkoutModal" />
                                </div> -->
                                
                                <!-- if book is not checked out and user is logged in-->
                                <div v-else-if="loggedIn" class="mr-1">
                                    <img src='../../Shared/Icons/checkout.svg' alt="checkout book" class="custom-icon" @click="checkoutModal" />
                                </div>

                                <!-- if current user is original owner of book -->
                                <a v-if="isOwner" :href="route('book.edit', book.uuid)" class="mr-1">
                                    <img src='../../Shared/Icons/edit.svg' alt="edit" class="custom-icon" />
                                </a>

                                <!-- show info modal -->
                                <img src='../../Shared/Icons/info.svg' class="custom-icon" alt="information" @click="infoModal" />  
                            </div>

                            <!-- CHECKOUT -->
                            <div v-if="!loggedIn" class="text-danger text-left">
                                <a :href="`/login?redirect=/books/${book.uuid}`">Login/Register</a> to checkout this Book  
                            </div>

                            <!-- <div v-else class="text-danger text-left mt-2"> -->
                                <!-- <div v-if="this.isCheckedOut">
                                    <div v-if="this.isWithUser">
                                        This is checked out by you
                                    </div>
                                    <div v-else>
                                        This is still checked out by another user, however, you can still checkout the book
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="this.lastWithUser">
                                        This was last checked out by you! pass this book to someone else to read.
                                    </div>
                                    <div v-else>
                                    </div>
                                </div> -->
                            <!-- </div> -->

                        </div>

                    </div>
                </div>
            </div>

            <!-- INFO MODAL -->
            <div id="infoModal" class="modal fade" role="dialog">
                <Modal
                    modalTitle="Info"
                    :modalDescription="modalDescription"
                    :modalWarning="modalWarning"
                />
            </div>

            <!-- BOOK CHECKOUT MODAL -->
            <div id="checkoutModal" class="modal fade" role="dialog">
                <Modal
                    :checkoutSuccess="checkoutSuccess"
                    :checkoutFailure="checkoutFailure"
                    :modalTitle="modalTitle"
                    modalDescription="This is the reference number found next to the QR code or hand-written in the book. This confirms that you are in possession of the book."
                    :hasFormFields="true"
                    @child-click="checkout"
                />
            </div>

            <!-- BOOK RETURN MODAL -->
            <div id="returnModal" class="modal fade" role="dialog">
                <Modal
                    :checkoutSuccess="checkoutSuccess"
                    :checkoutFailure="checkoutFailure"
                    modalTitle="Return Book"
                    modalDescription="Are you sure you want to return the book?"
                    :hasFormFields="true"
                    @child-click="returnbook"
                />
            </div>
            
            <!-- BOOK CHECKOUTS -->
            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100">
                        <Checkouts :bookCheckouts="book.book_checkout" :book-id="book.id" :modalWarning="modalWarning" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-5 bg-light border rounded-3">
                    <h2>Books Journey</h2>
                        <p>Google map with pins from checkout card here</p>
                       <!-- <GoogleMap
                           api-key="AIzaSyCYhCxQiuTzX8x1FtUJX9ngVSkzaavqY3Q"
                           style="width: 100%; height: 500px"
                           :center="center"
                           :zoom="zoom"
                       >
                           <MarkerCluster>
                               <Marker v-for="(location, i) in locations" :options="{ position: location }" :key="i" />
                           </MarkerCluster>
                       </GoogleMap> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- BOOK COMMENTS -->
        <div class="row book-item__comments">
            <div class="col-md-12 pb-5">
                <h5>Comments:</h5>
                <Comments :comments="comments" :book-uuid="book.uuid" :loggedIn="loggedIn" />
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
import Modal from '../../Shared/Modal'
import axios from 'axios';


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
        Modal,
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
        isWithUser: Object,
        isOwner: Boolean,
        userHasRead: Boolean,
        distanceTravelled: Number,
        loggedIn: Boolean,
        value: String, //check if used
        size: String, //check if used


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

    data() {
        return {
            referenceNumber: '',
            checkoutSuccess: '',
            checkoutFailure: '',
            modalTitle: 'Enter Reference',
            modalDescription: '',
        };
    },

    computed: {
        modalWarning() {
            if (this.isCheckedOut) {
                if (this.isWithUser) {
                    return "This is checked out by you";
                } else {
                    return "This is still checked out by another user, however, you can still checkout the book";
                }
            } else if (this.userHasRead) {
                return "You have previously read this book.";
            } else {
                return "This book is available to check out";
            }
        }
    },

    methods: {
        returnbook() {
            // this.$inertia.post(this.route('book.return', this.book.uuid))
            axios.post(route('book.return', this.book.uuid))
            .then(res => {
                if (res.data.success) {
                    this.checkoutSuccess = res.data.data;
                    this.checkoutFailure = '';
                    this.modalTitle = 'Congratulations!';
                    this.modalDescription = 'You are the current owner of this book. Once you have read the book, click return book and pass it to someone else to read!';                        
                } else {
                    this.checkoutFailure = res.data.data;
                    this.checkoutSuccess = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        },

        infoModal() {
          var infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
          infoModal.show();
        },

        checkoutModal() {
          var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
          checkoutModal.show();
        },

        returnModal() {
          var returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
          returnModal.show();
        },

        checkout(referenceNumber) {
            axios.post(route('book.checkout', this.book.uuid), {
                reference: referenceNumber,
            })
            .then(res => {
                if (res.data.success) {
                    this.checkoutSuccess = res.data.data;
                    this.checkoutFailure = '';
                    this.modalTitle = 'Congratulations!';
                    this.modalDescription = 'You are the current owner of this book. Once you have read the book, click return book and pass it to someone else to read!';                        
                } else {
                    this.checkoutFailure = res.data.data;
                    this.checkoutSuccess = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        },
    },
}
</script>
<style>
    .custom-icon {
        cursor: pointer; border: 1px solid #000; border-radius: 10px; padding: 7px; width:50px
    }
</style>