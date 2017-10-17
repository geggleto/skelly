var vm = new Vue({
    el: '#app',
    data: {
        payload : {
            payment: {
                currency: "",
                method: "",
                stripe_customer_id: "",
                stripe_token_id : ""
            },
            invoice: {
                day_of_month: "",
                email_list: [],
                country: "",
                province: "",
                postal: "",
                city: "",
                addr2: "",
                addr1: ""
            },
            category: "",
            parent: "",
            name: "",
            id : ""
        },
        form : {
            parent : "",
            credit_card_result : "",
            email : ""
        },
        voidInvoiceId : ''
    },
    delimiters: ["<%", "%>"],
    methods: {
        addEmail : function () {
            this.payload.invoice.email_list.push(this.form.email);
        },
        removeEmail : function (email) {
            remove(this.payload.invoice.email_list, email);
        },
        hasCreditCardResult : function () {
            return (this.form.credit_card_result !== "")
        },
        submit : function () {
            $.ajax({
                method : "POST",
                type: "POST",
                url: "/api/business",
                data: this.payload,
                success: function (response) {
                    window.location = '/business/edit/'+response.id;
                },
                dataType: "json"
            });
        },
        voidInvoice : function () {
            $.ajax({
                method : "POST",
                type: "POST",
                url: "/api/invoice/void/"+this.voidInvoiceId,
                success: function (response) {
                    window.location = '/business/edit/'+response.id;
                },
                dataType: "json"
            });
        },
        setVoidInvoiceId : function (invoiceId) {
            this.voidInvoiceId = invoiceId;
        }
    },
    mounted : function () {
        $( "#parent" ).autocomplete({
            source: "/api/business/auto-complete",
            minLength: 2,
            select: function( event, ui ) {
                vm.payload.parent = ui.item.id;
            }
        });

        applyHandlers();
    },
    updated : function () {
        applyHandlers();
    }
});

var stripe = Stripe('{{ stripe }}');

// Create an instance of Elements
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: '#32325d',
        lineHeight: '24px',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};

// Create an instance of the card Element
var card = elements.create('card', {style: style, hidePostalCode : true});

// Add an instance of the card Element into the `card-element` <div>
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');

    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Handle form submission
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createSource(card, {}).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            var $request = {
                'customer_id' : vm.payload.payment.stripe_customer_id,
                'source' : result.source
            };

            $.ajax({
                type : "POST",
                url : "/api/stripe/customer",
                data : $request,
                dataType : 'json',
                success : function (response) {
                    vm.payload.payment.stripe_customer_id = response.id;
                }
            });
        }
    });
});

function applyHandlers() {
    $(".listRemove").off();

    $(".listRemove").each( function () {
        $(this).on('click', function (e) {
            var id = $(this).data('id');
            var index = vm.payload.invoice.email_list.indexOf(id);

            if (index >= 0) {
                vm.payload.invoice.email_list.splice(index, 1);
            }
        });
    });
}