@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row d-flex justify-content-center flex-wrap gy-4">
        @if ($userCoinBalances->count())
            @foreach ($userCoinBalances as $item)
                <div class="col-md-4">
                    <div class="wallet-card">
                        <div class="wallet-item flex-wrap">
                            <button class="updateBtn" data-id="{{ $item->id }}" data-title="{{ strtoupper($item->miner->coin_code) }}" data-wallet="{{ $item->wallet }}">
                                <i class="las la-edit"></i>
                            </button>
                            <div class="wallet-item__thumb">
                                <img src="{{ getImage(getFilePath('miner') . '/' . $item->miner->coin_image, getFileSize('miner')) }}" class="fit-image" alt="@lang('image')">
                            </div>
                            <div class="wallet-item__content">
                                <div class="wallet-item__info">
                                    <h5 class="wallet-item__name">{{ strtoupper($item->miner->coin_code) }} @lang('Wallet')</h5>
                                    <strong class="wallet-item__designation fs-14"> {{ showAmount($item->balance, 8, exceptZeros: true) }} {{ strtoupper($item->miner->coin_code) }}</strong>
                                </div>
                                <div class="wallet-item__info">
                                    <h5 class="wallet-item__name">@lang('Wallet Address')</h5>
                                    <span class="wallet-item__designation fs-14">
                                        @if ($item->wallet)
                                            {{ $item->wallet }}
                                        @else
                                            @lang('No address provided yet')
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card custom--card">
                <div class="card-body">
                    <h4 class="text-danger text-center mb-0">@lang('You have no wallet yet, please buy some plan first')</h4>
                </div>
            </div>
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
