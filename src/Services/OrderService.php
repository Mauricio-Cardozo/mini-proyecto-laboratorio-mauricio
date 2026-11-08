<?php

/**
 * ============================================================================
 *  SERVICIO DE PEDIDOS
 *  Patron: FACADE (estructural) + separacion en servicios cohesivos
 * ============================================================================
 *
 *  ✅ La fachada solo COORDINA. Cada paso vive en su propio servicio:
 *     OrderValidator (validacion), OrderPricing (precio con Strategy),
 *     OrderRepository (persistencia), NotificationSender (Factory),
 *     OrderEvents (Observer) y los decoradores de reportes (Decorator).
 *
 *  ⚠️ Si esta clase empieza a DECIDIR reglas de negocio, se convierte
 *     en la nueva clase que hace todo y volvimos al punto de partida.
 * ============================================================================
 */

class OrderService
{
    public function __construct(
        private OrderValidator $validator = new OrderValidator(),
        private OrderPricing $pricing = new OrderPricing(),
        private OrderRepository $repository = new OrderRepository(),
        private NotificationSender $notificador = new NotificationSender(),
        private OrderEvents $eventos = new OrderEvents()
    ) {
        $this->eventos->attach(new EmailObserver());
        $this->eventos->attach(new SmsObserver());
        $this->eventos->attach(new DashboardObserver());
    }

    public function procesarPedidoCompleto(
        int $id,
        string $paciente,
        float $monto,
        string $tipoPaciente,
        string $tipoNotificacion,
        string $destino
    ): void {
        $error = $this->validator->validar($paciente, $monto);
        if ($error !== null) {
            echo $error . '<br>';
            return;
        }

        $total = $this->pricing->total($monto, $tipoPaciente);
        $order = new Order($id, $paciente, $total, $tipoPaciente);
        $this->repository->save($order);
        $this->notificador->enviar($tipoNotificacion, $destino, "Pedido {$id} por $ {$total}");
        $this->eventos->notify($order);

        echo (new PdfReportDecorator(new DigitalSignatureDecorator(new BasicReport("Pedido {$id}"))))->generate() . '<br>';
        echo "<p>Pedido {$id} procesado. Total: $ {$total}</p>";
    }

    /** Alta simple que usa el controlador: calcula, guarda y avisa por email. */
    public function crearPedido(Order $order): float
    {
        $total = $this->pricing->total($order->amount, $order->patientType);
        $this->repository->save($order);
        $this->notificador->enviar('email', 'paciente@mail.com', "Pedido {$order->id} creado");

        return $total;
    }
    /**
     * Obtiene los pedidos y prepara los datos para la vista MVC.
     * El cálculo del total se delega a OrderPricing (Strategy).
     */
    public function listarPedidos(): array
    {
        $pedidos = [];

        foreach ($this->repository->all() as $order) {
            $pedidos[] = [
                'id' => $order->id,
                'paciente' => $order->patient,
                'total' => $this->pricing->total(
                    $order->amount,
                    $order->patientType
                ),
            ];
        }

        return $pedidos;
    }
}

/*
 * PREGUNTA PARA EL PR: Facade elimina la complejidad o la mueve de lugar?
 */
