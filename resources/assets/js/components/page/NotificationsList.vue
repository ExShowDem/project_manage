<template>
    <li v-on:click="hideNotifications" class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
           data-close-others="true">
            <i class="icon-bell"></i>
            <span v-show="items.unread_count" class="badge badge-default"> {{ items.unread_count }} </span>
        </a>
        <ul class="dropdown-menu">
            <li class="external">
                <h3>
                    {{ items.unread_count }} thông báo chưa đọc
                </h3>
            </li>
            <li>
                <ul class="dropdown-menu-list scroller" style="height: 550px;" data-handle-color="#637283">
                    <li v-for="(item, key) in items.data" :key="key" :class="item.is_read ? 'color-read' : ''">
                        <a :href="route('admin.projects.notifications.read', {projectId: currentProjectId, id: item.id})">
                            <span  class="namuser">
                                <img style="border-radius: 25px !important;" width="50" height="50" :src="item.fromUser.image"/>
                            </span>
                            <span style="color:red;" class="namuser">
                                {{ item.fromUser.name }}
                            </span>

                            <span class="time">{{ getTimeFromNow(item.created_at) }}</span>
                            <span class="details">
                                {{ item.content }}
                            </span>

                        </a>
                    </li>

                </ul>
            </li>
        </ul>
    </li>
</template>

<script>
import moment from 'moment';

  export default {
    name: "notifications-list",
    data() {
      return {
        items: {
          data: [],
          meta: {},
          unread_count: 0,
        }
      }
    },
    created() {
        this.getItems();
    },
    methods: {

      getItems() {

        let params = {
          'page': this.items.meta.current_page,
          'search_option': this.searchOption,
          'per_page': this.items.meta.per_page,
          'project_id': this.currentProjectId
        };
        axios.get(route('api.notifications.index'), {
          params: params
        })
          .then((res) => {
            this.items = res.data;
          });
      },
      getTimeFromNow(time) {
        return moment(time).fromNow();
      },
        hideNotifications(event) {
          console.log(this.items.unread_count);
            $('.badge-default').remove();
            let params = {
                'project_id': this.currentProjectId
            };
            axios.get(route('api.notifications.update'), {
                params: params
            })
        }
    }
  }
</script>

<style scoped>

</style>