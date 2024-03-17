<template>
    <div style="padding: 10px; background: beige; border-radius: 5px;">
        <div class="py-2 d-flex justify-content-center">
            <h5>Checkout Card</h5>
        </div>
        <table class="table" style="width: 100%;">
            <thead>
            <tr>
                <th scope="col">Checkout date</th>
                <th scope="col">Returned date</th>
                <th scope="col">Issued to</th>
                <th scope="col">Location</th>
            </tr>
            </thead>
            <tbody v-for="checkout in bookCheckouts" :key="checkout.id" style="border-width: 1px;">
            <tr>
                <td>{{ checkout.checkout_date }}</td>
                <td>{{ checkout.checkin_date }}</td>
                <td>{{ checkout.user_id }}</td>
                <td>{{ checkout.city }}</td>
            </tr>
            </tbody>
        </table>


    </div>
</template>
<style>
.table td {
    border-top: 1px solid black;
}

.table thead th {
    border-top: 1px solid black;
    border-bottom: 0.25rem solid black;
}

</style>
<script>
import Icon from '../../Shared/Forms/Icon.vue'
import TextInput from '../../Shared/Forms/TextInput'

export default {
    components: {
        Icon,
        TextInput,
    },

    props: {
        bookCheckouts: Object,
        bookId: Number
    },

    methods: {
        store() {
            // this.form.post(this.route('book.comment', this.bookId))
        },
    },
}
</script>
