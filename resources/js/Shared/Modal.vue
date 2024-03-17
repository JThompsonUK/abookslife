<template>

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ modalTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span v-if="checkoutSuccess">
                    {{ checkoutSuccess }}
                </span>

                <span v-if="checkoutFailure">
                    {{ checkoutFailure }}
                </span>

                <div v-html="modalDescription"></div>

                <div v-if="!checkoutSuccess && hasFormFields">
                    <label for="referenceNumber">Reference:</label>
                    <input type="text" v-model="referenceNumber" id="referenceNumber">
                </div>
            </div>
            <div class="modal-footer">
                <button v-if="!checkoutSuccess" type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button v-if="!checkoutSuccess && hasFormFields" type="button" class="btn btn-primary" @click="handleClick">Submit</button>
                <button v-if="checkoutSuccess" type="button" class="btn btn-primary" onclick="location.reload();">Go back to your book!</button>

            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        modalTitle: String,
        modalDescription: String,
        checkoutSuccess: String,
        checkoutFailure: String,
        hasFormFields: Boolean
    },

    data() {
        return {
            referenceNumber: '',
        };
    },

    methods: {
        handleClick() {
            this.$emit('child-click', this.referenceNumber);
        },
    }
}
</script>
