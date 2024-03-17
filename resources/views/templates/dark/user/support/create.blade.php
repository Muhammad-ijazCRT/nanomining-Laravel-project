@extends($activeTemplate . 'layouts.master')
@section('content')
    {{-- <div class="card-header d-flex flex-wrap align-items-center justify-content-between"> --}}
    {{-- <div class="card-header card custom--card responsive-filter-card">

        <h4 class="card-title mb-0">
            {{ __($pageTitle) }}
        </h4>
        <a href="{{ route('ticket.index') }}" class="btn btn-sm btn--base"><i class="la la-list"></i>@lang('My Support Tickets')</a>
    </div> --}}














    <div class="dashboard-body">

        <div class="card-area">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-12 mb-30">
                    <div class="card custom--card mb-30">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Support Tickets
                            </h4>
                            <a href="https://home.easymining.io/ticket" class="btn btn-sm btn--base"><i class="la la-list"></i>
                                My
                                Support
                                Ticket
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card-form-wrapper">


                                <form action="{{ route('ticket.store') }}" enctype="multipart/form-data" method="post"
                                    onsubmit="return submitUserForm();">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label for="name" class="required">Name</label>
                                            <input type="text" name="name" value=" "
                                                class="form--control form-control-lg" placeholder="Enter Name"
                                                required="">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="email" class="required">Email address</label>
                                            <input type="email" name="email" value="ba  jufimona@mailinator.com"
                                                class="form--control form-control-lg" placeholder="Enter your Email"
                                                required="">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">@lang('Subject')</label>
                                            <input class="form--control" name="subject" required type="text"
                                                value="{{ old('subject') }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">@lang('Priority')</label>
                                            <select class="custom--select form--control" name="priority" required>
                                                <option @selected(old('priority' == 3)) value="3">@lang('High')
                                                </option>
                                                <option @selected(old('priority' == 2)) value="2">@lang('Medium')
                                                </option>
                                                <option @selected(old('priority' == 1)) value="1">@lang('Low')
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">@lang('Message')</label>
                                            <textarea class="form--control" id="inputMessage" name="message" required rows="6">{{ old('message') }}</textarea>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn--base btn-sm addFile" type="button">
                                                <i class="fa fa-plus"></i> @lang('Add New')
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <div class="file-upload">
                                                <label class="form-label">@lang('Attachments') <small
                                                        class="text-danger">@lang('Max 5 files can be uploaded').
                                                        @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                                </label>
                                                <input accept=".png, .jpg, .jpeg,.pdf,.doc,.docx"
                                                    class="form-control form--control mb-2" id="inputAttachments"
                                                    name="attachments[]" type="file" />
                                                <div id="fileUploadsContainer" class="mt-3"></div>
                                                <p class="ticket-attachments-message text-muted">
                                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'),
                                                    .@lang('png'),
                                                    .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                                </p>
                                            </div>

                                        </div>
                                        <button class="btn btn--base w-100" id="recaptcha"
                                            type="submit">@lang('Submit')</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>




    {{-- <h5>{{ __($pageTitle) }}</h5>
    <div class="contact-form">
        <form action="{{ route('ticket.store') }}" enctype="multipart/form-data" method="post" onsubmit="return submitUserForm();">
            @csrf
            <div class="row">
                <div class="form-group">
                    <label class="form-label">@lang('Subject')</label>
                    <input class="form--control" name="subject" required type="text" value="{{ old('subject') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Priority')</label>
                    <select class="custom--select form--control" name="priority" required>
                        <option @selected(old('priority' == 3)) value="3">@lang('High')</option>
                        <option @selected(old('priority' == 2)) value="2">@lang('Medium')</option>
                        <option @selected(old('priority' == 1)) value="1">@lang('Low')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Message')</label>
                    <textarea class="form--control" id="inputMessage" name="message" required rows="6">{{ old('message') }}</textarea>
                </div>
                <div class="text-end">
                    <button class="btn btn--base btn-sm addFile" type="button">
                        <i class="fa fa-plus"></i> @lang('Add New')
                    </button>
                </div>
                <div class="form-group">
                    <div class="file-upload">
                        <label class="form-label">@lang('Attachments') <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small> </label>
                        <input accept=".png, .jpg, .jpeg,.pdf,.doc,.docx" class="form-control form--control mb-2" id="inputAttachments" name="attachments[]" type="file" />
                        <div id="fileUploadsContainer" class="mt-3"></div>
                        <p class="ticket-attachments-message text-muted">
                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                        </p>
                    </div>

                </div>
                <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Submit')</button>
            </div>

        </form>
    </div> --}}
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="form-group">
                        <div class="input-group">
                            <input type="file" name="attachments[]" class="form-control form--control" required accept=".png, .jpg, .jpeg,.pdf,.doc,.docx"/>
                            <button class="input-group-text btn--danger remove-btn"><i class="las la-times"></i></button>
                        </div>    
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
