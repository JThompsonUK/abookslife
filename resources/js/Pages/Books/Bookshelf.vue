<template>
    <div>
        <Head title="Bookshelf" />
        <div class="container">
            list items / bookshelf view
            <div class="row book-item">
                <div class="col-md-6" style="display: flex">
                    <div v-for="book in books" :key="book.id">
                        <!-- {{ book.book_title }} -->
                        <img src='/images/book-layout-spine.svg' style="height: 500px"/>
                    </div>
                </div>
                <div class="col-md-6">
                    Book details
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

export default {
    props: {
        books: Object,
    },

}
</script>
