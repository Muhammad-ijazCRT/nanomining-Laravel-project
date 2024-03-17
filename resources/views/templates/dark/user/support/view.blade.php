@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
    @if (!auth()->user())
        <section class="py-100 section-bg">
            <div class="container">
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title text-white">
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h5>
                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="btn btn-danger close-button btn-sm confirmationBtn" data-action="{{ route('ticket.close', $myTicket->id) }}" data-question="@lang('Are you sure to close this ticket?')" type="button"><i class="fa fa-lg fa-times-circle"></i>
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <textarea class="form--control" name="message" placeholder="@lang('Your Reply')" required rows="4">{{ old('message') }}</textarea>
                            </div>

                            <div class="form-group text-end">
                                <button class="btn btn--base btn-sm addFile" type="button">
                                    <i class="fa fa-plus"></i> @lang('Add New')
                                </button>
                            </div>

                            <div class="form-group">
                                <div class="file-upload">
                                    <label class="form-label">@lang('Attachments') <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small> </label>
                                    <input accept=".png, .jpg, .jpeg,.pdf,.doc,.docx" class="form-control form--control mb-2" id="inputAttachments" name="attachments[]" type="file" />
                                    <div class="mt-3" id="fileUploadsContainer"></div>
                                    <p class="ticket-attachments-message text-muted">
                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                    </p>
                                </div>
                            </div>
                            <button class="btn--base w-100" type="submit"> <i class="fa fa-reply"></i> @lang('Reply')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom--card mt-4">
                <div class="card-body">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row border-primary border-radius-3 my-3 mx-2 border py-3">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row border-warning border-radius-3 my-3 mx-2 border py-3" style="background-color: #ffd96729">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    @if (!auth()->user())
        </div>
        </section>
    @endif

    <x-confirmation-modal />
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
                            <input type="file" name="attachments[]" class="form-control form--control" accept=".png, .jpg, .jpeg,.pdf,.doc,.docx" required />
                            <button class="input-group-text btn--danger remove-btn"><i class="las la-times"></i></button>
                        </div>
                </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });

            $('#confirmationModal').find('.btn--primary').addClass('btn--base btn--sm').removeClass('btn--primary');
            $('#confirmationModal').find('.btn--dark').addClass('btn--danger btn--sm').removeClass('btn--dark');
        })(jQuery);
    </script>
@endpush
