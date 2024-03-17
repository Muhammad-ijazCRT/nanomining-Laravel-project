@extends($activeTemplate . 'layouts.master')

@section('content')

    <div class="row justify-content-center">

        @if ($userCoinBalances->count())
            <div class="wallet-card-wrapper">
                @foreach ($userCoinBalances as $item)
                    <div class="wallet-card">
                        <button class="edit-btn updateBtn" data-id="{{ $item->id }}" data-title="{{ strtoupper($item->miner->coin_code) }}" data-wallet="{{ $item->wallet }}"><i class="la la-pencil"></i></button>

                        <div class="wallet-card-body">
                            <div class="top">
                                <img alt="Image" class="logo" src="{{ getImage(getFilePath('miner') . '/' . $item->miner->coin_image, getFileSize('miner')) }}">
                                <div>
                                    <h5 class="title">{{ strtoupper($item->miner->coin_code) }} @lang('Wallet')</h5>
                                    <small>{{ showAmount($item->balance, 8, exceptZeros:true) }}</strong> {{ strtoupper($item->miner->coin_code) }}</small>
                                </div>
                            </div>

                            <p class="address">
                                <span class="label">@lang('Wallet Address')</span>
                                <span class="value">
                                    @if ($item->wallet)
                                        {{ $item->wallet }}
                                    @else
                                        @lang('No address provided yet')
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-danger text-center mb-0">@lang('You have no wallet yet, please buy some plan first')</h3>
        @endif
    </div>
    <div class="modal custom--modal fade" id="walletAddressModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Wallet - ') <span class="addressTitle"></span></h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="wallet">@lang('Wallet')</label>
                            <input class="form--control" id="wallet" name="wallet" placeholder="@lang('Enter wallet Address')" required type="text" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.updateBtn').on('click', function() {
                let modal = $('#walletAddressModal');
                let data = $(this).data();

                modal.find('.addressTitle').text(data.title);
                modal.find('form').attr('action', `{{ route('user.wallet.update', '') }}/${data.id}`);
                modal.find('[name=wallet]').val(data.wallet);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
