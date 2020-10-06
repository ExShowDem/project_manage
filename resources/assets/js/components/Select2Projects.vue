<template>
    <div>
        <select2 :settings="projects" :selected="selectedProject" @select="selectProject"/>
    </div>
</template>

<script>
    export default {
        name: 'Select2Projects',
        data() {
            return {
                item: {},
                projects: this.getSelect2Settings({
                    url: route('api.select2.projects'),
                    field_name: 'name',
                    placeholder: 'Chọn dự án...',
                    term_name: 'search_option[keyword]',
                }),
                selectedProject: {},
            };
        },
        // Not done: bị load lại trang.
        // mounted() {
        //     this.selectedProject = {
        //         'id': this.currentProjectId,
        //         'text': this.currentProjectName,
        //     };
        // },
        methods: {
            selectProject(selected) 
            {
                let path      = window.location.href.replace(Ziggy.baseUrl, '');
                let pathArray = path.split('/');
                pathArray[1]  = selected.id;
                let newPath   = Ziggy.baseUrl + pathArray.join('/');

                window.location.href = newPath;
            }
        }
    };
</script>
