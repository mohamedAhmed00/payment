<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('user.index')}}">{{ __('Users') }}</a></li>
                        <li class="breadcrumb-item">{{ __('User payment settings') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('User payment settings') }}</h4>
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
                            <li class="nav-item" data-target-form="#payment_types">
                                <a href="#first" data-bs-toggle="tab" data-toggle="tab" class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-bank me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment type') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#payment_suppliers">
                                <a href="#second" data-bs-toggle="tab" data-toggle="tab" class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-office-building me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment supplier') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#payment_methods">
                                <a href="#third" data-bs-toggle="tab" data-toggle="tab" class="disabled nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-cash me-1"></i>
                                    <span class="d-none d-sm-inline">{{ __('Payment methods') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" data-target-form="#confirm">
                                <a href="#fifth" data-bs-toggle="tab" data-toggle="tab" class="disabled nav-link rounded-0 pt-2 pb-2">
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
                                        <form id="payment_types" method="post" action="#" class="form-horizontal">
                                            <div class="row mb-3 mt-3 mx-4">
                                                <div class="form-check my-1" v-for="type in data">
                                                    <input type="checkbox" class="form-check-input" v-model="payment_type" :value="type.id" :id="'payment_type_'+type.id">
                                                    <label class="form-check-label"  :for="'payment_type_'+type.id">@{{ type.name }}</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>

                            <div class="tab-pane fade" id="second">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="payment_suppliers" method="post" action="#" class="form-horizontal">

                                            <div class="row mb-3 mt-3 mx-4" v-for="type in data" >
                                                <div V-if="checkType(type.id)">
                                                    <h3 class="h3 text-uppercase my-3">@{{ type.name }}</h3>
                                                    <div class="form-check my-1" v-for="supplier in all_suppliers" v-if="type.id == supplier.payment_type_id">
                                                        <input type="radio" @change="check(supplier)" class="form-check-input" v-model="supplier_settings" :value="supplier.id" :id="'suppliers_'+supplier.id">
                                                        <label class="form-check-label"  :for="'suppliers_'+supplier.id">@{{ JSON.parse(supplier.settings).supplier_name }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="third">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="payment_methods" method="post" action="#" class="form-horizontal">
                                            <div class="row" >
                                                <div class="row mb-3 mt-3 mx-4" v-for="type in data" v-if="checkTypes(type.id)">

                                                    <div class="form-check my-1 mb-1 mt-1 mx-4" v-for="method in payment_methods" v-if="typeof supplier == 'object' && supplier !== null && method.supplier_id == supplier.supplier_id && method.supplier.payment_type_id == type.id">
                                                        <input type="checkbox" class="form-check-input" v-model="methods" name="method[]" :value="method.id" :id="'method_'+method.id">
                                                        <label class="form-check-label"  :for="'method_'+method.id">@{{ method.name }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="fifth">
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
                                                    <button type="submit" @click.prevent="submitUser()" class="btn btn-primary waves-effect waves-light">{{ __('Submit') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>
                        </div>

                            <ul class="list-inline wizard mb-0">
                                    <li class="previous list-inline-item"><a href="javascript: void(0);" class="btn btn-secondary">Previous</a>
                                    </li>
                                    <li class="next list-inline-item float-end"><a href="javascript: void(0);" class="btn btn-secondary">Next</a></li>
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
                data: @json($organization->paymentTypes),
                payment_methods: @json($organization->paymentMethod()->with('supplier')->get()->toArray()),
                all_suppliers: @json($organization->suppliers),
                supplier_settings: @json($user->supplierSettings?->id),
                supplier: '',
                methods: [],
                payment_type: @json($user->paymentTypes()->get()->pluck('id')->toArray()),
                errors: null
            },
            created() {
                for (const supplier of this.all_suppliers) {
                    if(this.supplier_settings == supplier.id){
                        this.supplier = supplier;
                    }
                }
            },
            methods: {
                submitUser: function () {
                    axios.put('{{ route('user.payment_settings', $user->id) }}',
                        {
                            'payment_type': this.payment_type,
                            'methods': this.methods,
                            'supplier_settings': this.supplier_settings,
                        })
                        .then(response => (
                            window.location.href = "{{ route('organization.show', $organization->id) }}"
                        ))
                        .catch(error =>
                            this.errors = error.response.data.errors
                        )
                },
                checkSupplier: function (id) {
                    const check = item_id => item_id === id;
                    return this.suppliers.some(check);
                },
                checkType: function (id) {
                    const check = item_id => item_id === id;
                    return this.payment_type.some(check);
                },
                checkTypes: function (id) {
                    const check = item_id => item_id === id;
                    return this.payment_type.some(check);
                },
                check: function (supplier){
                    this.supplier = supplier;
                }
            }
        })
    </script>
</x-app-layout>
