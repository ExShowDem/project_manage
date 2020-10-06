<template>
  <div>
  <div class="row">
    <div class="sc_property_search_content">
      <div class="portlet-input input-inline input-small">
        <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_from" :config="datepickerOptions"  placeholder="Từ ngày" class="select_image"  />
        </div>
      </div>

      <div class="portlet-input input-inline input-small">
        <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_to" :config="datepickerOptions"  placeholder="Đến ngày" class="select_image"  />
        </div>
      </div>
      <div v-if="" class="portlet-input input-inline input-small project-search">
        <select2 class="select_image" :settings="select2ProjectOptions" v-model="searchOption.projects" @select="getItems()" />
      </div>
      <div class="portlet-input input-inline input-small">
        <select2 class="select_image" :settings="select2RolesOptions" v-model="searchOption.roles" @select="getItems()" />
      </div>

      <div class="portlet-input input-inline input-small">
        <select2 class="select_image" :settings="select2UsersOptions" v-model="searchOption.users" @select="getItems()" />
      </div>

    </div>
  </div>


  <div class="row">
    <div class="col-md-3">
      <aside id="bestdeals_widget_flickr-1" class="widget_number_5 widget widget_flickr"><div class="height-dashboard widget_number_5 widget_bg">
        <h5 class="widget_title_">Quản lý công việc</h5>
        <div class="flickr_images">
          <span class="sc_icon sc_icon_shape_round alignleft icon5e">{{items.total_task}}</span>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color:#ff0000;"></div>


            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.tasks.index', {projectId: items.project_id})+'?view_style=1'"><span>{{items.needhandle_task}}</span> công việc cần xử lý</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.tasks.index', {projectId: this.currentProjectId})+'?view_style=1'"><span>{{items.needhandle_task}}</span> công việc cần xử lý</a></div>
            <div class="tag-label" v-else><span>{{items.needhandle_task}}</span> công việc cần xử lý</div>


          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #6BBF19;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.tasks.index', {projectId: items.project_id})+'?view_style=2'"><span>{{items.processed_task}}</span> công việc đã xử lý</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.tasks.index', {projectId: this.currentProjectId})+'?view_style=2'"><span>{{items.processed_task}}</span> công việc đã xử lý</a></div>

            <div class="tag-label" v-else><span>{{items.processed_task}}</span> công việc đã xử lý</div>

          </div>
         <!-- <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #875692;"></div>


            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.tasks.index', {projectId: items.project_id})"><span>{{items.returned_task}}</span> đã từ chối</a></div>
            <div class="tag-label" v-else><span>{{items.returned_task}}</span> đã từ chối</div>


          </div>-->
        </div>

      </div></aside>
    </div>

    <div class="col-md-3">
      <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr"><div class=" height-dashboard widget_number_5 widget_bg">
        <h5 class="widget_title_">Yêu cầu vật tư dự án</h5>
        <div class="flickr_images">
          <span class="sc_icon sc_icon_shape_round alignleft icon5e">{{items.total_requestsupplies}}</span>

          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color:#f0ca13;"></div>


            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: items.project_id,target: 'project'})+'?status=3'"><span>{{items.processed_requestsupplies}}</span> đã duyệt</a></div>
            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: this.currentProjectId,target: 'project'})+'?status=3'"><span>{{items.processed_requestsupplies}}</span> đã duyệt</a></div>
            <div class="tag-label" v-else><span>{{items.processed_requestsupplies}}</span> đã duyệt</div>

          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #009999;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: items.project_id,target: 'project'})+'?status=2'"><span>{{items.needhandle_requestsupplies}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: this.currentProjectId,target: 'project'})+'?status=2'"><span>{{items.needhandle_requestsupplies}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else><span>{{items.needhandle_requestsupplies}}</span> chuyển tiếp</div>

          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #875692;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: items.project_id,target: 'project'})+'?status=4'"><span>{{items.returned_requestsupplies}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.requests.supplies', {projectId: this.currentProjectId,target: 'project'})+'?status=4'"><span>{{items.returned_requestsupplies}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else><span>{{items.returned_requestsupplies}}</span> đã từ chối</div>


          </div>

        </div>

      </div></aside>
    </div>


    <div class="col-md-3">
      <aside id="bestdeals_widget_flickr-3" class="widget_number_5 widget widget_flickr"><div class="height-dashboard widget_number_5 widget_bg">
        <h5 class="widget_title_">Hóa đơn mua vật tư</h5>
        <div class="flickr_images">
          <span class="sc_icon sc_icon_shape_round alignleft icon5e">{{items.total_invoice}}</span>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color:#f0f013;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: items.project_id})+'?status=3'"><span>{{items.processed_invoice}}</span> đã duyệt</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: this.currentProjectId})+'?status=3'"><span>{{items.processed_invoice}}</span> đã duyệt</a></div>

            <div class="tag-label" v-else><span>{{items.processed_invoice}}</span> đã duyệt</div>

          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #009999;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: items.project_id})+'?status=2'"><span>{{items.needhandle_invoice}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: this.currentProjectId})+'?status=2'"><span>{{items.needhandle_invoice}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else><span>{{items.needhandle_invoice}}</span> chuyển tiếp</div>

          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #875692;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: items.project_id})+'?status=4'"><span>{{items.returned_invoice}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.invoices.index', {projectId: this.currentProjectId})+'?status=4'"><span>{{items.returned_invoice}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else><span>{{items.returned_invoice}}</span> đã từ chối</div>


          </div>

        </div>

      </div></aside>
    </div>

    <div class="col-md-3">
      <aside id="bestdeals_widget_flickr-4" class="widget_number_5 widget widget_flickr"><div class=" height-dashboard widget_number_5 widget_bg">
        <h5 class="widget_title_">Mua thiết bị</h5>
        <div class="flickr_images">
          <span class="sc_icon sc_icon_shape_round alignleft icon5e">{{items.total_devicepurchase}}</span>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color:#f0f013;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: items.project_id})+'?status=3'"><span>{{items.processed_devicepurchase}}</span> đã duyệt</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: this.currentProjectId})+'?status=3'"><span>{{items.processed_devicepurchase}}</span> đã duyệt</a></div>

            <div class="tag-label" v-else><span>{{items.processed_devicepurchase}}</span> đã duyệt</div>


          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #009999;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: items.project_id})+'?status=2'"><span>{{items.needhandle_devicepurchase}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: this.currentProjectId})+'?status=2'"><span>{{items.needhandle_devicepurchase}}</span> chuyển tiếp</a></div>

            <div class="tag-label" v-else><span>{{items.needhandle_devicepurchase}}</span> chuyển tiếp</div>

          </div>
          <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
            <div class="tag-color" style="background-color: #875692;"></div>

            <div class="tag-label" v-if="typeof items.project_id !== 'undefined'"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: items.project_id})+'?status=4'"><span>{{items.returned_devicepurchase}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else-if="typeof this.currentProjectId !== 'undefined' && this.currentProjectId != ''"><a target='_blank' :href="route('admin.projects.devices.purchase.index', {projectId: this.currentProjectId})+'?status=4'"><span>{{items.returned_devicepurchase}}</span> đã từ chối</a></div>

            <div class="tag-label" v-else><span>{{items.returned_devicepurchase}}</span> đã từ chối</div>

          </div>

        </div>

      </div></aside>
    </div>

  </div>
  </div>
