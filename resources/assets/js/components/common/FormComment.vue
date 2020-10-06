<template>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <div class="col-md-12">
          <ul class="media-list">
            <li v-for="(comment, index) in comments" :key="index" class="media">
              <a class="pull-left" href="javascript:;">
                <img
                  class="todo-userpic"
                  src="/assets/admin/images/avatar3_small.jpg"
                  width="27px"
                  height="27px"
                > </a>
              <div class="media-body todo-comment">
                <p class="todo-comment-head">
                  <span class="todo-comment-username">{{ comment.user.name }}</span> &nbsp;
                  <span class="todo-comment-date">{{ comment.created_at }}</span>
                </p>
                <p class="todo-text-color" v-html="comment.content">
                  {{ comment.content }}
                </p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <div class="row">
          <a class="pull-left" href="javascript:;">
            <img
              class="todo-userpic"
              src="/assets/admin/images/avatar3_small.jpg"
              width="27px"
              height="27px"
            >
          </a>
        </div>
        <div class="row">
          <div class="media-body">
            <textarea
              v-model="content"
              class="form-control todo-taskbody-taskdesc"
              rows="4"
              placeholder="Nội dung bình luận"
            />
            <select id="autosuggest" @change="choseUser" size="5"></select>
            <input type="hidden" id="curr_username">
          </div>
        </div>
        <div class="row">
          <button type="button" class="pull-right btn btn-sm btn-circle green" @click="storeComment">
            Submit &nbsp;
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
  name: 'FormComment',
  props: {
    /**
     * type: 'plan',
     * id: xx
     */
    model: {
      type: Object,
      default: () => {
        return {};
      },
    },
    comments: {
      type: Array,
      default: () => [],
    }
  },
  data() {
    return {
      content: '',
      editor: null,
    };
  },
  mounted() {
    ClassicEditor
      .create( document.querySelector( "div.media-body textarea" ) )
      .then( editor => {
        this.editor = editor;
        editor.editing.view.document.on( 'keyup', this.getUserList );
      } )
      .catch( error => {
          console.error( error );
      } );

    $("#curr_username").val('');
    $("select#autosuggest").empty();
    $("select#autosuggest").hide();

    this.getUserList = _.debounce(this.getUserList, 500);
  },
  methods: {
    getUserList(e, data)
    {
      var selection = window.getSelection();
      var currPos   = selection.anchorOffset;
      var currInput = selection.focusNode.wholeText;

      if (currPos)
      {
        var currPart  = currInput.substring(0, currPos);
        var currWord  = currPart.substring(currPart.lastIndexOf(" ") + 1);

        if (currWord.charAt(0) === '@')
        {
          var username = currWord.replace("@", "");

          if (username)
          {
            $("#curr_username").val(username);
            $("select#autosuggest").empty();

            axios.get(route('api.autosuggest.users', username))
              .then((users) => {
                $(users).each(function(i) {
                  $("div.media-body select#autosuggest").append('<option style="min-width:200px;padding:10px;" value="'+ this.id +'">'+ this.name +'</option>');
                });

                $("select#autosuggest").show();
              });
          }
          else
          {
            $("#curr_username").val('');
            $("select#autosuggest").empty();
            $("select#autosuggest").hide();
          }
        }
        else
        {
          $("#curr_username").val('');
          $("select#autosuggest").empty();
          $("select#autosuggest").hide();
        }
      }
    },
    choseUser(event)
    {
      var value = event.target.value;
      var text  = $(event.target).find("option:selected").text();
      var url   = route('admin.projects.users.edit', {projectId:1, user:value});
      var link  = '<a href="'+ url +'">'+ text +'</a>';

      var commentText = $("div.ck-content").html();
      var toReplace   = $("#curr_username").val();
      var replaced    = commentText.replace('@' + toReplace, link);

      this.editor.setData(replaced);
      $("#curr_username").val('');
      $("select#autosuggest").empty();
      $("select#autosuggest").hide();
    },
    storeComment() {
      let comment = {
        'from_user': window.currentUser.id,
        'content': this.editor.getData(),
        ...this.model
      };
      axios.post(route('api.comment.store'), comment)
        .then((res) => {
          if (res.code === 2) {
            this.$emit('updateErrors', res.data.errors);

            return false;
          }

          this.comments.push(res);
          this.editor.setData('');
        });
    }
  }
};
</script>

<style scoped>
  select#autosuggest {
    margin-top: 10px;
  }
</style>
