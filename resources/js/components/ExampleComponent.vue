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
                       API Users 
                    </span>

                    <a class="action-link" tabindex="-1" @click="showCreateClientForm">
                        Create New User
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Current Clients -->
                <p class="mb-0" v-if="users.length === 0">
                    You have no registered Users.
                </p>

                <table class="table table-borderless mb-0" v-if="users.length > 0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Misses</th>
                            <th>Hits</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="user in users">
                            <!-- ID -->
                            <td style="vertical-align: middle;">
                                {{ user.email }}
                            </td>

                            <!-- Name -->
                            <td style="vertical-align: middle;">
                                {{ user.name }}
                            </td>

                            <!-- Secret -->
                            <td style="vertical-align: middle;">
                                <a v-bind:href="'/administrator/view/misses/'+user.id"> {{ user.misses }} </a>
                            </td>
                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                <a v-bind:href="'/administrator/view/hits/'+user.id">{{ user.hits }}</a>
                            </td>
                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                {{ user.activate }}
                            </td>
                            <!-- Edit Button -->
                            <td style="vertical-align: middle;">
                                <a class="btn btn-info" tabindex="-1" @click="edit(user)">
                                    Modify
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Client Modal -->
        <div class="modal fade" id="modal-create-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Create Client
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form role="form">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Name</label>

                                <div class="col-md-9">
                                    <input id="create-client-name" type="text" class="form-control"
                                                                @keyup.enter="store" v-model="createForm.name">

                                    <span class="form-text text-muted">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Redirect URL</label>

                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="redirect"
                                                    @keyup.enter="store" v-model="createForm.redirect">

                                    <span class="form-text text-muted">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Edit Client
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Client Form -->
                        <form role="form">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Name</label>

                                <div class="col-md-9">
                                    <input id="edit-client-name" type="text" class="form-control"
                                                                @keyup.enter="update" v-model="editForm.name">

                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Email</label>

                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="email"
                                                    @keyup.enter="update" v-model="editForm.email">

                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Activation</label>

                                <div class="col-md-9">
                                    <select name="activate" class="form-control" @keyup.enter="update" v-model="editForm.activate">
                                        <option value="active">Activate</option>
                                        <option value="inactive">Deactivate</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="update">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                users: [],
                hits: [],

                createForm: {
                    errors: [],
                    name: '',
                    email: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    email: '',
                    activate: ''
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
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
                this.getClients();

                $('#modal-create-client').on('shown.bs.modal', () => {
                    $('#create-client-name').focus();
                });

                $('#modal-edit-client').on('shown.bs.modal', () => {
                    $('#edit-client-name').focus();
                });
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/administrator/users')
                        .then(response => {
                            this.users = response.data;
                        });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
                $('#modal-create-client').modal('show');
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createForm, '#modal-create-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(user) {
                this.editForm.id = user.id;
                this.editForm.name = user.name;
                this.editForm.email = user.email;
                this.editForm.activate = user.activate;

                $('#modal-edit-client').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/administrator/users/update/' + this.editForm.id,
                    this.editForm, '#modal-edit-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.email = '';
                        form.activate = '';
                        form.errors = [];

                        $(modal).modal('hide');
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
