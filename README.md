# 🎬 Cinema Booking & Ticketing System (CBTS)

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)](https://www.php.net) [![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![Livewire 3](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)](https://livewire.laravel.com) [![Architecture](https://img.shields.io/badge/Architecture-DDD_%2F_Clean-blue?style=for-the-badge)](https://en.wikipedia.org/wiki/Domain-driven_design)

  

> Sistema de reserva y venta de boletos para un cine con 9 salas, desarrollado con **Laravel 12**, **Livewire 3** y una arquitectura basada en **Domain‑Driven Design (DDD)**, **Clean Architecture** y principios **SOLID**. Este proyecto sirve como ejemplo profesional de cómo aplicar patrones de diseño y buenas prácticas en un entorno Laravel. Es una demostración de ingeniería de software avanzada aplicada a un dominio con reglas de negocio complejas y desafíos de concurrencia en tiempo real.

  

---
  
## 🧠 El Desafío del Dominio
  

Gestionar un cine de **9 salas** (3D, Premium, General) con **5-6 funciones diarias** por sala implica resolver:

*  **Precios Dinámicos:** Variación por tipo de sala, día de la semana y políticas de descuento.

*  **Alta Concurrencia:** Evitar la "doble reserva" de asientos mediante bloqueos atómicos.

*  **Ciclo de Vida Complejo:** Reservas que nacen como `Pending`, transicionan a `Confirmed` o expiran automáticamente.

---

## ⚙️ Stack Tecnológico & Infraestructura


*  **PHP 8.2+** & **Laravel 12+**

*  **Livewire 3:** Reactividad del lado del servidor.

*  **SQLite/MySQL:** Persistencia de datos.

*  **Cache/Concurrencia**: Redis (para bloqueo de asientos)

*  **Pest / PHPUnit:** Suite de pruebas para asegurar reglas de arquitectura y dominio.

*  **Otros**: Laravel Events, Queues, etc.

---

## 🚀 Instalación y Setup

1.  **Clonar el repositorio:**

```bash
git clone [https://github.com/Alexis79Bck/livewire3-crud-cinema-project.git](https://github.com/Alexis79Bck/livewire3-crud-cinema-project.git)
cd livewire3-crud-cinema-project
```

2.  **Instalar dependencias:**

```bash

composer install

npm install && npm  run  build
```

3.  **Configurar entorno:**

```bash
cp .env.example .env
php artisan key:generate
```

4.  **Migraciones y Seeds (Crea las 9 salas automáticamente):**

```bash
php artisan migrate --seed
```
5.  **Ejecutar el Servidor:**

```bash
php artisan serve
```

---


## ✨ Características principales

- Gestión de **películas**, **salas** y **asientos** (contexto Catalog).
- Programación de **funciones** (shows) con horarios (contexto Scheduling).
- **Reserva de asientos** con control de concurrencia y estados (pending, confirmed, cancelled, expired).
- **Cálculo dinámico de precios** según tipo de sala, día y promociones (patrón Strategy).
- **Bloqueo temporal de asientos** con Redis mientras el usuario completa la compra.
- **Eventos de dominio** para desacoplar contextos (ej. `BookingConfirmed` → email + actualización de disponibilidad).
- Panel administrativo y área de clientes con componentes **Livewire 3** interactivos.
- Arquitectura preparada para escalar y mantener.

---

## 🏗️ Arquitectura y Filosofía

A diferencia del MVC tradicional de Laravel (donde la lógica suele "ensuciar" modelos y controladores), este proyecto desacopla el **Corazón del Negocio** del Framework.

El proyecto está organizado en **contextos delimitados** y **capas**, siguiendo los principios de Domain‑Driven Design y Clean Architecture. Las dependencias siempre apuntan hacia el dominio.
  

### Estrategia de Capas:

1.  **Domain:** Lógica pura. Agregados, Entidades y Value Objects. Cero dependencias externas.

2.  **Application:** Orquestación de casos de uso (Commands/Queries/Handlers).

3.  **Infrastructure:** Implementaciones concretas (Eloquent, Redis, Mailers, Mappers).

4.  **Presentation (Livewire 3):** UI reactiva y moderna sin la complejidad de un SPA pesado.

---

### Contextos delimitados (Bounded Contexts)

| Contexto       | Responsabilidad                                                                 |
|----------------|---------------------------------------------------------------------------------|
| **Catalog**    | Películas, salas y asientos (datos maestros).                                   |
| **Scheduling** | Funciones (shows): asignación de película, sala y horario.                      |
| **Booking**    | Corazón del negocio: reservas, tickets, estados, precios y políticas.           |
| **Shared**     | Elementos comunes (Value Objects, Enums) compartidos por los demás contextos.  


### 🛠️ Patrones de Diseño Implementados

Este repositorio destaca por el uso justificado de patrones de diseño:

*  **Aggregate Root:**  `Booking` actúa como puerta de entrada para garantizar la consistencia de los `Tickets`.

*  **State Pattern:** Gestión elegante de estados (`Pending` -> `Confirmed`) sin `if/else` interminables.

*  **Strategy Pattern:** Cálculo de precios (`StandardPrice`, `PremiumPrice`, `WeekendPrice`) escalable y SOLID.

*  **Repository Pattern:** Interfaces de dominio implementadas en infraestructura mediante **Eloquent**.

*  **Domain Events:** Desacoplamiento de efectos secundarios (ej: enviar QR tras confirmar pago).

*  **Data Mapper:** Transformación bidireccional entre Modelos Eloquent y Entidades de Dominio.

---

## 📁 Estructura de carpetas


    app/
    ├── Domain/
    │   ├── Catalog/
    │   │   ├── Entities/
    │   │   │   ├── Movie.php
    │   │   │   ├── Auditorium.php
    │   │   │   └── Seat.php
    │   │   ├── Repositories/
    │   │   │   ├── MovieRepository.php
    │   │   │   └── AuditoriumRepository.php
    │   │   └── Events/
    │   │
    │   ├── Scheduling/
    │   │   ├── Aggregates/
    │   │   │   └── Showtime/
    │   │   │       ├── Showtime.php
    │   │   │       ├── ShowtimeId.php
    │   │   │       ├── ShowtimeStatus.php
    │   │   │       └── SeatAvailability.php
    │   │   ├── Repositories/
    │   │   │   └── ShowtimeRepository.php
    │   │   └── Events/
    │   │
    │   ├── Booking/
    │   │   ├── Aggregates/
    │   │   │   └── Booking/
    │   │   │       ├── Booking.php
    │   │   │       ├── BookingId.php
    │   │   │       ├── BookingStatus.php
    │   │   │       └── Ticket.php
    │   │   ├── Pricing/
    │   │   │   ├── PriceCalculator.php
    │   │   │   ├── StandardPrice.php
    │   │   │   ├── PremiumPrice.php
    │   │   │   └── WeekendPrice.php
    │   │   ├── Policies/
    │   │   │   ├── ReservationPolicy.php
    │   │   │   └── CancellationPolicy.php
    │   │   ├── Repositories/
    │   │   │   └── BookingRepository.php
    │   │   ├── Events/
    │   │   └── Exceptions/
    │   │
    │   └── Shared/
    │       ├── ValueObjects/
    │       │   ├── Money.php
    │       │   ├── Email.php
    │       │   └── SeatNumber.php
    │       └── Enums/
    │           ├── SeatType.php
    │           └── PaymentMethod.php
    │
    ├── Application/
    │   ├── Catalog/
    │   ├── Scheduling/
    │   └── Booking/
    │       ├── Commands/
    │       ├── Queries/
    │       ├── Handlers/
    │       ├── DTOs/
    │       └── Services/
    │           └── BookingFacade.php
    │
    ├── Infrastructure/
    │   ├── Persistence/
    │   │   ├── Eloquent/
    │   │   │   ├── Models/
    │   │   │   └── Repositories/
    │   │   └── Mappers/
    │   ├── Services/
    │   │   ├── FakePaymentGateway.php
    │   │   └── RedisSeatLockManager.php
    │   └── Providers/
    │
    ├── Livewire/
    │
    └── Support/


## 🧪 Pruebas Automatizadas

El proyecto sigue una pirámide de pruebas estricta:

- Unit Tests: Validación de reglas de dominio y Value Objects (sin DB).

- Integration Tests: Verificación de Repositorios y Mappers.

- Feature Tests: Flujo completo desde el componente Livewire hasta la DB.

---

## 🤝 Contribución
Este es un proyecto de portafolio y ejemplo didáctico. Si deseas contribuir o reportar errores, por favor abre un issue o envía un pull request. Toda ayuda es bienvenida.

---

## 📄 Licencia
Este proyecto es de código abierto y se distribuye bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.


## 👨‍💻 Sobre el Autor

Este proyecto fue desarrollado por Alexis E. Mata, bajo una visión Senior Fullstack, priorizando el código que expresa el negocio sobre el código que simplemente "funciona". El objetivo es demostrar que Laravel es una herramienta excepcionalmente potente para aplicaciones empresariales cuando se combina con patrones de diseño robustos.