<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div class="card card-default">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                       API Consumption Misses
                    </span>
                </div>
            </div>

            <div class="card-body">
                <!-- Current Clients -->
                <p class="mb-0" v-if="misses.length === 0">
                    You have no Missed Fico Search Result.
                </p>

                <table class="table table-borderless mb-0" v-if="misses.length > 0">
                    <thead>
                        <tr>
                            <th>Search Parameter</th>
                            <th>Date of Call</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="miss in misses">
                            <!-- ID -->
                            <td style="vertical-align: middle;">
                                {{ miss.fico_id }}
                            </td>

                            <!-- Name -->
                            <td style="vertical-align: middle;">
                                {{ miss.created_at }}
                            </td>

                            <!-- Secret -->
                            <td style="vertical-align: middle;">
                                {{ miss.status }} 
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
                misses: [],
            };
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
            console.log(this.id)
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getMisses();
            },

            /**
             * Get all of the OAuth Misses for the user.
             */
            getMisses() {
                axios.get('/administrator/misses/'+this.id)
                        .then(response => {
                            this.misses = response.data;
                        });
            },
        }
    }
</script>
