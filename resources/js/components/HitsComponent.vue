<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>

    <div>
        <div class="col-xs-12 table-responsive">
        </div>
        
        <div class="card card-default">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                       API Consumption Hits
                    </span>
                </div>
            </div>

            <div class="card-body">
                <!-- Current Clients -->
                <p class="mb-0" v-if="hits.length === 0">
                    You have no Hits Fico Search Result.
                </p>

                <table class="table table-borderless mb-0" v-if="hits.length > 0">
                    <thead>
                        <tr>
                            <th>Search Parameter</th>
                            <th>Date of Call</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="hit in hits">
                            <!-- ID -->
                            <td style="vertical-align: middle;">
                                {{ hit.fico_id }}
                            </td>

                            <!-- Name -->
                            <td style="vertical-align: middle;">
                                {{ hit.created_at }}
                            </td>

                            <!-- Secret -->
                            <td style="vertical-align: middle;">
                                {{ hit.status }} 
                            </td>
                            <!-- Edit Button -->
                            <td style="vertical-align: middle;">
                                <a class="btn btn-info" href="#">
                                    Retry
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

       
    </div>
    
</template>

<script>

    export default {
        props: ['id'],
        /*
         * The component's data.
         */
        data() {
            return {
                hits: [],
            };
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getHits();
            },

            /**
             * Get all of the OAuth hits for the user.
             */
            getHits() {
                axios.get('/administrator/hits/'+this.id)
                        .then(response => {
                            this.hits = response.data;
                        });
            },
        }
    }
</script>
