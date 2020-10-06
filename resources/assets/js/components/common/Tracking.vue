<template>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light portlet-fit bordered">
        <div class="portlet-title">
          <div class="caption">
            <span class="caption-subject bold font-green uppercase">Lịch sử</span>
            <span class="caption-helper" />
          </div>
        </div>
        <div class="portlet-body">
          <div v-for="(tracking, key) in trackingItems" class="timeline">
            <div class="timeline-item">
              <div class="timeline-badge">
                <div style="width: 130px; height: 80px; background-color: #337ab7; padding-top: 14px; text-align: center">
                  <div style="font-size: x-large; color: white">
                    {{ tracking.time }}
                  </div>
                  <div style="color: white">
                    {{ tracking.date }}
                  </div>
                </div>
              </div>
              <div class="timeline-body" style="margin-left: 150px !important;">
                <div class="timeline-body-arrow" />
                <div class="timeline-body-head">
                  <div class="timeline-body-head-caption">
                    <span class="timeline-body-alerttitle font-green-haze">{{ tracking.process_name }}</span>
                  </div>
                </div>
                <div class="timeline-body-content">
                  <div class="font-grey-cascade margin-bottom-10">
                    Người thực hiện:
                    <!--<img src="{{ asset('assets/admin/images/avatar3_small.jpg')}}">-->
                    <label style="padding:3px;">{{ tracking.process_user_name }}</label>
                  </div>
                  <div v-if="tracking.to" class="font-grey-cascade margin-bottom-10"">
                    Người bên nhận:
                    <label v-for="(recipient) in tracking.to" style="padding:3px;">
                      {{ recipient.name }}
                    </label>
                  </div>
                  <div v-if="tracking.cc" class="font-grey-cascade margin-bottom-10"">
                    cc:
                    <label v-for="(cc) in tracking.cc" style="padding:3px;">
                      {{ cc.name }}
                    </label>
                  </div>
                  <div class="font-grey-cascade">
                    Trạng thái: <label class="label label-status" :class="tracking.process_status_class">{{ tracking.process_status.toUpperCase() }}</label>
                  </div>
                  <div>
                    <div v-if="trackingItems.length > 1">
                      <a v-if="key > 0 && trackingItems[key - 1].data_object" type="button" class="btn green" :href="urlTrackingDetail(trackingItems[key - 1])">
                        Dữ liệu trước
                      </a>
                      <a v-if="tracking.data_object" type="button" class="btn green" :href="urlTrackingDetail(tracking)">
                        Dữ liệu sau
                      </a>
                    </div>
                    <div v-else>
                      <a type="button" class="btn green" :href="urlTrackingDetail(tracking)">
                        Dữ liệu
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: Number,
    trackingRoute: String,
    trackingDetail: String,
  },
  data() {
    return {
      trackingItems: [],
    };
  },
  created() {
    this.getTrackingItems();
  },
  methods: {
    getTrackingItems() {
      if (this.id !== undefined && this.trackingRoute !== undefined) {
        axios.get(route(this.trackingRoute, this.id))
          .then((res) => {
            this.trackingItems = res.data;
          });
      }
    },
    urlTrackingDetail(log) {
      return window.location.href + '/' + log.id;
    }
  },
};
</script>
