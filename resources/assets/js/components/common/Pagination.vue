<template>
  <div class="row">
    <div v-show="pagination.total > 0" :class="width">
      <ul class="pagination pull-right">
        <li
          :class="{'disabled': pagination.current_page == 1}"
          @click.prevent="changePage(1)"
        >
          <a href="javascript:;">
            <i class="fa fa-angle-double-left" />
          </a>
        </li>
        <li
          :class="{'disabled': pagination.current_page == 1}"
          @click.prevent="changePage(pagination.current_page - 1)"
        >
          <a href="javascript:;">
            <i class="fa fa-angle-left" />
          </a>
        </li>
        <li
          v-show="rangeFirstPage !== 1"
          @click.prevent="changePage(1)"
        >
          <a href="javascript:;"> 1 </a>
        </li>
        <li
          v-show="rangeFirstPage === 3"
          @click.prevent="changePage(2)"
        >
          <a href="javascript:;"> 2 </a>
        </li>
        <li v-show="rangeFirstPage !== 1 && rangeFirstPage !== 2 && rangeFirstPage !== 3">
          <a href="javascript:;"> <i class="fa fa-ellipsis-h" /> </a>
        </li>
        <li
          v-for="(page, index) in pagesNumber"
          :key="index"
          :class="{'active': page == pagination.current_page}"
          @click.prevent="changePage(page)"
        >
          <a href="javascript:;"> {{ page }} </a>
        </li>
        <li v-show="rangeLastPage !== pagination.last_page && rangeLastPage !== (pagination.last_page - 1) && rangeLastPage !== (pagination.last_page - 2)">
          <a href="javascript:;"> <i class="fa fa-ellipsis-h" /> </a>
        </li>
        <li
          v-show="rangeLastPage === (pagination.last_page - 2)"
          @click.prevent="changePage(pagination.last_page - 1)"
        >
          <a href="javascript:;"> {{ pagination.last_page - 1 }} </a>
        </li>
        <li
          v-if="rangeLastPage !== pagination.last_page"
          @click.prevent="changePage(pagination.last_page)"
        >
          <a href="javascript:;"> {{ pagination.last_page }} </a>
        </li>
        <li
          :class="{'disabled': pagination.current_page == pagination.last_page}"
          @click.prevent="changePage(pagination.current_page + 1)"
        >
          <a href="javascript:;">
            <i class="fa fa-angle-right" />
          </a>
        </li>
        <li
          :class="{'disabled': pagination.current_page == pagination.last_page}"
          @click.prevent="changePage(pagination.last_page)"
        >
          <a href="javascript:;">
            <i class="fa fa-angle-double-right" />
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
const rangeMax = 3;
export default {
  name: 'VuePagination',
  props: {
    pagination: {
      type: Object,
      required: true
    },
    width: {
      default: 'col-md-12'
    }
  },
  data() {
    return {
      pageJump: null,
      per_page: null,
    };
  },
  computed: {
    rangeFirstPage() {
      if (this.pagination.current_page === 1) {
        return 1;
      }
      if (this.pagination.current_page === this.pagination.last_page) {
        if ((this.pagination.last_page - rangeMax) < 0) {
          return 1;
        } else {
          return this.pagination.last_page - rangeMax + 1;
        }
      }
      return (this.pagination.current_page - 1);
    },
    rangeLastPage() {
      return Math.min(this.rangeFirstPage + rangeMax - 1, this.pagination.last_page);
    },
    pagesNumber() {
      if (!this.pagination.last_page) {
        return [];
      }
      let pagesArray = [];
      for (let page = this.rangeFirstPage; page <= this.rangeLastPage; page++) {
        pagesArray.push(page);
      }

      return pagesArray;
    }
  },
  methods: {
    changePage(page, per_page) {
      this.pagination.current_page = page;
      this.pagination.per_page = per_page;
      this.$emit('paginate');
    },
  }
};
</script>