</template>


<script>
    export default {
        name: 'List',
        data() {
            return {
                items: {
                    data: [],
                    meta: {},
                },
                role_action: {
                    can_approve: false,
                    can_create: false,
                    can_delete: false,
                    can_update: false,
                    is_admin: false,
                },
                searchOption: {}
            };
        },
        created() {
            this.getItems();

            this.select2ProjectOptions = this.getSelect2Settings({
                url: route('api.select2.projects'),
                field_name: 'name',
                placeholder: 'Các dự án...',
            });

            this.select2RolesOptions = this.getSelect2Settings({
                url: route('api.select2.roles'),
                field_name: 'name',
                placeholder: 'Các khối phòng ban...',
            });
            this.select2UsersOptions = this.getSelect2Settings({
                url: route('api.select2.users'),
                field_name: 'name',
                placeholder: 'Thành viên công ty...',
            });
        },
        methods: {
            getItems() {
                let params = {
                    'search_option': this.searchOption,
                    'project_id': this.currentProjectId
                };
                axios.get(route('api.report.index'), {
                    params: params
                })
                    .then((res) => {
                    console.log(res);
                        this.items = res.data;
                        this.role_action = res.role_action;
                    });
            },
            deleteItem(item) {
                return this.confirmAndDeleteItem(item, 'api.tasks.destroy')
            },
        }
    };
</script>

<style scoped>

</style>
