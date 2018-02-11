<template>
    <div id="comments">
        <h2>Comments</h2>

        <div class="comment-list">
            <div class="comment" v-for="comment in comments">
                <div class="top-row">
                    <span class="author">{{ comment.author.name }}</span>
                    <span class="remove" @click.prevent="removeComment(comment)">Remove comment</span>
                </div>
                <div class="date-row">
                    <span class="posted">2018-02-10 22:23:43 +01:00</span>
                </div>
                <div class="comment-text">{{ comment.comment }}</div>
            </div>
        </div>

        <div id="add-comment-form">
            <div class="form-field">
                <label for="new-comment">
                    New comment<br>
                    <textarea v-model="newComment" rows="3" id="new-comment"></textarea>
                </label>
                <div>
                    <button class="btn primary" @click.prevent="addComment(newComment)">Add comment</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const utils = require('../utils');

  export default {
    props: ["liquid"],
    data() {
      return {
        comments: [],
        newComment: null
      };
    },
    mounted() {
      this.loadComments();
    },
    methods: {
      loadComments() {
        axios.get(`/ajax/liquids/${this.liquid.id}/comments`)
        .then(res => {
          if (res.status === 200) {
            this.comments = res.data;
          }
        })
        .catch(err => {
          toastr.error("Failed to fetch comments");
        });
      },
      addComment(comment) {
        axios.post(`/ajax/liquids/${this.liquid.id}/comments`, {
          comment: comment
        })
        .then(res => {
          if (res.status === 200) {
            toastr.success("Comment added");
            this.loadComments();
            this.newComment = null;
          }
        })
        .catch(err => {
          let res = err.response;

          if (res && res.status === 400) {
            toastr.error(utils.extractErrors(res.data).join("<br>"));
          } else {
            toastr.error("Failed to save comment");
          }
        });
      },
      removeComment(comment) {
        axios.delete(`/ajax/liquids/${this.liquid.id}/comments/${comment.id}`)
        .then(res => {
          if (res.status === 204) {
            toastr.success("Comment removed");
            this.loadComments();
          }
        })
        .catch(err => {
          toastr.error("Failed to remove comment");
        });
      }
    }
  }
</script>