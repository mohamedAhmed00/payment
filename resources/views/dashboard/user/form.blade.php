<x-app-layout>
    <script src="{{ asset('assets/js/axios.js') }}"></script>
    <script src="{{ asset('assets/js/vue.js') }}"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('user.index')}}">{{ __('Users') }}</a></li>
                        <li class="breadcrumb-item">
                        @isset($user)
                            {{ __('Update user') }}
                        @else
                            {{ __('Create user') }}
                        @endisset
                    </ol>
                </div>
                <h4 class="page-title">
                    @isset($user)
                        {{ __('Update user') }}
                    @else
                        {{ __('Create user') }}
                    @endisset
                </h4>
            </div>
        </div>
    </div>
    <div class="row" id="selector">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <form class="user-form"
                                  action="{{ !empty($user)? route('user.update',$user->id) : route('user.store') }}"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                @isset($user)
                                    @method('PATCH')
                                @endisset
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name"
                                           value="{{ old('name', !empty($user) ? $user->name : '' ) }}"
                                           class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email', !empty($user) ? $user->email : '' ) }}"
                                           class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" name="password" id="password" value="" class="form-control"
                                           autocomplete="new-password"
                                           @input="handleConfirmationPasswordVisibility"
                                    >
                                </div>

                                <div class="mb-3" v-if="!isHidden">
                                    <label for="password_confirmation"
                                           class="form-label">{{ __('Confirm password') }}</label>
                                    <input  type="password"
                                            name="password_confirmation"
                                           id="password_confirmation" value="" class="form-control"
                                    >
                                </div>

                                <div class="mb-3">
                                    <label for="returning_url" class="form-label">{{ __('Returning Url') }}</label>
                                    <input type="text" name="returning_url" id="returning_url"
                                           value="{{ old('returning_url', !empty($user) ? $user->returning_url : '' ) }}"
                                           class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Select Group') }}</label>
                                    <select class="form-select" name="group_id">
                                        <option>{{ __('Select One') }}</option>
                                        @foreach($groups as $group)
                                            <option
                                                {{ (!empty($user) && $user->group_id == $group->id)?  'selected': '' }} value="{{ $group->id }}">{{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(empty(auth()->user()->organization))
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Select Organization') }}</label>
                                        <select class="form-select" name="organization_id">
                                            <option value="">{{ __('Select One') }}</option>
                                            @foreach($organizations as $organization)
                                                <option
                                                    {{ (!empty($user) && $user->organization_id == $organization->id)?  'selected ': '' }} value="{{ $organization->id }}">
                                                    {{ $organization->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif


                                <div class="mb-3">
                                    <label for="signature_key" class="form-label">{{ __('Signature key') }}</label>
                                    <input type="password" name="signature_key" id="signature_key"
                                           value="" class="form-control"
                                           autocomplete="new-password"
                                    >
                                </div>

                                <div class="row mb-3">
                                    <label
                                        class="col-md-3 col-form-label">{{ __('Callback system authentication type') }}
                                        :</label>
                                    <div v-for="type in types" class="form-check mb-2 col-md-2 pull-right">
                                        <input class="form-check-input" type="radio" v-model="auth_type"
                                               name="auth_type" :value="type">
                                        <label class="form-check-label">@{{ type }}</label>
                                    </div>
                                </div>


                                <transition name="slide">
                                    <div class="conig-wrapper" v-if="auth_type == 'token'">
                                        <div class="row">
                                            <div class="mb-3 col-5">
                                                <label for="login_url"
                                                       class="form-label">{{ __('System login url') }}</label>
                                                <input type="text" name="login_url"
                                                       id="login_url"
                                                       value="{{ old('login_url', !empty($user) && optional($user->system_configuration)['login_url'] !==null? $user->system_configuration['login_url'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="notification_url"
                                                       class="form-label">{{ __('System notification url') }}</label>
                                                <input type="text" name="notification_url"
                                                       id="notification_url"
                                                       value="{{ old('notification_url', !empty($user) && optional($user->system_configuration)['notification_url'] !==null? $user->system_configuration['notification_url'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="origin"
                                                       class="form-label">{{ __('Origin') }}</label>
                                                <input type="text" name="origin"
                                                       id="origin"
                                                       value="{{ old('origin', !empty($user) && optional($user->system_configuration)['origin'] !==null ? $user->system_configuration['origin'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="username"
                                                       class="form-label"> {{ __('Username') }}</label>
                                                <input type="text" name="username"
                                                       id="username"
                                                       value="{{ old('username', !empty($user) && optional($user->system_configuration)['username'] !==null ? $user->system_configuration['username'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="password"
                                                       class="form-label">{{ __('Password') }}</label>
                                                <input type="password" name="password"
                                                       id="password"
                                                       autocomplete="new-password"
                                                       value="{{ old('password', !empty($user) && optional($user->system_configuration)['password'] !==null ? $user->system_configuration['password'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conig-wrapper" v-if="auth_type == 'backOffice'">
                                        <div class="row">
                                            <div class="mb-3 col-5">
                                                <label for="login_url"
                                                       class="form-label">{{ __('System login url') }}</label>
                                                <input type="text" name="login_url"
                                                       id="login_url"
                                                       value="{{ old('login_url', !empty($user) && optional($user->system_configuration)['login_url'] !==null? $user->system_configuration['login_url'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="notification_url"
                                                       class="form-label">{{ __('System notification url') }}</label>
                                                <input type="text" name="notification_url"
                                                       id="notification_url"
                                                       value="{{ old('notification_url', !empty($user) && optional($user->system_configuration)['notification_url'] !==null? $user->system_configuration['notification_url'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                            <div class="mb-3 col-5">
                                                <label for="domain_type"
                                                       class="form-label">{{ __('Agent') }}</label>
                                                <input type="text" name="agent"
                                                       id="agent"
                                                       value="{{ old('agent', !empty($user) && optional($user->system_configuration)['agent'] !==null ? $user->system_configuration['agent'] : '' ) }}"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                                <button type="submit"
                                        class="btn btn-primary waves-effect waves-light btn-submit">{{ __('Save') }}</button>
                            </form>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div>
        </div> <!-- end col -->
    </div>

    <script>
        var app = new Vue({
            el: '#selector',
            data: {
                types: @json(config('app.callback_system_authentication_types')),
                auth_type: '{{ !empty($user) and !empty(optional($user->system_configuration)['auth_type'])? $user->system_configuration['auth_type'] : ''}}',
                isHidden: true

            },
            methods: {
                handleConfirmationPasswordVisibility(event) {
                    const {srcElement, type} = event;
                    const {name, value} = srcElement;
                    this.isHidden = !(type === 'input' && value.length > 7);
                },
            }
        });

    </script>

    <style>
        .user-form {
            height: auto;
        }

        input:checked {
            outline: none;
            background-color: #0d5a4b;
        }

        .conig-wrapper {
            list-style-type: none;
            transform-origin: top;
            transition: transform .4s ease-in-out;
            overflow: hidden;
            margin-top: 30px
        }

        .slide-enter, .slide-leave-to {
            transform: scaleY(0);
        }

        .btn-submit {
            margin-top: 40px;
        }
    </style>
</x-app-layout>
