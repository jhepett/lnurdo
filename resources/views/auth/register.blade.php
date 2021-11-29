@extends('layouts.app')

@section('content')

@php
    $colleges = \App\Models\College::all();
    $units = \App\Models\Unit::all();
    $userTypes = \App\Models\UserType::where([['user_type', '!=', 'Admin'], ['user_type', '!=', 'RDO']])->get();
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id="register_form" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" value="{{ old('gender') }}" required autofocus>
                                    <option value="" hidden>Select</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Rather Not Say</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Contact number') }}</label>

                            <div class="col-md-6">
                                <input id="contact_number" type="text" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" value="{{ old('contact_number') }}" required autofocus>

                                @error('contact_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required autofocus>{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('User Type') }}</label>

                            <div class="col-md-6">
                                <select name="user_type" id="user_type" class="form-control @error('user_type') is-invalid @enderror" value="{{ old('user_type') }}" required autofocus>
                                    <option value="" hidden>Select</option>
                                    @foreach ($userTypes as $userType)
                                        <option value="{{$userType->id}}">{{$userType->user_type}}</option>
                                    @endforeach
                                </select>
                                @error('user_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('College') }}</label>

                            <div class="col-md-6">
                                <select name="college" id="college" class="form-control @error('college') is-invalid @enderror" value="{{ old('college') }}" required autofocus>
                                    <option value="" hidden>Select</option>
                                    @foreach ($colleges as $college)
                                        <option value="{{$college->id}}">{{$college->college}}</option>
                                    @endforeach
                                </select>
                                @error('college')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Unit') }}</label>

                            <div class="col-md-6">
                                <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit') }}" disabled required autofocus>
                                   
                                </select>
                                @error('unit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        let units = @json($units);
        $('#college').on('change', function(){
            let college_id = $(this).val();
            console.log(units.length)
            let el='<option value="" hidden>Select</option>';
            units.forEach(unit=>{
                if(unit.college_id == college_id){
                    el+= '<option value="'+unit.id+'">'+unit.unit+'</option>';
                }
            });
            $('#unit').html(el);
            $('#unit').prop('disabled', false);
        });
        $('#user_type').on('change', function(){
            let user_type = $(this).val();

            if(user_type == 2 || user_type == 3){
                $('#unit').parent().parent().show();
                $('#college').parent().parent().show();
                $('#unit').prop('required', true);
                $('#college').prop('required', true);
            }
            else if(user_type == 4){
                $('#unit').parent().parent().hide();
                $('#unit').val('');
                $('#unit').prop('required', false);
                $('#college').prop('required', true);
                $('#college').parent().parent().show(); 
            }
            else if(user_type == 5){
                $('#unit').parent().parent().hide();
                $('#unit').val('');
                $('#college').parent().parent().hide();
                $('#college').val('');
                $('#unit').prop('required', false);
                $('#college').prop('required', false);
            }
        });



    </script>
@endsection