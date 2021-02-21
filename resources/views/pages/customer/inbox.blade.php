@extends('layouts.base')

@section('title', 'Inbox Customer')

@section('extra-fonts')

@endsection

@section('prerender-js')
<script>
window.props = {
    inboxes: [
        @foreach( $inboxes as $inbox )
            {
                id: "{{ $inbox->id }}",
                customer_id: "{{ $inbox->customer_id }}",
                partner_id: "{{ $inbox->partner_id }}",
                project_id: "{{ $inbox->project_id }}",
                project: {
                    id: "{{ $inbox->project->id }}",
                    name: "{{ $inbox->project->name }}",
                    count: "{{ $inbox->project->count }}",
                    cost: "{{ $inbox->project->cost }}",
                    status: "{{ $inbox->project->status }}",
                    start_date: "{{ $inbox->project->start_date }}",
                    end_date: "{{ $inbox->project->end_date }}",
                    images: [@forEach( $inbox->project->images as $image) 
                            {
                                path: "{{ $image->path }}"
                            },
                            @endforeach],
                            category: @json($inbox->project->category),
                    note: "{{ $inbox->project->note }}",
                },
                partner: @json($inbox->partner),   
                transaction: {
                    id: "{{ $inbox->project->name }}",
                },
                chats: [
                    @foreach( $inbox->chats as $chat )
                        {
                            @inject('chatConstants', 'App\Constant\ChatTemplateConstant')
                            @if (($chat->type != $chatConstants::NEGOTIATION_TYPE || $chat->answer != $chatConstants::BLANK_ANSWER || $chat->role != $role ) &&
                                ($chat->type != $chatConstants::NEGOTIATION_TYPE || $chat->answer != $chatConstants::REJECT_ANSWER || $chat->role != $role ) &&
                                ($chat->type != $chatConstants::PROJECT_OFFER_TYPE || $chat->role == $role ))
                                
                                id: "{{ $chat->id }}",
                                created_at: "{{ $chat->created_at }}",
                                role: "{{ $chat->role }}",
                                type: "{{ $chat->type }}",
                                answer: "{{ $chat->answer }}",
                                excuse: "{{ $chat->excuse }}",
                                negotiation: @json($chat->negotiation)
                            @endif
                        },
                    @endforeach
                ],
            },
        @endforeach
    ],
    userRole: @json($role)
}
</script>
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('extra-css')
@endsection

@section('content')
<div class="custom-container">
    <div class="custom-wrapper">
        <div id="customer-inbox" ></div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    var changeModalChatNegotiation = function(e) {
        const projectID = e.getAttribute("data-projectId");
        const customerID = e.getAttribute("data-customerId");
        const partnerID = e.getAttribute("data-partnerId");
        const inboxID = e.getAttribute("data-inboxId");
        const chatID = e.getAttribute("data-chatId");

        $(".negotiation-project-id").val(projectID);
        $(".negotiation-customer-id").val(customerID);
        $(".negotiation-partner-id").val(partnerID);
        $(".negotiation-inbox-id").val(inboxID);
        $(".negotiation-chat-id").val(chatID);

        $("#chat-negotiation-form").attr("action", "{{ route('home.inbox.nego.offer') }}");
        $("#chat-negotiation-form").append('@csrf');

    }

    var changeModalChatInitiationReject = function(e) {
        const chatID = e.getAttribute("data-chatId");

        $(".negotiation-chat-id").val(chatID);

        $("#chat-initiation-reject-form").attr("action", "{{ route('home.inbox.nego.reject') }}");
        $("#chat-initiation-reject-form").append('@csrf');

    }

    var changeModalChatNegotiationAccept = function(e) {
        const inboxID = e.getAttribute("data-inboxId");
        const chatID = e.getAttribute("data-chatId");
        const negotiationID = e.getAttribute("data-negotiationId");

        $(".negotiation-inbox-id").val(inboxID);
        $(".negotiation-chat-id").val(chatID);
        $(".negotiation-negotiation-id").val(negotiationID);

        $("#chat-negotiation-accept-form").attr("action", "{{ route('home.inbox.nego.accept') }}");
        $("#chat-negotiation-accept-form").append('@csrf');
    }

    var changeModalChatAskSample = function(e) {
        const projectID = e.getAttribute("data-projectId");
        const partnerID = e.getAttribute("data-partnerId");
        const inboxID = e.getAttribute("data-inboxId");
        const chatID = e.getAttribute("data-chatId");
        const negotiationID = e.getAttribute("data-negotiationId");

        $(".negotiation-project-id").val(projectID);
        $(".negotiation-partner-id").val(partnerID);
        $(".negotiation-inbox-id").val(inboxID);
        $(".negotiation-chat-id").val(chatID);
        $(".negotiation-negotiation-id").val(negotiationID);

        $("#chat-ask-sample-form").attr("action", "{{ route('home.inbox.sample.request') }}");
        $("#chat-ask-sample-form").append('@csrf');
    }

    var changeModalChatProjectPermission =  function(e) {
        const projectID = e.getAttribute("data-projectId");
        const partnerID = e.getAttribute("data-partnerId");
        const inboxID = e.getAttribute("data-inboxId");
        const chatID = e.getAttribute("data-chatId");
        const negotiationID = e.getAttribute("data-negotiationId");

        $(".negotiation-project-id").val(projectID);
        $(".negotiation-partner-id").val(partnerID);
        $(".negotiation-inbox-id").val(inboxID);
        $(".negotiation-chat-id").val(chatID);
        $(".negotiation-negotiation-id").val(negotiationID);

        $("#chat-project-permission-form").attr("action", "{{ route('home.inbox.sample.deal') }}");
        $("#chat-project-permission-form").append('@csrf');
    }
    
</script>
@endsection
