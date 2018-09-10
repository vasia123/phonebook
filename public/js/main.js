Vue.component('record-list', {
    template: '#record-list',
    props: {
        records: {
            type: Array,
            default: []
        },
        all_count: Number,
        is_loading: Boolean
    },
    data() {
        return {
            newRecordName: '',
            newRecordPhone: '',
            searchField: '',
            searchActive: false,
            errors: [],
            is_edited: {}
        };
    },
    methods: {
        checkFormErrors() {
            if (!this.newRecordName) {
                this.errors.push('Имя не может быть пустым');
            }
            if (!this.newRecordPhone) {
                this.errors.push('Телефон не может быть пустым');
            }
        },
        addRecord() {
            this.errors = [];
            this.checkFormErrors();
            if (this.errors.length == 0) {
                this.$emit('add-phone', this.newRecordName, this.newRecordPhone);
                this.newRecordName = '';
                this.newRecordPhone = '';
            }
        },
        searchRecords() {
            if (this.searchField) {
                this.searchActive = true;
                this.$emit('search-phone', this.searchField);
            }
        },
        removeRecord(record) {
            this.$emit('remove-phone', record);
        },
        editRecord() {
            this.errors = [];
            this.checkFormErrors();
            if (this.errors.length == 0) {
                this.$emit('edit-phone', this.newRecordPhone, this.newRecordName, this.is_edited.phone);
                this.newRecordName = '';
                this.newRecordPhone = '';
                this.is_edited = {};
            }
        },
        preEditRecord(index, record) {
            this.errors = [];
            if (this.is_edited.phone && this.is_edited.phone.length > 0) {
                this.errors.push('Сначала закончите редактировать текущий номер');
            } else {
                this.newRecordName = record.name;
                this.newRecordPhone = record.phone;
                this.is_edited.name = record.name;
                this.is_edited.phone = record.phone;
                this.is_edited.index = index;
                this.records.splice(index, 1);
            }
        },
        clearSearch() {
            this.searchField = '';
            this.searchActive = false;
            this.$emit('refresh-phones');
        }
    }
});

Vue.component('record-item', {
    template: '#record-item',
    props: ['record', 'index']
});

new Vue({
    el: '#app',
    data() {
        return {
            records: [],
            is_loading: false,
            all_count: 0
        };
    },
    methods: {
        refreshPhones() {
            let self = this;
            self.records = [];
            self.is_loading = true;
            axios.get('?phones/get-all')
                .then(response => {
                    self.records = response.data.message.data;
                    self.all_count = response.data.message.count_all;
                    self.is_loading = false;
                })
                .catch(error => {
                    console.log(error);
                    self.is_loading = false;
                });
        },
        addPhone(name, phone) {
            let self = this;
            self.records = [];
            self.is_loading = true;
            axios.post('?phones/add-one', {
                name: name,
                phone: phone
            })
                .then(function (response) {
                    self.is_loading = false;
                    if (response.data.success) {
                        self.refreshPhones()
                    } else {
                        alert('Ошибка: '+response.data.message)
                    }
                })
                .catch(function (error) {
                    self.is_loading = false;
                    console.log(error);
                    alert('Ошибка: '+error)
                    self.refreshPhones()
                });

        },
        removePhone(record) {
            let self = this;
            self.records = [];
            self.is_loading = true;
            axios.post('?phones/remove-one', {
                phone: record.phone
            })
                .then(function (response) {
                    self.is_loading = false;
                    if (response.data.success) {
                        self.refreshPhones()
                    } else {
                        alert('Ошибка: '+response.data.message)
                    }
                })
                .catch(function (error) {
                    self.is_loading = false;
                    console.log(error);
                    alert('Ошибка: '+error)
                    self.refreshPhones()
                });
        },
        editPhone(phone, name, old_phone) {
            let self = this;
            self.records = [];
            self.is_loading = true;
            axios.post('?phones/edit-one', {
                phone: phone,
                name: name,
                old_phone: old_phone
            })
                .then(function (response) {
                    self.is_loading = false;
                    if (response.data.success) {
                        self.refreshPhones()
                    } else {
                        alert('Ошибка: '+response.data.message)
                    }
                })
                .catch(function (error) {
                    self.is_loading = false;
                    console.log(error);
                    alert('Ошибка: '+error)
                    self.refreshPhones()
                });
        },
        searchPhone(search_string) {
            let self = this;
            self.records = [];
            self.is_loading = true;
            axios.post('?phones/search', {
                search: search_string
            })
                .then(function (response) {
                    self.is_loading = false;
                    if (response.data.success) {
                        self.records = response.data.message.data;
                        self.all_count = response.data.message.count_all;
                    } else {
                        alert('Ошибка: '+response.data.message)
                    }
                })
                .catch(function (error) {
                    self.is_loading = false;
                    console.log(error);
                    alert('Ошибка: '+error)
                    self.refreshPhones()
                });
        }
    },
    mounted() {
        this.refreshPhones()
    }
});