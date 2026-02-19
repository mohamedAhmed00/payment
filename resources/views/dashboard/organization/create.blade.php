<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{route('organization.index')}}">{{ __('Organizations') }}</a></li>
                        <li class="breadcrumb-item">{{ __('Create organization') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Create organization') }}</h4>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/axios.js') }}"></script>
    <script src="{{ asset('assets/js/vue.js') }}"></script>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div id="rootwizard">
                        <ul class="nav  nav-pills bg-light nav-justified form-wizard-header mb-3">
                            <li class="nav-item" data-target-form="#account_form">
                                <a href="#first" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-account-circle me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Organization info') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#payment_types">
                                <a href="#second" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-bank me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment type') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#payment_suppliers">
                                <a href="#third" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-office-building me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment supplier') }}</span>
                                </a>
                            </li>

                            <li class="nav-item" data-target-form="#suppliers_settings">
                                <a href="#fourth" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-credit-card-settings me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('suppliers settings') }}</span>
                                </a>
                            </li>

                            <li class="nav-item" data-target-form="#payment_methods">
                                <a href="#fifth" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment method') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#confirm">
                                <a href="#sixth" data-bs-toggle="tab" data-toggle="tab"
                                   class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Confirm') }}</span>
                                </a>
                            </li>
                        </ul>

                        <div v-if="errors" class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <p v-for="error in errors">@{{ error[0] }}</p>
                        </div>

                        <div class="tab-content mb-0 b-0 pt-0">

                            <div class="tab-pane" id="first">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="account_form" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="name">{{ __('Name') }}</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" v-model="organization.name"
                                                           id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="phone">{{ __('Phone') }}</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" v-model="organization.phone"
                                                           id="phone" name="phone" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="tax_number">{{ __('Tax number') }}</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"
                                                           v-model="organization.tax_number" id="tax_number"
                                                           name="tax_number" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="address">{{ __('Address') }}</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"
                                                           v-model="organization.address" id="address" name="address"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="email">{{ __('Email') }}</label>
                                                <div class="col-md-9">
                                                    <input type="email" class="form-control"
                                                           v-model="organization.email" id="email" name="email"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"
                                                       for="email">{{ __('Status') }}</label>
                                                <div class="form-check mb-2 col-md-1 pull-right">
                                                    <input class="form-check-input" type="radio" value="1"
                                                           id="customradio1" v-model="organization.status">
                                                    <label class="form-check-label"
                                                           for="customradio1">{{ __('Active') }}</label>
                                                </div>
                                                <div class="form-check mb-2 col-md-1 pull-right">
                                                    <input class="form-check-input" type="radio" value="0"
                                                           id="customradio2" v-model="organization.status">
                                                    <label class="form-check-label"
                                                           for="customradio2">{{ __('In active') }}</label>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <span class="col-md-3 col-form-label">{{__('Logo')}}</span>
                                                <div class="col-md-9">
                                                    <input type="file"
                                                          v-on:change="onFileSelected" id="logo" name="logo"
                                                            accept="image/" hidden>
                                                    <label class="col-md-3" style="height: 50px; margin-top: -5px;"
                                                           for="logo"><i class="h3 text-muted dripicons-cloud-upload" ></i><span style="margin-left: 15px;">Select Image</span></label>

                                                    <img  v-bind:style= "[organization.logo ? {'height': '100px', 'width': '100px'} : {'height': '0',  'width': '0'}]" style="margin-top: 10px; display: block" :src="organization.logo">
                                                </div>
                                            </div>
                                        </form>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>

                            <div class="tab-pane fade" id="second">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="payment_types" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3 mt-3 mx-4">
                                                <div class="form-check my-1" v-for="type in data">
                                                    <input type="checkbox" class="form-check-input"
                                                           v-model="payment_type" :value="type.id"
                                                           :id="'payment_type_'+type.id">
                                                    <label class="form-check-label" :for="'payment_type_'+type.id">@{{
                                                        type.name }}</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="third">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="payment_suppliers" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3 mt-3 mx-4" v-for="type in data">
                                                <div V-if="checkType(type.id)">
                                                    <h3 class="h3 text-uppercase">@{{ type.name }}</h3>
                                                    <div v-if="type.suppliers.length != 0">
                                                        <div class="form-check my-1" v-for="supplier in type.suppliers">
                                                            <input type="checkbox" class="form-check-input"
                                                                   v-model="suppliers" name="supplier[]"
                                                                   :value="supplier.id" :id="'suppliers_'+supplier.id">
                                                            <label class="form-check-label"
                                                                   :for="'suppliers_'+supplier.id">@{{ supplier.name
                                                                }}</label>
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        {{ __('No suppliers') }}
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="fourth">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="suppliers_settings" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3 mt-3 mx-4">
                                                <div class="form-check my-1"
                                                     v-for="(supplier, index) in supplier_settings">
                                                    <div>
                                                        <div class="row mb-3">
                                                            <h3 class="h3 text-uppercase col-md-9">@{{ supplier.name
                                                                }}</h3>
                                                            <div class="col-md-2">
                                                                <button
                                                                    class=" btn btn-primary waves-effect waves-light"
                                                                    @click.prevent="addSupplierSetting(supplier)">{{ __('Add another one') }}</button>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button class=" btn btn-danger waves-effect waves-light"
                                                                        @click.prevent="removeSupplierSetting(index)"><i
                                                                        class="mdi mdi-close"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_name">{{ __('Name') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_name"
                                                                       id="supplier_name" name="supplier_name" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_server_key">{{ __('Server key') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_server_key"
                                                                       id="supplier_server_key"
                                                                       name="supplier_server_key">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_profile_id">{{ __('Client id') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_profile_id"
                                                                       id="supplier_profile_id"
                                                                       name="supplier_profile_id">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_access_code">{{ __('Access Code') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_access_code"
                                                                       id="supplier_access_code"
                                                                       name="supplier_access_code">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_access_code">{{ __('Merchant Identifier') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_merchant_identifier"
                                                                       id="supplier_merchant_identifier"
                                                                       name="supplier_merchant_identifier">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_access_code">{{ __('SHA request phrase') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_sha_request_phrase"
                                                                       id="supplier_sha_request_phrase"
                                                                       name="supplier_sha_request_phrase">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_access_code">{{ __('SHA response phrase') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_sha_response_phrase"
                                                                       id="supplier_sha_response_phrase"
                                                                       name="supplier_sha_response_phrase">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_provider_key">{{ __('Provider Key') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_provider_key"
                                                                       id="supplier_provider_key"
                                                                       name="supplier_provider_key">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_api_key">{{ __('Api Key') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_api_key"
                                                                       id="supplier_api_key"
                                                                       name="supplier_api_key">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_pay_integration_url">{{ __('Pay Integration URL') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_pay_integration_url"
                                                                       id="supplier_pay_integration_url"
                                                                       name="supplier_pay_integration_url">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="supplier_refund_integration_url">{{ __('Refund Integration URL') }}</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                       v-model="supplier.supplier_refund_integration_url"
                                                                       id="supplier_refund_integration_url"
                                                                       name="supplier_refund_integration_url">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                   for="email">{{ __('Environment Type') }}</label>
                                                            <div class="form-check mb-2 col-md-1 pull-right">
                                                                <input class="form-check-input" type="radio" value="production"
                                                                       id="customradio1" v-model="supplier.env_type">
                                                                <label class="form-check-label"
                                                                       for="customradio1">{{ __('Production') }}</label>
                                                            </div>
                                                            <div class="form-check mb-2 col-md-1 pull-right">
                                                                <input class="form-check-input" type="radio" value="test"
                                                                       id="customradio2" v-model="supplier.env_type">
                                                                <label class="form-check-label"
                                                                       for="customradio2">{{ __('Test') }}</label>
                                                            </div>
                                                        </div>


                                                        <div class="row mb-3">
                                                            <label for="currency-select"
                                                                   class="col-md-3 col-form-label">{{ __('Select Currency') }}</label>
                                                            <div class="col-md-9">
                                                                <select class="form-select" id="currency-select"
                                                                        v-model="supplier.supplier_currency">
                                                                    <option>{{ __('Select Currency') }}</option>
                                                                    <option v-for="currency in currencies"
                                                                            :value="currency.currency_code">@{{
                                                                        currency.currency_code }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>

                            <div class="tab-pane fade" id="fifth">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="payment_methods" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3 mt-3 mx-4" v-for="type in data">
                                                <div V-if="checkType(type.id)">
                                                    <h3 class="h3 text-uppercase my-3">@{{ type.name }}</h3>
                                                    <div v-if="type.suppliers.length != 0">
                                                        <div v-for="supplier in type.suppliers">
                                                            <div v-if="checkSupplier(supplier.id)">
                                                                <h3 class="h3 text-uppercase my-3">@{{ supplier.name
                                                                    }}</h3>
                                                                <div class="form-check my-1"
                                                                     v-for="method in supplier.payment_methods">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           v-model="methods" name="method[]"
                                                                           :value="method.id" :id="'method_'+method.id">
                                                                    <label class="form-check-label"
                                                                           :for="'method_'+method.id">@{{ method.name
                                                                        }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        {{ __('No methods') }}
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>

                            <div class="tab-pane fade" id="sixth">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h2 class="mt-0">
                                                <i class="mdi mdi-check-all"></i>
                                            </h2>
                                            <h3 class="mt-0">{{ __('Confirm') }}</h3>
                                            <form>
                                                @csrf
                                                <div class="mb-3 text-center">
                                                    <button type="submit" @click.prevent="submitOrganization()"
                                                            class="btn btn-primary waves-effect waves-light">{{ __('Submit') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>

                            <ul class="list-inline wizard mb-0">
                                <li class="previous list-inline-item"><a href="javascript: void(0);"
                                                                         class="btn btn-secondary">Previous</a>
                                </li>
                                <li class="next list-inline-item float-end"><a href="javascript: void(0);"
                                                                               class="btn btn-secondary">Next</a></li>
                            </ul>

                        </div> <!-- tab-content -->
                    </div> <!-- end #rootwizard-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <script>
        var app = new Vue({
            el: '#rootwizard',
            data: {
                organization: {
                    'name': '',
                    'phone': '',
                    'tax_number': '',
                    'address': '',
                    'email': '',
                    'status': '',
                    'logo' : null
                },
                data: @json($paymentTypes),
                logoExtension : '',
                payment_type: [],
                suppliers: [],
                methods: [],
                supplier_settings: [],
                all_suppliers: @json($suppliers),
                errors: null,
                currencies: @json(getCurrencies())
            },
            methods: {
                addSupplierSetting: function (supplier) {
                    const newSupplier = {...supplier};
                    this.supplier_settings.push(newSupplier);
                },
                removeSupplierSetting: function (index) {
                    this.supplier_settings.splice(index, 1);
                },
                submitOrganization: function () {
                    axios.post('{{ route('organization.store') }}',
                        {
                            'organization': this.organization,
                            'payment_type': this.payment_type,
                            'suppliers': this.suppliers,
                            'methods': this.methods,
                            'supplier_settings': this.supplier_settings,
                        })
                        .then(response => (
                            window.location.href = "{{ route('organization.index') }}"
                        ))
                        .catch(error =>
                            this.errors = error.response.data.errors
                        )
                },
                checkType: function (id) {
                    const check = item => item === id;
                    return this.payment_type.some(check);
                },
                checkSupplier: function (id) {
                    const check = item => item === id;
                    return this.suppliers.some(check);
                },
                onFileSelected: function (event){
                    const selectedImage = event.target.files[0];
                    this.createBase64Image(selectedImage);
                },
                createBase64Image: function (file){
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.organization.logo = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            watch: {
                suppliers: function (suppliers) {
                    this.supplier_settings = [];
                    for (let supplier of suppliers) {
                        for (let sup of this.all_suppliers) {
                            if (sup.id == supplier) {
                                let object = {
                                    supplier_id: sup.id,
                                    name: sup.name,
                                    supplier_name: null,
                                    supplier_server_key: null,
                                    supplier_profile_id: null,
                                    supplier_currency: null,
                                    supplier_access_code: null,
                                    supplier_merchant_identifier: null,
                                    supplier_sha_request_phrase: null,
                                    supplier_sha_response_phrase: null,
                                    supplier_pay_integration_url: null,
                                    supplier_pay_integration_url: null,
                                    env_type:null

                                };
                                this.supplier_settings.push(object);
                            }
                        }
                    }
                }
            }
        })
    </script>
</x-app-layout>
