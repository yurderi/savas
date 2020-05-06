<template>
    <div class="system-requirements">
        <div class="modal-header">
            System Requirements
        </div>
        <div class="row">
            <div class="column" v-if="model">
                <div class="form-item">
                    <label for="type">
                        Type
                    </label>
                    <v-select id="type" :data="types" v-model="model.type" />
                </div>
                <div class="form-item">
                    <label for="name">
                        Requirement / Application
                    </label>
                    <v-input id="name" type="text" v-model="model.name" />
                </div>
                <div class="form-item">
                    <label for="version">
                        Version
                    </label>
                    <v-input id="version" type="text" v-model="model.version" />
                </div>
                <div class="form-buttons" style="position:static;margin:0">
                    <v-button type="submit" v-if="requi">
                        Create
                    </v-button>
                    <v-button type="submit">
                        Save
                    </v-button>
                </div>
            </div>
            <div class="column">
                table goes here
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            types: [
                {
                    id: 'app',
                    label: 'Application'
                },
                {
                    id: 'custom',
                    label: 'Custom'
                }
            ],
            model: null
        }
    },
    mounted() {
        this.model = this.$models.requirement.create();
    }
}
</script>