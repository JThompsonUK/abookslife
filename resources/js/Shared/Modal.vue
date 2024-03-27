<template>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ modalTitle }}</h5>
                <div type="button" data-bs-dismiss="modal" aria-label="Close">
                    <img class="book-item__right--icon" src='../Shared/Icons/close.svg' alt="close" />
                </div>
            </div>
            <div class="modal-body py-2">

                <div v-if="modalWarning" class="text-danger">
                    <h6 class="py-2">
                        {{ modalWarning }}
                        <hr>
                    </h6>
                </div>

                <span v-if="checkoutSuccess">
                    {{ checkoutSuccess }}
                </span>

                <span v-if="checkoutFailure">
                    <p class="text-danger">
                        {{ checkoutFailure }}
                    </p>
                </span>

                <!-- INFO MODAL -->
                <div v-if="modalTitle == 'Info'">
                    <h6 class="mb-2">
                        Key:
                    </h6>
                    <div class="row">

                    <div class="col-md-1 mb-3">
                        <img class="book-listing__info--icon mr-2" src='../Shared/Icons/bookmarkStar.svg' alt="You are this books owner"/>
                    </div>
                    <div class="col-md-11 mb-3">
                        You are the owner of this book and have the ability to edit it.
                    </div>
                    <div class="col-md-1 mb-3">
                        <img class="book-listing__info--icon" src='../Shared/Icons/bookmarkHeart.svg' alt="You have read this book"/>
                    </div>
                    <div class="col-md-11 mb-3">
                        You have read this book, but someone else was the original owner.
                    </div>
                    <div class="col-md-1 mb-3">
                        <img class="book-listing__info--icon" src='../Shared/Icons/qr.svg' alt="Add QR code to your book"/>
                    </div>
                    <div class="col-md-11 mb-3">
                        Print off the QR code and stick it in your book, so people can easily access this page. If you aren&#39;t able to print the QR code, simply write the reference in your book so other users can find this page.
                    </div>
                    <div class="col-md-1 mb-3">
                        <img class="book-listing__info--icon" src='../Shared/Icons/checkout.svg' alt="Checkout this book"/>
                    </div>
                    <div class="col-md-11 mb-3">
                        If you checkout this book, it will update the checkout card with the date and location. Much like a library card, you can keep a record of everyone who has read your book.
                    </div>
                    <div class="col-md-1 mb-3">
                        <img class="book-listing__info--icon" src='../Shared/Icons/checkoutReturn.svg' alt="Checkin this book"/>
                    </div>
                    <div class="col-md-11 mb-3">
                        Once you have finished reading the book, check it back in and hand it to someone else to read.
                    </div>
                </div>

                </div>

                <div v-if="!checkoutSuccess" v-html="modalDescription"></div>

                <div v-if="refRequired && !checkoutSuccess && hasFormFields && modalTitle != 'Return Book'" class="mt-2">
                    <input type="text" class="form-control" v-model="referenceNumber" id="referenceNumber" placeholder="Reference">
                </div>
            </div>
            <div class="modal-footer">
                <button v-if="!checkoutSuccess" type="button" class="btn btn-secondary m-3" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button v-if="!checkoutSuccess && hasFormFields" type="button" class="btn btn-primary mr-3" @click="handleClick">Submit</button>
                <button v-if="checkoutSuccess" type="button" class="btn btn-primary m-3" onclick="location.reload();">Go back to your book!</button>

            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        modalTitle: String,
        modalDescription: String,
        modalWarning: String,
        checkoutSuccess: String,
        checkoutFailure: String,
        hasFormFields: Boolean,
        refRequired: Boolean,
    },

    data() {
        return {
            referenceNumber: '',
        };
    },

    methods: {
        handleClick() {
            // reference isnt required for users that have already checked out the book
            if ( !this.refRequired ) {
                this.referenceNumber = 'na';
            }
            this.$emit('child-click', this.referenceNumber);
        },
    }
}
</script>
