<template>
    <div>
        <div class="card shadow-sm rounded-0 p-3 mt-3">
            <form @submit.prevent="store">

                <!-- Toggleable Comment Section -->
                <div class="mb-3">
                    <div
                        class="d-flex justify-content-between align-items-center mb-2"
                        @click="toggleCommentSection"
                        style="cursor: pointer"
                    >
                        <span>Add new comment</span>
                        <div class="border rounded-circle d-flex justify-content-center" style="width: 35px; height: 35px;">
                            <span v-html="showCommentSection ? '&#8722;' : '&#43;'"></span>
                        </div>

                    </div>

                    <!-- Comment Section -->
                    <div v-if="showCommentSection" class="d-flex justify-content-between align-items-center">
                        <img
                            src="https://via.placeholder.com/30"
                            width="30"
                            class="user-img rounded-circle mr-2"
                        />
                        <textarea
                            v-model="form.comment"
                            type="text"
                            name="comment"
                            class="form-control"
                            id="comment"
                            required
                        ></textarea>
                        <button type="submit" class="btn btn-sm btn-info">Submit</button>
                    </div>
                </div>
            </form>
        </div>



        <div v-for="comment in comments" :key="comment.id" class="card bg-light shadow-sm rounded-0 p-3 mt-3">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <img :src="comment.userImage || 'https://via.placeholder.com/30'" width="30" class="user-img rounded-circle mr-2">
                    <span><small class="font-weight-bold text-primary">{{ comment.user }}</small></span>
                </div>

                <small>{{ comment.created }}</small>
            </div>
            <div class="px-4"><small class="font-weight-bold">
                <template v-if="comment.id !== editingCommentId">
                    <span style="padding: 0 0.75rem;">{{comment.comment }}</span>
                </template>
                <template v-else>
                    <input v-model="form.editedComment" type="text" class="form-control my-2" style="margin: 0 0.75rem;"/>
                </template>
            </small></div>
            <div v-if="comment.isByActiveUser" class="action d-flex justify-content-between mt-2 align-items-center">
                <div class="reply px-4">
            <span style="padding: 0 0.75rem;">


                <template v-if="comment.id !== editingCommentId">
                        <button @click="editComment(comment)" class="btn btn-sm btn-info mr-1" >Edit</button>
                    </template>
                    <template v-else>
                        <button @click="saveComment(comment)" class="btn btn-sm btn-info mr-1" >Save</button>
                        <button @click="cancelEdit" class="btn btn-sm btn-info mr-1">Cancel</button>
                    </template>
                    <button @click="deleteComment(comment)" class="btn btn-sm btn-info" >Delete</button>




            </span>
                </div>
                <div class="icons align-items-center">
                    <i class="fa fa-check-circle-o check-icon text-primary"></i>

                </div>
            </div>
        </div>






    </div>
</template>
<style>
.card {
    border: 1px solid rgba(0,0,0,.125);
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
        comments: Object,
        bookId: Number
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: 'post',
                comment: null,
                editedComment: '',
            }),
            editingCommentId: null,
            showCommentSection: false,
        }
    },
    methods: {
        store() {
            this.form.post(this.route('book.comment', this.bookId))
        },

        // editComment(commentId) {
        //     // this.form.post(this.route('book.comment.edit', this.bookId))
        // },
        // deleteComment(commentId) {
        //     this.form.delete(this.route('book.comment.delete', this.bookId, commentId))
        // },
        editComment(comment) {
            // Set the comment for editing
            this.editingCommentId = comment.id;
            this.form.editedComment = comment.comment;
        },

        saveComment(comment) {
            // save the edited comment
            this.form.put(this.route('book.comment.update', { book: this.bookId, bookComments: comment.id }), {
                editedComment: this.form.editedComment,
            });

            // Reset editing state
            this.editingCommentId = null;
        },
        cancelEdit() {
            this.editingCommentId = null;
        },
        deleteComment(comment) {
            this.form.post(this.route('book.comment.delete', { book: this.bookId, bookComments: comment.id }));
        },
        toggleCommentSection() {
            this.showCommentSection = !this.showCommentSection;
        },
    },
}
</script>
