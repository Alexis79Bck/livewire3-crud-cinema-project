# 🎬 Cinema Booking & Ticketing System (CBTS)

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)](https://www.php.net) [![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com) [![Livewire 3](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)](https://livewire.laravel.com) [![Architecture](https://img.shields.io/badge/Architecture-DDD_%2F_Clean-blue?style=for-the-badge)](https://en.wikipedia.org/wiki/Domain-driven_design)

  

> Sistema de reserva y venta de boletos para un cine con 9 salas, desarrollado con **Laravel 12**, **Livewire 3** y una arquitectura basada en **Domain‑Driven Design (DDD)**, **Clean Architecture** y principios **SOLID**. Este proyecto sirve como ejemplo profesional de cómo aplicar patrones de diseño y buenas prácticas en un entorno Laravel.

  

---
  
## 🧠 El Desafío del Dominio
  

Gestionar un cine de **9 salas** (3D, Premium, General) con **5-6 funciones diarias** por sala implica resolver:

*  **Precios Dinámicos:** Variación por tipo de sala, día de la semana y políticas de descuento.

*  **Alta Concurrencia:** Evitar la "doble reserva" de asientos mediante bloqueos atómicos.

*  **Ciclo de Vida Complejo:** Reservas que nacen como `Pending`, transicionan a `Confirmed` o expiran automáticamente.
  

## 🏗️ Arquitectura y Filosofía

A diferencia del MVC tradicional de Laravel (donde la lógica suele "ensuciar" modelos y controladores), este proyecto desacopla el **Corazón del Negocio** del Framework.
  

### Estrategia de Capas:

1.  **Domain:** Lógica pura. Agregados, Entidades y Value Objects. Cero dependencias externas.

2.  **Application:** Orquestación de casos de uso (Commands/Queries/Handlers).

3.  **Infrastructure:** Implementaciones concretas (Eloquent, Redis, Mailers, Mappers).

4.  **Presentation (Livewire 3):** UI reactiva y moderna sin la complejidad de un SPA pesado.

---

## 🗺️ Bounded Contexts (Contextos Delimitados)

El sistema se divide en sub-dominios para garantizar un bajo acoplamiento:
| Contexto  | Responsabilidad  |
|--|--|
| **Catalog** | Gestión de películas, salas y asientos (Master Data). |
|**Scheduling**| Programación de funciones y asignación de horarios.|
| **Booking** | 🚀 **El Corazón:** Reservas, tickets y gestión de estados. |
| **Shared** |  Elementos transversales (Money VO, Enums, Identificadores). |
---

## 🛠️ Patrones de Diseño Implementados

Este repositorio destaca por el uso justificado de patrones de diseño:

*  **Aggregate Root:**  `Booking` actúa como puerta de entrada para garantizar la consistencia de los `Tickets`.

*  **State Pattern:** Gestión elegante de estados (`Pending` -> `Confirmed`) sin `if/else` interminables.

*  **Strategy Pattern:** Cálculo de precios (`StandardPrice`, `PremiumPrice`, `WeekendPrice`) escalable y SOLID.

*  **Repository Pattern:** Interfaces de dominio implementadas en infraestructura mediante **Eloquent**.

*  **Domain Events:** Desacoplamiento de efectos secundarios (ej: enviar QR tras confirmar pago).

*  **Data Mapper:** Transformación bidireccional entre Modelos Eloquent y Entidades de Dominio.

---

## ⚙️ Stack Tecnológico & Infraestructura


*  **PHP 8.2+** & **Laravel 12+**

*  **Livewire 3:** Reactividad del lado del servidor.

*  **SQLite/MySQL:** Persistencia de datos.

*  **Pest / PHPUnit:** Suite de pruebas para asegurar reglas de arquitectura y dominio.

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

## 🧪 Pruebas Automatizadas

El proyecto sigue una pirámide de pruebas estricta:

- Unit Tests: Validación de reglas de dominio y Value Objects (sin DB).

- Integration Tests: Verificación de Repositorios y Mappers.

- Feature Tests: Flujo completo desde el componente Livewire hasta la DB.

  

## 👨‍💻 Sobre el Autor

Este proyecto fue desarrollado bajo una visión Senior Fullstack, priorizando el código que expresa el negocio sobre el código que simplemente "funciona". El objetivo es demostrar que Laravel es una herramienta excepcionalmente potente para aplicaciones empresariales cuando se combina con patrones de diseño robustos.