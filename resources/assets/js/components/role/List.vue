<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
            <i class="icon-magnifier" @click="searchKeyword()" />
            <input
              v-model="searchText"
              type="text"
              class="form-control"
              placeholder="Tìm kiếm..."
              @keyup.enter="searchKeyword()"
            >
          </div>
        </div>
      </div>
      <div class="actions">
        <a
          class="btn btn-success"
          :href="currentProjectId ? route('admin.projects.roles.create', currentProjectId) : route('admin.roles.create')"
        >
          <i class="fa fa-plus" /> Thêm Vai trò/Chức vụ
        </a>
      </div>
    </div>

    <v-jstree :data="tree" :draggable="true" @item-drop="onDrop" ref="tree">
       <template slot-scope="_">
         <div class="role_node" v-bind:id="_.model.id">
            {{_.model.text}}
            <a v-if="role_action.can_update && _.model.id !== 0"
              :href="currentProjectId ? route('admin.projects.roles.edit', {projectId: currentProjectId, role: _.model.id}) : route('admin.roles.edit', _.model.id)"
              class="btn btn-xs blue btn-outline"
            >
              <i class="fa fa-pencil" />
            </a>
            <button v-if="role_action.can_delete && _.model.id !== 0"
              type="button"
              class="btn btn-xs red btn-outline"
              @click="deleteNode(_.vm, _.model, $event)"
            >
              <i class="fa fa-trash" />
            </button>
         </div>
       </template>
    </v-jstree>

    <button type="button" @click="sync()">Save All</button>

  </div>
</template>

<script>
import VJstree from 'vue-jstree';

export default {
  name: 'List',
  components: {
    VJstree,
  },
  data() {
    return {
      items: {
        data: [],
        meta: {}
      },
      role_action: {
        can_approve: false,
        can_create: false,
        can_delete: false,
        can_update: false,
        is_admin: false,
      },
      searchText: '',
      tree: [
        {
          id: 0,
          text: "Công Ty",
          value: "root",
          icon: "",
          opened: true,
          selected: false,
          disabled: false,
          loading: false,
          children: [],
          dragDisabled: true
        }
      ],
      tally: []
    };
  },
  created() {
    this.getItems();
  },
  methods: {
    searchKeyword ()
    {
      var text   = this.searchText.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
      const patt = new RegExp(text);

      this.$refs.tree.handleRecursionNodeChilds(this.$refs.tree, function (node) {

        if (text !== '' && node.model !== undefined) 
        {
          const str = node.model.text.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

          if (patt.test(str)) 
          {
            node.$el.querySelector('.tree-anchor').style.backgroundColor = 'yellow';
          } 
          else 
          {
            node.$el.querySelector('.tree-anchor').style.backgroundColor = '';
          }
        } 
        else 
        {
          node.$el.querySelector('.tree-anchor').style.backgroundColor = '';
        }
      });
    },
    sync () 
    {
      var roles = $('div.role_node#0').closest('li').find('li');

      this.tally = [];
      this.getNode(roles);

      axios.post(route('api.role_tree.rearrange'), this.tally)
        .then((res) => {
          var currentProjectId = $('input[name=current_project_id]').val();
          var redirUrl         = currentProjectId ? route('admin.projects.roles.index', currentProjectId) : route('admin.roles.index');
          window.location.href = redirUrl;
        });
    },  
    getNode(node)
    {
      var that = this;

      if (node.length > 0)
      {
        if (node.length > 1)
        {
          node.each(function( index, value ) {
            return that.getNode($(this));
          });
        }
        else
        {
          this.tally.push({role_id: node.find('div.role_node').attr('id'), parent_id: node.parent().closest('li').find('div.role_node').attr('id')});

          return;
        }
      }
    },
    onDrop (node, item, draggedItem, e) 
    {

    },  
    deleteNode: function (node ,item, e) 
    {
      e.stopPropagation();
      var index = node.parentItem.indexOf(item);
      var res   = node.parentItem.splice(index, 1);
    },
    getItems() {
      let params = {
        'page'     : this.items.meta.current_page,
        'per_page' : this.items.meta.per_page,
      };
      axios.get(route('api.roles.index'), {
        params: params
      })
        .then((res) => {
          this.items            = res.data;
          this.tree[0].children = res.data.data;
          this.role_action      = res.role_action;
        });
    },
  }
};


</script>

<style scoped>

</style>
