@extends('layouts.base')

@section('extra-fonts')

@endsection

@section('prerender-js')
<script>
    const chatProject = [
    {
        id: "111",
        userRole: "VENDOR",
        project: {
            id: "123123",
            name: "Relawan Rompi COVID",
            amount: "13000",
            price: "1000000",
            start_date: "2020-10-29T03:59:09",
            end_date: "2020-11-01T03:59:09",
            note: "test",
        },
        transaction: {
            id: "123111",
        },
        message: [
            {
                role: "CLIENT",
                type: "INISIASI",
                answer: "accept"
            },
            {
                role: "VENDOR",
                type: "DIAJUKAN",
            },
            {
                role: "CLIENT",
                type: "SETUJU",
            },
        ],
    },
    {
        id: "123",
        userRole: "VENDOR",
        project: {
            id: "123123",
            name: "Relawan Rompi COVID",
            amount: "15000",
            price: "2000000",
            start_date: "2020-10-29T03:59:09",
            end_date: "2020-11-01T03:59:09",
            note: "test123",
        },
        transaction: {
            id: "123111",
        },
        message: [
            {
                role: "CLIENT",
                type: "INISIASI",
                answer: "reject"
            },
            {
                role: "VENDOR",
                type: "DIAJUKAN",
            },
            {
                role: "CLIENT",
                type: "NEGOSIASI",
                answer: "accept"
            },
            {
                role: "VENDOR",
                type: "SETUJU",
            },
        ],
    },
];
</script>
@endsection

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/userChat.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/form.css') }}"/>
@endsection

@section('content')
@include('layouts/modalChatNegotiation')
<div class="userChat">
    <div class="userChat__container">
        <h2 class="userChat__title">Pesan</h2>
        <div class="chatbox">
            <div class="chatbox__navigation navigation">
                <div class="navigation__story"></div>

                <div class="navigation__item" data-id="111">
                    <div class="navigation__left">
                        <h5 class="navigation__title">Rompi Relawan COVID</h5>
                        <p class="navigation__description">Transaksi #123231 sudah terverifikasi . . .</p>
                    </div>
                    <div class="navigation__right">
                        <p class="navigation__date">10 Maret 2020</p>
                    </div>
                </div>

                <div class="navigation__item" data-id="123">
                    <div class="navigation__left">
                        <h5 class="navigation__title">Rompi Relawan COVID</h5>
                        <p class="navigation__description">Transaksi #123231 sudah terverifikasi . . .</p>
                    </div>
                    <div class="navigation__right">
                        <p class="navigation__date">10 Maret 2020</p>
                    </div>
                </div>
            </div>

            <div class="chatbox__container">
                <div class="chatbox__header">
                    <h6 class="chatbox__title">Rompi Relawan COVID</h6>
                    <!-- <div class="chatbox__more">
                        <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                    </div> -->
                </div>
                <div class="chatbox__messages">
                    <div class="chatbox__noMessages__wrapper">
                        <img src="{{ asset('img/empty-chat.svg') }}" alt="no-message"/>
                        <h5 class="chatbox__noMessages__title">Mulai transaksi untuk melihat chat.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="{{ asset('js/userChat.js') }}"></script>
<script src="{{ asset('js/chatTemplate.js') }}"></script>
@endsection
