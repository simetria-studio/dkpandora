@extends('layouts.app')

@section('title', 'Pagamento - Pedido #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Pagamento - Pedido #{{ $order->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Resumo do Pedido -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Resumo do Pedido</h5>
                            <p><strong>Usuário do Jogo:</strong> {{ $order->game_username }}</p>
                            <p><strong>Servidor:</strong> {{ $order->server_name }}</p>
                            <p><strong>Total:</strong> <span class="text-primary fw-bold">{{ $order->formatted_total_amount }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Itens do Pedido</h5>
                            @foreach($order->orderItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $item->product_name ?? $item->product->name }}</span>
                                    <span>R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Formulário de Pagamento -->
                    <div class="payment-form">
                        <h5 class="mb-3">Informações de Pagamento</h5>

                        <form id="payment-form" action="{{ route('payments.confirm', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_intent_id" id="payment-intent-id" value="{{ $paymentIntent->id }}">

                            <div class="mb-3">
                                <label for="card-element" class="form-label">Cartão de Crédito</label>
                                <div id="card-element" class="form-control">
                                    <!-- Stripe Elements será inserido aqui -->
                                </div>
                                <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                            </div>

                            <div class="mb-3">
                                <label for="card-holder-name" class="form-label">Nome no Cartão</label>
                                <input type="text" id="card-holder-name" class="form-control" placeholder="Nome como aparece no cartão" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" id="submit-button" class="btn btn-primary btn-lg">
                                    <span id="button-text">Pagar R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Informações de Segurança -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-shield-alt text-success me-2"></i>Pagamento Seguro</h6>
                        <p class="mb-0 small text-muted">
                            Seus dados de pagamento são processados de forma segura pelo Stripe.
                            Não armazenamos informações do seu cartão em nossos servidores.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Configuração do Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    // Criar elemento do cartão
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    // Montar elemento do cartão
    cardElement.mount('#card-element');

    // Manipular erros de validação em tempo real
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Manipular envio do formulário
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        // Desabilitar botão e mostrar spinner
        submitButton.disabled = true;
        buttonText.classList.add('d-none');
        spinner.classList.remove('d-none');

        try {
            // Criar método de pagamento
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: document.getElementById('card-holder-name').value,
                },
            });

            if (error) {
                throw error;
            }

            // Adicionar payment_method_id ao formulário
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method_id');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);

            // Enviar formulário
            form.submit();

        } catch (error) {
            // Reabilitar botão e esconder spinner
            submitButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');

            // Mostrar erro
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        }
    });
</script>
@endsection
