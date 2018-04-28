<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Editing <em>{{ title }}</em></div>

                    <div class="card-body">
                        <div class="alert alert-success" v-if="message">
                            {{ message }}
                        </div>

                        <form action="#" @submit.prevent="submit">
                            <div class="form-group" v-bind:class="{'has-danger': errors.title}">
                                <label class="form-control-label" for="title">Title</label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    class="form-control"
                                    v-bind:class="{'is-invalid': errors.title}"
                                    v-model="title" 
                                >
                                <div class="invalid-feedback" v-if="errors.title">
                                    {{ errors.title[0] }}
                                </div>
                            </div>
                            
                            <draggable 
                                :list="parts" 
                                :options="{'handle': '.card-header'}"
                                @start="drag=true" 
                                @end="drag=false"
                                @change="update"
                            >
                                <div class="card card-default" v-for="part, index in parts">
                                    <div class="card-header">Part {{ index + 1 }} ({{ part.sort_order }})</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-control-label">Part title</label>
                                            <input 
                                                type="text"
                                                class="form-control"
                                                v-model="parts[index].title"
                                                v-bind:class="{'is-invalid': errors[`parts.${index}.title`]}"
                                            >
                                            <div class="invalid-feedback" v-if="errors[`parts.${index}.title`]">
                                                {{ errors[`parts.${index}.title`][0] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </draggable>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        components: {
            draggable
        },
        data() {
            return {
                title: null,
                parts: [],
                errors: [],
                message: null
            }
        },
        props: [
            'data'
        ],
        methods: {
            submit() {
                axios.patch(`/series/${this.data.id}`, {
                    title: this.title,
                    parts: this.parts
                }).then((response) => {
                    // set success message or redirect
                    this.message = 'Series saved.';
                }).catch((error) => {
                    this.errors = error.response.data.errors;
                });
            },
            update(e) {
                // dragging will rearrange the order of parts in our data
                // so we can map through an update the sort_order to the (index + 1)
                this.parts.map((part, index) => {
                    part.sort_order = index + 1;
                });
            }
        },
        mounted() {
            this.title = this.data.title;
            this.parts = this.data.parts;
        }
    }
</script>
